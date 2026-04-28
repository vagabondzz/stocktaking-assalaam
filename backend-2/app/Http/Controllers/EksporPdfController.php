<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\SatuanBarang;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EksporPdfController extends Controller {

    public function buildReportSnapshot($itemLokasi, $team = null, $year = null, array $deletedItems = [])
{
    $reportDateTime = $itemLokasi->DATE_OPEN
        ? Carbon::parse($itemLokasi->DATE_OPEN)
        : Carbon::now();
    $reportYear = (int) ($year ?: $reportDateTime->format('Y'));
    $reportPayload = [
        'team' => $this->buildSnapshotTeam($team, $itemLokasi),
        'lokasi' => $itemLokasi->ISITEAMITEM_KODELOKASI,
        'item_id' => $itemLokasi->ISITEAMITEM_ID,
        'year' => $reportYear,
        'generated_at' => $reportDateTime->format('d-m-Y H:i'),
        'logo_base64' => $this->buildSnapshotLogo(),
        'items' => $this->buildSnapshotDetails($itemLokasi, $reportYear)->values()->all(),
        'deleted_items' => array_values($deletedItems),
    ];

    return [
        'report_date' => $reportDateTime->toDateString(),
        'report_datetime' => $reportDateTime->toDateTimeString(),
        'kode_lokasi' => $itemLokasi->ISITEAMITEM_KODELOKASI,
        'item_id' => $itemLokasi->ISITEAMITEM_ID,
        'report_year' => $reportYear,
        'team_id' => $itemLokasi->ISITEAMITEM_TEAM_ID,
        'total_items' => count($reportPayload['items']),
        'total_logs' => collect($reportPayload['items'])->sum(fn ($detail) => count($detail['logs'] ?? [])),
        'source_updated_at' => $itemLokasi->DATE_MODIFY ?? $itemLokasi->DATE_OPEN,
        'report_payload' => $reportPayload,
    ];
}

    public function getStocktakingReportData(Request $request)
{
    try {

        $request->validate([
            'kode_lokasi' => 'required|string',
            'year' => 'nullable|integer|min:2000|max:3000',
            'deleted_items' => 'nullable|array'
        ]);

        $kodeLokasi = $request->kode_lokasi;
        $year = $request->year ?? date('Y');

        $team = $this->resolveTeamFromToken();
        $itemLokasi = $this->findItemLokasi($kodeLokasi, $team);

        if (!$itemLokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan atau sudah ditutup'
            ], 404);
        }

        $snapshot = $this->buildReportSnapshot(
            $itemLokasi,
            $team,
            $year,
            $this->normalizeDeletedItems($request->input('deleted_items', []))
        );
        $reportData = $snapshot['report_payload'];

        return response()->json([
            'success' => true,
            'message' => 'Data laporan stocktaking berhasil diambil',
            'data' => $reportData
        ]);

    } catch (\Exception $e) {

        Log::error('[getStocktakingReportData] Gagal mengambil data laporan', [
            'kode_lokasi' => $request->kode_lokasi ?? null,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function exportStocktakingPdf(Request $request)
{
    try {

        $request->validate([
            'kode_lokasi' => 'required|string',
            'year' => 'nullable|integer|min:2000|max:3000',
            'deleted_items' => 'nullable|array'
        ]);

        $kodeLokasi = $request->kode_lokasi;
        $year = $request->year ?? date('Y');

        $team = JWTAuth::parseToken()->authenticate();

        $itemLokasi = $this->findItemLokasi($kodeLokasi, $team);
        if (!$itemLokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak valid untuk team ini atau sudah ditutup'
            ], 404);
        }

        $snapshot = $this->buildReportSnapshot(
            $itemLokasi,
            $team,
            $year,
            $this->normalizeDeletedItems($request->input('deleted_items', []))
        );
        $reportData = $snapshot['report_payload'];

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.stocktaking', [
                'report' => $reportData
            ]);
            return $pdf->download('stocktaking.pdf');
        }

        return response()->json([
            'success' => true,
            'message' => 'Data laporan berhasil diambil (PDF belum di-generate karena library PDF belum terpasang)',
            'data' => $reportData
        ]);

    } catch (\Exception $e) {

        Log::error('[exportStocktakingPdf] Gagal export PDF', [
            'kode_lokasi' => $request->kode_lokasi ?? null,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function publishAdminReportSnapshot(Request $request)
{
    try {
        $team = JWTAuth::parseToken()->authenticate();
        $kodeLokasi = trim((string) $request->input('kode_lokasi', ''));

        if ($kodeLokasi === '') {
            $activeLocation = Item::query()
                ->where('ISITEAMITEM_NOTEAM', $team->SOPT_NO)
                ->where('ISITEAMITEM_IS_OPEN', 0)
                ->orderByDesc('DATE_OPEN')
                ->first();

            $kodeLokasi = $activeLocation->ISITEAMITEM_KODELOKASI ?? '';
            $request->merge(['kode_lokasi' => $kodeLokasi]);
        }

        $request->validate([
            'kode_lokasi' => 'required|string',
            'deleted_items' => 'nullable|array',
        ]);

        $itemLokasi = $this->findItemLokasi($kodeLokasi, $team);

        if (!$itemLokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak valid untuk team ini atau sudah ditutup'
            ], 404);
        }

        $backend1BaseUrl = config('services.backend1.base_url');
        $backend1Token = config('services.backend1.token');

        if (!$backend1BaseUrl || !$backend1Token) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi backend-1 belum lengkap'
            ], 500);
        }

        $snapshot = $this->buildReportSnapshot(
            $itemLokasi,
            $team,
            null,
            $this->normalizeDeletedItems($request->input('deleted_items', []))
        );

        $response = Http::timeout(30)
            ->withToken($backend1Token)
            ->post(rtrim($backend1BaseUrl, '/') . '/api/report/admin-ingest', [
                'reports' => [$snapshot],
            ]);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkron report ke backend admin',
                'status' => $response->status(),
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json([
            'success' => true,
            'message' => 'Report admin berhasil disinkronkan',
            'data' => $response->json('data'),
        ]);
    } catch (\Throwable $e) {
        Log::error('[publishAdminReportSnapshot] Gagal publish report admin', [
            'kode_lokasi' => $request->input('kode_lokasi'),
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat sinkron report admin',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    private function resolveTeamFromToken()
{
    try {
        return JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return null;
    }
}

    private function findItemLokasi(string $kodeLokasi, $team = null)
{
    $query = Item::query()->with('team')
        ->where('ISITEAMITEM_KODELOKASI', $kodeLokasi)
        ->where('ISITEAMITEM_IS_OPEN', 0);

    if ($team) {
        $query->where('ISITEAMITEM_NOTEAM', $team->SOPT_NO);
    }

    return $query->first();
}

    public function buildSnapshotDetails($itemLokasi, $year)
{
    $reportDate = $itemLokasi->DATE_OPEN
        ? Carbon::parse($itemLokasi->DATE_OPEN)->toDateString()
        : Carbon::today()->toDateString();

    $details = ItemDetail::where('ISITEAMITEMDETAIL_ISITEAMITEM_ID', $itemLokasi->ISITEAMITEM_ID)
        ->where('YEAR', $year)
        ->with([
            'barang:BARANG_ID,BRG_IS_DECIMAL',
            'logs' => function ($query) use ($reportDate) {
                $query->select(
                    'LOG_BRG_ID',
                    'LOG_ISITEAMITEMDETAIL_ID',
                    'LOG_NEWQTY',
                    'DATE_CREATE'
                )
                ->whereDate('DATE_CREATE', $reportDate)
                ->orderBy('DATE_CREATE', 'asc');
            }
        ])
        ->orderBy('ISITEAMITEMDETAIL_SEQ', 'asc')
        ->get();

    $uomIds = $details->pluck('ISITEAMITEMDETAIL_BRG_UOM_ID')->unique();
    $uoms = SatuanBarang::whereIn('REF$SATUAN_ID', $uomIds)
        ->get()
        ->keyBy('REF$SATUAN_ID');

    return $details->map(function ($detail) use ($uoms, $reportDate) {
        $uom = $uoms->get($detail->ISITEAMITEMDETAIL_BRG_UOM_ID);
        $isDecimal = $detail->barang ? $detail->barang->BRG_IS_DECIMAL : 1;
        $qty = $detail->ISITEAMITEMDETAIL_QTY;
        $isNewItem = $detail->DATE_CREATE
            ? Carbon::parse($detail->DATE_CREATE)->toDateString() === $reportDate
            : false;

        if ($isDecimal == 0) {
            $qty = (int) $qty;
        } else {
            $qty = number_format((float) $qty, 3, '.', '');
        }

        return [
            'ISITEAMITEMDETAIL_ID' => $detail->ISITEAMITEMDETAIL_ID,
            'ISITEAMITEMDETAIL_SEQ' => $detail->ISITEAMITEMDETAIL_SEQ,
            'ISITEAMITEMDETAIL_BRG_ID' => $detail->ISITEAMITEMDETAIL_BRG_ID,
            'ISITEAMITEMDETAIL_BRG_CODE' => $detail->ISITEAMITEMDETAIL_BRG_CODE,
            'ISITEAMITEMDETAIL_BRG_BARCODE' => $detail->ISITEAMITEMDETAIL_BRG_BARCODE,
            'ISITEAMITEMDETAIL_BRG_NAME' => $detail->ISITEAMITEMDETAIL_BRG_NAME,
            'ISITEAMITEMDETAIL_BRG_UOM' => $uom ? $uom->SAT_CODE : null,
            'IS_DECIMAL' => $isDecimal,
            'ISITEAMITEMDETAIL_QTY' => $qty,
            'ISITEAMITEMDETAIL_DESC' => $detail->ISITEAMITEMDETAIL_DESC,
            'IS_NEW_ITEM' => $isNewItem,
            'logs' => $detail->logs->map(function ($log) {
                return [
                    'LOG_BRG_ID' => $log->LOG_BRG_ID,
                    'LOG_ISITEAMITEMDETAIL_ID' => $log->LOG_ISITEAMITEMDETAIL_ID,
                    'LOG_NEWQTY' => $log->LOG_NEWQTY,
                    'DATE_CREATE' => $log->DATE_CREATE
                ];
            })->values()
        ];
    });
}

    public function buildSnapshotTeam($team, $itemLokasi)
{
    $src = $team ?? $itemLokasi->team;
    if (!$src) {
        return null;
    }

    return [
        'stock_opname_team_id' => $src->STOCK_OPNAME_TEAM_ID ?? null,
        'no_team' => $src->SOPT_NO ?? null,
        'penghitung_1' => $src->SOPT_PENGHITUNG ?? null,
        'penghitung_2' => $src->SOPT_HELPER ?? null,
        'kode_team' => $src->KODE_TEAM ?? null
    ];
}

    public function buildSnapshotLogo()
{
    $candidates = [
        public_path('images/logoassalam.png'),
        public_path('images/logo.png'),
    ];

    $path = null;
    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            $path = $candidate;
            break;
        }
    }

    if (!$path) {
        return null;
    }

    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $mime = $ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg' : 'image/png';
    return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
}

    private function buildDetailRows($itemLokasi, $year)
{
    return $this->buildSnapshotDetails($itemLokasi, $year);
}

    private function normalizeDeletedItems(array $deletedItems = [])
{
    return collect($deletedItems)
        ->map(function ($item) {
            return [
                'item_detail_id' => $item['item_detail_id'] ?? null,
                'barang_id' => $item['barang_id'] ?? null,
                'kode_plu' => $item['kode_plu'] ?? null,
                'kode_barcode' => $item['kode_barcode'] ?? null,
                'nama_barang' => $item['nama_barang'] ?? null,
                'uom' => $item['uom'] ?? null,
                'qty_sebelumnya' => $item['qty_sebelumnya'] ?? null,
                'note_sebelumnya' => $item['note_sebelumnya'] ?? null,
                'deleted_at' => $item['deletedAt'] ?? $item['deleted_at'] ?? null,
            ];
        })
        ->filter(fn ($item) => !empty($item['kode_plu']) || !empty($item['kode_barcode']) || !empty($item['nama_barang']))
        ->values()
        ->all();
}

    private function resolveTeamInfo($team, $itemLokasi)
{
    return $this->buildSnapshotTeam($team, $itemLokasi);
}

    private function getLogoBase64()
{
    return $this->buildSnapshotLogo();
}

}

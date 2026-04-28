<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\DetailLog;
use App\Models\ItemDetail;
use App\Models\TipeBarang;
use App\Models\SatuanBarang;
use App\Models\Item;
use App\Models\Team;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LogItemController extends Controller {

    public function getPerubahanBarangByLokasi(Request $request)
{
    try {

        $request->validate([
            'kode_lokasi' => 'required|string',
            'kode_barang' => 'required|string'
        ]);

        $kodeLokasi = $request->kode_lokasi;
        $kodeBarang = $request->kode_barang;
        $team = JWTAuth::parseToken()->authenticate();
        $currentYear = date('Y');

        $itemLokasi = Item::where('ISITEAMITEM_NOTEAM', $team->SOPT_NO)
            ->where('ISITEAMITEM_KODELOKASI', $kodeLokasi)
            ->where('ISITEAMITEM_IS_OPEN', 0)
            ->first();

        if (!$itemLokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak valid untuk team ini atau sudah ditutup'
            ], 404);
        }

        $itemDetail = ItemDetail::where('ISITEAMITEMDETAIL_ISITEAMITEM_ID', $itemLokasi->ISITEAMITEM_ID)
            ->where('YEAR', $currentYear)
            ->where(function ($query) use ($kodeBarang) {
                $query->where('ISITEAMITEMDETAIL_BRG_CODE', $kodeBarang)
                    ->orWhere('ISITEAMITEMDETAIL_BRG_BARCODE', $kodeBarang);
            })
            ->with([
                'barang:BARANG_ID,BRG_IS_DECIMAL',
                'logs' => function ($query) {
                    $query->select(
                        'LOG_BRG_ID',
                        'LOG_ISITEAMITEMDETAIL_ID',
                        'LOG_NEWQTY',
                        'DATE_CREATE'
                    )->orderBy('DATE_CREATE', 'asc');
                }
            ])
            ->first();

        if (!$itemDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan pada lokasi ini'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Riwayat perubahan barang berhasil diambil',
            'data' => [
                'team' => $team->KODE_TEAM,
                'lokasi' => $kodeLokasi,
                'item_id' => $itemLokasi->ISITEAMITEM_ID,
                'barang' => [
                    'ISITEAMITEMDETAIL_ID' => $itemDetail->ISITEAMITEMDETAIL_ID,
                    'LOG_BRG_ID' => $itemDetail->ISITEAMITEMDETAIL_BRG_ID,
                    'KODE_BARANG' => $itemDetail->ISITEAMITEMDETAIL_BRG_CODE,
                    'BARCODE' => $itemDetail->ISITEAMITEMDETAIL_BRG_BARCODE,
                    'NAMA_BARANG' => $itemDetail->ISITEAMITEMDETAIL_BRG_NAME,
                    'IS_DECIMAL' => $itemDetail->barang ? $itemDetail->barang->BRG_IS_DECIMAL : 1,
                    'QTY_SAAT_INI' => $itemDetail->ISITEAMITEMDETAIL_QTY,
                    'perubahan' => $itemDetail->logs->map(function ($log) {
                        return [
                            'LOG_BRG_ID' => $log->LOG_BRG_ID,
                            'LOG_ISITEAMITEMDETAIL_ID' => $log->LOG_ISITEAMITEMDETAIL_ID,
                            'LOG_NEWQTY' => $log->LOG_NEWQTY,
                            'DATE_CREATE' => $log->DATE_CREATE
                        ];
                    })->values()
                ]
            ]
        ]);

    } catch (\Exception $e) {

        Log::error('[getPerubahanBarangByLokasi] Gagal mengambil riwayat perubahan', [
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

    public function updateLog(Request $request)
{
    try {

        $request->validate([
            'kode_lokasi' => 'required|string',
            'logs' => 'required|array|min:1',
            'logs.*.LOG_NEWQTY' => 'required|numeric|min:0'
        ]);

    } catch (\Exception $e) {

        Log::error("[updateBatch] Validasi gagal", [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal: ' . $e->getMessage()
        ], 422);
    }

    try {

        $kodeLokasi = $request->kode_lokasi;

        // 🔹 Ambil team dari JWT token
        $team = JWTAuth::parseToken()->authenticate();

        Log::info("[updateBatch] Team ditemukan", [
            'kode_team' => $team->KODE_TEAM
        ]);

        // 🔹 Validasi lokasi milik team
        $itemLokasi = Item::where('ISITEAMITEM_NOTEAM', $team->SOPT_NO)
            ->where('ISITEAMITEM_KODELOKASI', $kodeLokasi)
            ->where('ISITEAMITEM_IS_OPEN', 0)
            ->first();

        if (!$itemLokasi) {

            Log::warning("[updateBatch] Lokasi tidak valid", [
                'kode_lokasi' => $kodeLokasi,
                'team' => $team->KODE_TEAM
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak valid untuk team ini atau sudah ditutup'
            ], 404);
        }

        $archiveLogs = [];
        $processedCount = 0;

        foreach ($request->logs as $index => $log) {

            Log::debug("[updateBatch] Memproses log index {$index}", $log);

            // 🔹 Cari kode barang
            $barangKode = $log['barang_data']['ISITEAMITEMDETAIL_BRG_CODE'] ?? null;

            $barang = Barang::where('BRG_CODE', $barangKode)
                ->orWhere('BRG_CATALOG', $barangKode)
                ->first();

            if (!$barang) {

                Log::error("[updateBatch] Barang tidak ditemukan", [
                    'log_index' => $index,
                    'kode' => $barangKode
                ]);

                continue;
            }

            // 🔹 Cari item detail id
            $finalDetailId = null;

            if (
                !empty($log['LOG_ISITEAMITEMDETAIL_ID']) &&
                !str_starts_with($log['LOG_ISITEAMITEMDETAIL_ID'], 'manual-')
            ) {

                $itemDetail = ItemDetail::where(
                    'ISITEAMITEMDETAIL_ID',
                    $log['LOG_ISITEAMITEMDETAIL_ID']
                )->first();

                $finalDetailId = $itemDetail
                    ? $itemDetail->ISITEAMITEMDETAIL_ID
                    : $log['LOG_ISITEAMITEMDETAIL_ID'];
            }

            // 🔹 Truncate qty (tanpa rounding)
            // Hapus koma sebagai separator ribuan (misal: "21,000" → "21000")
            $rawQty = str_replace(',', '', (string) $log['LOG_NEWQTY']);

            if (str_contains($rawQty, '.')) {

                [$intPart, $decimalPart] = explode('.', $rawQty, 2);
                $decimalPart = substr($decimalPart, 0, 2);

                $finalQty = (float) ($intPart . '.' . $decimalPart);

            } else {

                $finalQty = (float) $rawQty;
            }

            // 🔹 Prepare insert log
            $archiveLogs[] = [

                'ISITEAMITEMDETAILLOG_ID' => $log['ISITEAMITEMDETAILLOG_ID'] ?? Str::uuid(),

                'LOG_BRG_ID' => $barang->BARANG_ID,

                'LOG_ISITEAMITEMDETAIL_ID' => $finalDetailId,

                'LOG_NEWQTY' => $finalQty,

                'DATE_CREATE' => isset($log['DATE_CREATE'])
                    ? Carbon::parse($log['DATE_CREATE'])
                    : now(),

                'DATE_MODIFY' => isset($log['DATE_MODIFY'])
                    ? Carbon::parse($log['DATE_MODIFY'])
                    : now(),

                'USER_CREATE' => $log['USER_CREATE'] ?? $team->KODE_TEAM,
            ];

            if (!empty($itemDetail)) {
                $itemDetail->ISITEAMITEMDETAIL_QTY = $finalQty;
                $itemDetail->DATE_MODIFY = now();
                $itemDetail->save();
            }

            $processedCount++;
        }

        if (empty($archiveLogs)) {

            Log::warning("[updateBatch] Tidak ada data valid yang diproses");

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang berhasil diproses.'
            ], 400);
        }

        // 🔹 Insert batch log
        DetailLog::insert($archiveLogs);

        return response()->json([
            'success' => true,
            'message' => "Perubahan item berhasil disimpan. {$processedCount} data diproses.",
            'data' => [
                'team' => $team->KODE_TEAM,
                'lokasi' => $kodeLokasi,
                'processed_count' => $processedCount,
                'total_received' => count($request->logs)
            ]
        ]);

    } catch (\Exception $e) {

        Log::error("[updateBatch] Gagal menyimpan perubahan", [
            'error_message' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan Perubahan: ' . $e->getMessage()
        ], 500);
    }
}

}

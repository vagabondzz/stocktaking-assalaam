<?php

namespace App\Http\Controllers;

use App\Models\StocktakingReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminReportController extends Controller
{
    public function ingest(Request $request)
    {
        try {
            $request->validate([
                'reports' => 'required|array|min:1',
                'reports.*.report_date' => 'required|date',
                'reports.*.kode_lokasi' => 'required|string',
                'reports.*.report_payload' => 'required|array',
            ]);

            $savedReports = $this->persistReports(collect($request->input('reports', [])));

            return response()->json([
                'success' => true,
                'message' => 'Snapshot report berhasil diterima',
                'data' => [
                    'count' => $savedReports->count(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[AdminReportController@ingest] Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan snapshot report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sync(Request $request)
    {
        try {
            [$startDate, $endDate] = $this->resolveDateRange($request);

            $backendBaseUrl = config('services.backend2.base_url');
            $backendToken = config('services.backend2.token');

            if (!$backendBaseUrl || !$backendToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi koneksi backend-2 belum lengkap',
                ], 500);
            }

            $response = Http::timeout(60)
                ->withToken($backendToken)
                ->post(rtrim($backendBaseUrl, '/') . '/api/report/stocktaking/snapshots', [
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'kode_lokasi' => $request->string('kode_lokasi')->toString() ?: null,
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil snapshot report dari backend-2',
                    'status' => $response->status(),
                    'error' => $response->json(),
                ], $response->status());
            }

            $reports = collect($response->json('data.reports', []));

            $savedReports = $this->persistReports($reports);

            return response()->json([
                'success' => true,
                'message' => 'Snapshot report berhasil disinkronkan',
                'data' => [
                    'count' => $savedReports->count(),
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[AdminReportController@sync] Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkron report admin',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            [$startDate, $endDate] = $this->resolveDateRange($request);

            $query = StocktakingReport::query()
                ->whereBetween('report_date', [$startDate->toDateString(), $endDate->toDateString()]);

            if ($request->filled('kode_lokasi')) {
                $kodeLokasi = $request->string('kode_lokasi')->toString();
                $query->where('kode_lokasi', 'like', '%' . $kodeLokasi . '%');
            }

            $reports = $query
                ->orderByDesc('report_date')
                ->orderByDesc('report_datetime')
                ->orderBy('kode_lokasi')
                ->get()
                ->map(fn (StocktakingReport $report) => $this->transformReport($report));

            $today = Carbon::today();
            $yesterday = Carbon::yesterday();

            return response()->json([
                'success' => true,
                'data' => [
                    'filters' => [
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString(),
                        'kode_lokasi' => $request->string('kode_lokasi')->toString() ?: '',
                    ],
                    'stats' => [
                        'total_reports' => $reports->count(),
                        'total_locations' => $reports->pluck('kode_lokasi')->unique()->count(),
                        'reports_today' => StocktakingReport::whereDate('report_date', $today)->count(),
                        'reports_yesterday' => StocktakingReport::whereDate('report_date', $yesterday)->count(),
                    ],
                    'reports' => $reports->values(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[AdminReportController@index] Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function downloadPdf(StocktakingReport $report)
    {
        try {
            $payload = $report->report_payload ?? [];
            $payload['logo_base64'] = $payload['logo_base64'] ?? $this->getLogoBase64();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.stocktaking', [
                'report' => $payload,
            ]);

            return $pdf->download(sprintf(
                'stocktaking-%s-%s.pdf',
                $report->kode_lokasi,
                $report->report_date?->format('Y-m-d') ?? date('Y-m-d')
            ));
        } catch (\Throwable $e) {
            Log::error('[AdminReportController@downloadPdf] Error', [
                'report_id' => $report->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat PDF report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function resolveDateRange(Request $request): array
    {
        $filter = $request->string('filter')->toString() ?: 'today';

        if ($filter === 'yesterday') {
            $date = Carbon::yesterday();
            return [$date->copy()->startOfDay(), $date->copy()->endOfDay()];
        }

        if ($filter === 'custom') {
            $start = $request->filled('start_date')
                ? Carbon::parse($request->input('start_date'))->startOfDay()
                : Carbon::today()->startOfDay();
            $end = $request->filled('end_date')
                ? Carbon::parse($request->input('end_date'))->endOfDay()
                : $start->copy()->endOfDay();

            if ($start->gt($end)) {
                [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
            }

            return [$start, $end];
        }

        if ($filter === 'all') {
            return [Carbon::create(2000, 1, 1)->startOfDay(), Carbon::today()->endOfDay()];
        }

        $date = Carbon::today();
        return [$date->copy()->startOfDay(), $date->copy()->endOfDay()];
    }

    private function transformReport(StocktakingReport $report): array
    {
        $payload = $report->report_payload ?? [];
        $team = $payload['team'] ?? [];

        return [
            'id' => $report->id,
            'report_date' => optional($report->report_date)->format('Y-m-d'),
            'report_datetime' => optional($report->report_datetime)->toIso8601String(),
            'kode_lokasi' => $report->kode_lokasi,
            'item_id' => $report->item_id,
            'report_year' => $report->report_year,
            'team' => [
                'stock_opname_team_id' => $report->team_id,
                'no_team' => $report->team_no,
                'kode_team' => $report->kode_team,
                'penghitung_1' => $report->penghitung_1 ?: ($team['penghitung_1'] ?? null),
                'penghitung_2' => $report->penghitung_2 ?: ($team['penghitung_2'] ?? null),
            ],
            'total_items' => $report->total_items,
            'total_logs' => $report->total_logs,
            'source_updated_at' => optional($report->source_updated_at)->toIso8601String(),
        ];
    }

    private function getLogoBase64(): ?string
    {
        $candidates = [
            public_path('images/logoassalam.png'),
            public_path('images/logo.png'),
        ];

        foreach ($candidates as $candidate) {
            if (!file_exists($candidate)) {
                continue;
            }

            $ext = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
            $mime = in_array($ext, ['jpg', 'jpeg'], true) ? 'image/jpeg' : 'image/png';

            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($candidate));
        }

        return null;
    }

    private function persistReports($reports)
    {
        return collect($reports)->map(function (array $report) {
            $payload = $report['report_payload'] ?? [];
            $team = $payload['team'] ?? [];

            return StocktakingReport::updateOrCreate(
                [
                    'report_date' => $report['report_date'],
                    'kode_lokasi' => $report['kode_lokasi'],
                    'team_id' => $team['stock_opname_team_id'] ?? $report['team_id'] ?? 'unknown',
                ],
                [
                    'report_datetime' => $report['report_datetime'] ?? null,
                    'item_id' => $report['item_id'] ?? null,
                    'report_year' => $report['report_year'] ?? null,
                    'team_no' => $team['no_team'] ?? null,
                    'kode_team' => $team['kode_team'] ?? null,
                    'penghitung_1' => $team['penghitung_1'] ?? null,
                    'penghitung_2' => $team['penghitung_2'] ?? null,
                    'total_items' => (int) ($report['total_items'] ?? 0),
                    'total_logs' => (int) ($report['total_logs'] ?? 0),
                    'source_updated_at' => $report['source_updated_at'] ?? null,
                    'report_payload' => $payload,
                ]
            );
        });
    }
}

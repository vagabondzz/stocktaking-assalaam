<?php

namespace App\Http\Controllers;

use App\Models\StocktakingProgressDraft;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminProgressDraftController extends Controller
{
    public function ingest(Request $request)
    {
        try {
            $request->validate([
                'draft_date' => 'nullable|date',
                'kode_lokasi' => 'required|string',
                'team' => 'nullable|array',
                'total_items' => 'required|integer|min:0',
                'items' => 'nullable|array',
                'items.*.item_identity_key' => 'required|string',
                'items.*.draft_action' => 'required|string',
                'items.*.is_counted' => 'required|boolean',
            ]);

            $draftDate = $request->filled('draft_date')
                ? Carbon::parse($request->input('draft_date'))->toDateString()
                : Carbon::today()->toDateString();
            $today = Carbon::today()->toDateString();

            DB::transaction(function () use ($request, $draftDate, $today) {
                StocktakingProgressDraft::whereDate('draft_date', '<', $today)->delete();

                $team = $request->input('team', []);
                $draft = StocktakingProgressDraft::updateOrCreate(
                    [
                        'draft_date' => $draftDate,
                        'kode_lokasi' => $request->input('kode_lokasi'),
                    ],
                    [
                        'team_id' => $team['stock_opname_team_id'] ?? null,
                        'team_no' => $team['no_team'] ?? null,
                        'kode_team' => $team['kode_team'] ?? null,
                        'penghitung_1' => $team['penghitung_1'] ?? null,
                        'penghitung_2' => $team['penghitung_2'] ?? null,
                        'total_items' => (int) $request->input('total_items', 0),
                        'last_activity_at' => now(),
                    ]
                );

                foreach ($request->input('items', []) as $item) {
                    $draft->items()->updateOrCreate(
                        [
                            'item_identity_key' => $item['item_identity_key'],
                        ],
                        [
                            'item_detail_id' => $item['item_detail_id'] ?? null,
                            'barang_id' => $item['barang_id'] ?? null,
                            'kode_plu' => $item['kode_plu'] ?? null,
                            'kode_barcode' => $item['kode_barcode'] ?? null,
                            'nama_barang' => $item['nama_barang'] ?? null,
                            'uom' => $item['uom'] ?? null,
                            'draft_action' => $item['draft_action'] ?? 'edited',
                            'is_counted' => (bool) ($item['is_counted'] ?? true),
                            'draft_qty' => $item['draft_qty'] ?? null,
                            'draft_note' => $item['draft_note'] ?? null,
                            'source_updated_at' => !empty($item['source_updated_at'])
                                ? Carbon::parse($item['source_updated_at'])
                                : now(),
                        ]
                    );
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Draft progress berhasil disimpan',
            ]);
        } catch (\Throwable $e) {
            Log::error('[AdminProgressDraftController@ingest] Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan draft progress',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $today = Carbon::today();
            $filter = $request->input('filter', 'today');
            [$startDate, $endDate] = $this->resolveDateRange($request, $today, $filter);
            $selectedYear = (int) $request->input('year', $startDate->year);

            $query = StocktakingProgressDraft::query()
                ->with('items')
                ->whereBetween('draft_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ])
                ->orderBy('kode_lokasi');

            if ($request->filled('kode_lokasi')) {
                $kodeLokasi = $request->string('kode_lokasi')->toString();
                $query->where('kode_lokasi', 'like', '%' . $kodeLokasi . '%');
            }

            $drafts = $query->get();
            $locations = $drafts
                ->map(fn (StocktakingProgressDraft $draft) => $this->transformDraft($draft))
                ->values();

            $monthlySummary = collect();
            if ($request->filled('kode_lokasi')) {
                $monthlySummary = $this->buildMonthlySummary(
                    $request->string('kode_lokasi')->toString(),
                    $selectedYear
                );
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'generated_at' => now()->toIso8601String(),
                    'filter' => $filter,
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'year' => $selectedYear,
                    'total_locations' => $locations->count(),
                    'locations' => $locations,
                    'monthly_summary' => $monthlySummary,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[AdminProgressDraftController@index] Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil draft progress',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    protected function resolveDateRange(Request $request, Carbon $today, string $filter): array
    {
        return match ($filter) {
            'yesterday' => [
                $today->copy()->subDay()->startOfDay(),
                $today->copy()->subDay()->endOfDay(),
            ],
            'custom' => [
                Carbon::parse($request->input('start_date', $today->toDateString()))->startOfDay(),
                Carbon::parse($request->input('end_date', $request->input('start_date', $today->toDateString())))->endOfDay(),
            ],
            'all' => [
                Carbon::create(2000, 1, 1)->startOfDay(),
                $today->copy()->endOfDay(),
            ],
            default => [
                $today->copy()->startOfDay(),
                $today->copy()->endOfDay(),
            ],
        };
    }

    protected function transformDraft(StocktakingProgressDraft $draft): array
    {
        $countedItems = $draft->items
            ->where('is_counted', true)
            ->where('draft_action', '!=', 'deleted')
            ->count();

        $totalItems = (int) $draft->total_items;
        $progressPercent = $totalItems > 0
            ? round(($countedItems / $totalItems) * 100, 2)
            : 0;

        return [
            'id' => $draft->id,
            'item_id' => (string) $draft->id,
            'kode_lokasi' => $draft->kode_lokasi,
            'draft_date' => optional($draft->draft_date)->toDateString(),
            'report_year' => optional($draft->draft_date)->format('Y'),
            'report_month' => optional($draft->draft_date)->format('m'),
            'total_items' => $totalItems,
            'counted_items_today' => $countedItems,
            'remaining_items' => max($totalItems - $countedItems, 0),
            'progress_percent' => $progressPercent,
            'is_complete' => $totalItems > 0 && $countedItems >= $totalItems,
            'opened_at' => null,
            'last_activity_at' => optional($draft->last_activity_at)->toIso8601String(),
            'team' => [
                'stock_opname_team_id' => $draft->team_id,
                'no_team' => $draft->team_no,
                'kode_team' => $draft->kode_team,
                'penghitung_1' => $draft->penghitung_1,
                'penghitung_2' => $draft->penghitung_2,
            ],
        ];
    }

    protected function buildMonthlySummary(string $kodeLokasi, int $selectedYear)
    {
        $drafts = StocktakingProgressDraft::query()
            ->with('items')
            ->whereYear('draft_date', $selectedYear)
            ->where('kode_lokasi', 'like', '%' . $kodeLokasi . '%')
            ->get();

        $mappedDrafts = $drafts->map(fn (StocktakingProgressDraft $draft) => $this->transformDraft($draft));

        return collect(range(1, 12))->map(function (int $month) use ($mappedDrafts) {
            $monthlyItems = $mappedDrafts->filter(
                fn (array $draft) => (int) ($draft['report_month'] ?? 0) === $month
            );

            return [
                'month_number' => $month,
                'month_label' => Carbon::create(null, $month, 1)->translatedFormat('F'),
                'average_progress_percent' => $monthlyItems->isNotEmpty()
                    ? round($monthlyItems->avg('progress_percent'), 2)
                    : 0,
                'counted_plu_total' => (int) $monthlyItems->sum('counted_items_today'),
                'days_with_data' => $monthlyItems->count(),
            ];
        })->values();
    }
}

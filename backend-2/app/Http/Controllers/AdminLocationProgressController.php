<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminLocationProgressController extends Controller
{
    public function index(Request $request)
    {
        try {
            $today = now();
            $currentYear = $today->year;
            $kodeLokasi = trim((string) $request->input('kode_lokasi', ''));

            $query = Item::query()
                ->with([
                    'team',
                    'details' => function ($detailQuery) use ($currentYear, $today) {
                        $detailQuery->where('YEAR', $currentYear)
                            ->with([
                                'logs' => function ($logQuery) use ($today) {
                                    $logQuery->whereDate('DATE_CREATE', $today->toDateString());
                                },
                            ]);
                    },
                ])
                ->where('ISITEAMITEM_IS_OPEN', 0)
                ->whereNotNull('DATE_OPEN')
                ->orderByDesc('DATE_OPEN');

            if ($kodeLokasi !== '') {
                $query->where('ISITEAMITEM_KODELOKASI', 'like', '%' . $kodeLokasi . '%');
            } else {
                $query->whereYear('DATE_OPEN', $currentYear);
            }

            $locations = $query->get()->map(function (Item $item) use ($today, $currentYear) {
                $details = $item->details ?? collect();
                $totalItems = $details->count();

                $countedToday = $details->filter(function ($detail) use ($today) {
                    $timestamps = array_filter([
                        $detail->DATE_MODIFY ?? null,
                        $detail->DATE_CREATE ?? null,
                    ]);

                    foreach ($timestamps as $timestamp) {
                        try {
                            if (Carbon::parse($timestamp)->isSameDay($today)) {
                                return true;
                            }
                        } catch (\Throwable $e) {
                            continue;
                        }
                    }

                    if (($detail->logs?->count() ?? 0) > 0) {
                        return true;
                    }

                    return false;
                })->count();

                $progressPercent = $totalItems > 0
                    ? round(($countedToday / $totalItems) * 100, 2)
                    : 0;

                $latestActivity = $details
                    ->map(function ($detail) {
                        return $detail->DATE_MODIFY ?? $detail->DATE_CREATE ?? null;
                    })
                    ->filter()
                    ->sortDesc()
                    ->values()
                    ->first();

                return [
                    'item_id' => $item->ISITEAMITEM_ID,
                    'kode_lokasi' => $item->ISITEAMITEM_KODELOKASI,
                    'report_year' => $currentYear,
                    'total_items' => $totalItems,
                    'counted_items_today' => $countedToday,
                    'remaining_items' => max($totalItems - $countedToday, 0),
                    'progress_percent' => $progressPercent,
                    'is_complete' => $totalItems > 0 && $countedToday >= $totalItems,
                    'opened_at' => $item->DATE_OPEN ? Carbon::parse($item->DATE_OPEN)->toIso8601String() : null,
                    'last_activity_at' => $latestActivity ? Carbon::parse($latestActivity)->toIso8601String() : null,
                    'team' => [
                        'stock_opname_team_id' => $item->team->STOCK_OPNAME_TEAM_ID ?? $item->ISITEAMITEM_TEAM_ID,
                        'no_team' => $item->team->SOPT_NO ?? $item->ISITEAMITEM_NOTEAM,
                        'kode_team' => $item->team->KODE_TEAM ?? null,
                        'penghitung_1' => $item->team->SOPT_PENGHITUNG ?? null,
                        'penghitung_2' => $item->team->SOPT_HELPER ?? null,
                    ],
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'year' => $currentYear,
                    'generated_at' => $today->toIso8601String(),
                    'total_locations' => $locations->count(),
                    'locations' => $locations,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[AdminLocationProgressController@index] Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil progress lokasi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

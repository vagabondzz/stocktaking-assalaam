<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminOpenLocationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $currentYear = now()->year;
            $kodeLokasi = trim((string) $request->input('kode_lokasi', ''));

            $query = Item::query()
                ->with('team')
                ->where('ISITEAMITEM_IS_OPEN', 0)
                ->whereNotNull('DATE_OPEN')
                ->orderByDesc('DATE_OPEN');

            if ($kodeLokasi !== '') {
                $query->where('ISITEAMITEM_KODELOKASI', 'like', '%' . $kodeLokasi . '%');
            } else {
                $query->whereYear('DATE_OPEN', $currentYear);
            }

            $locations = $query->get()->map(function (Item $item) {
                $openedAt = $item->DATE_OPEN ? Carbon::parse($item->DATE_OPEN) : null;

                return [
                    'item_id' => $item->ISITEAMITEM_ID,
                    'kode_lokasi' => $item->ISITEAMITEM_KODELOKASI,
                    'status' => $item->ISITEAMITEM_STATUS,
                    'opened_at' => $openedAt?->toIso8601String(),
                    'opened_for_minutes' => $openedAt ? $openedAt->diffInMinutes(now()) : null,
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
                    'total_open_locations' => $locations->count(),
                    'locations' => $locations,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[AdminOpenLocationController@index] Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil lokasi yang sedang dibuka',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

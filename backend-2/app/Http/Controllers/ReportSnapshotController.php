<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportSnapshotController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'kode_lokasi' => 'nullable|string',
            ]);

            $startDate = $request->filled('start_date')
                ? Carbon::parse($request->input('start_date'))->startOfDay()
                : Carbon::today()->startOfDay();
            $endDate = $request->filled('end_date')
                ? Carbon::parse($request->input('end_date'))->endOfDay()
                : $startDate->copy()->endOfDay();

            if ($startDate->gt($endDate)) {
                [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
            }

            $query = Item::query()
                ->with('team')
                ->whereNotNull('DATE_OPEN')
                ->whereBetween('DATE_OPEN', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
                ->orderByDesc('DATE_OPEN');

            if ($request->filled('kode_lokasi')) {
                $query->where('ISITEAMITEM_KODELOKASI', $request->input('kode_lokasi'));
            }

            $reports = $query->get()->map(function (Item $itemLokasi) {
                return app(EksporPdfController::class)->buildReportSnapshot($itemLokasi);
            })->values();

            return response()->json([
                'success' => true,
                'message' => 'Snapshot report berhasil diambil',
                'data' => [
                    'reports' => $reports,
                    'count' => $reports->count(),
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[ReportSnapshotController@index] Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil snapshot report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}

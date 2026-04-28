<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class DraftProgressController extends Controller
{
    public function sync(Request $request)
    {
        try {
            $request->validate([
                'kode_lokasi' => 'required|string',
                'total_items' => 'required|integer|min:0',
                'items' => 'nullable|array',
                'items.*.item_identity_key' => 'required|string',
                'items.*.draft_action' => 'required|string',
                'items.*.is_counted' => 'required|boolean',
            ]);

            /** @var Team $team */
            $team = JWTAuth::parseToken()->authenticate();

            $backend1BaseUrl = config('services.backend1.base_url');
            $backend1Token = config('services.backend1.token');

            if (!$backend1BaseUrl || !$backend1Token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi backend-1 belum lengkap',
                ], 500);
            }

            $response = Http::timeout(20)
                ->withToken($backend1Token)
                ->post(rtrim($backend1BaseUrl, '/') . '/api/report/progress-draft', [
                    'draft_date' => Carbon::today()->toDateString(),
                    'kode_lokasi' => $request->input('kode_lokasi'),
                    'total_items' => (int) $request->input('total_items', 0),
                    'team' => [
                        'stock_opname_team_id' => $team->STOCK_OPNAME_TEAM_ID ?? null,
                        'no_team' => $team->SOPT_NO ?? null,
                        'kode_team' => $team->KODE_TEAM ?? null,
                        'penghitung_1' => $team->SOPT_PENGHITUNG ?? null,
                        'penghitung_2' => $team->SOPT_HELPER ?? null,
                    ],
                    'items' => $request->input('items', []),
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal sinkron draft progress ke backend admin',
                    'status' => $response->status(),
                    'error' => $response->json(),
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'message' => 'Draft progress berhasil disinkronkan',
            ]);
        } catch (\Throwable $e) {
            Log::error('[DraftProgressController@sync] Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkron draft progress',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

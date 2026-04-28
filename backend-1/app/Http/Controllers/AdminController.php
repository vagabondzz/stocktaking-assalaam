<?php

namespace App\Http\Controllers;

use App\Models\StocktakingProgressDraft;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    // REGISTER ADMIN
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:admin,username',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => 'admin'
        ]);

        return response()->json([
            'message' => 'Admin berhasil dibuat',
            'data' => $user
        ], 201);
    }

    // LOGIN
    // LOGIN
public function login(Request $request)
{
    $credentials = $request->only('username', 'password');

    if (!$token = Auth::guard('api')->attempt($credentials)) {
        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah'
        ], 401);
    }

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'access_token' => $token,
        'token_type' => 'bearer',
        'user' => Auth::guard('api')->user()
    ]);
}

    // LOGOUT LOKASI (FORWARD KE BACKEND-2)
    public function logoutLokasi(Request $request)
    {
        $request->validate([
            'kode_lokasi' => 'required|string'
        ]);

        $backend2Url = env('BACKEND2_BASE_URL') . '/api/admin/logout/location';
        $backend2Token = env('BACKEND2_TOKEN');

        // Memanggil endpoint backend-2
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $backend2Token,
            'X-Report-Token' => $backend2Token // dikirim juga sesuai VerifyReportServiceToken middleware di backend 2
        ])->post($backend2Url, [
            'kode_lokasi' => $request->kode_lokasi
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function openLocations(Request $request)
    {
        $backend2Url = env('BACKEND2_BASE_URL') . '/api/admin/open-locations';
        $backend2Token = env('BACKEND2_TOKEN');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $backend2Token,
            'X-Report-Token' => $backend2Token,
        ])->get($backend2Url, [
            'kode_lokasi' => $request->input('kode_lokasi'),
        ]);

        $payload = $response->json();

        if (!$response->successful()) {
            return response()->json($payload, $response->status());
        }

        $locations = collect($payload['data']['locations'] ?? []);
        $kodeLokasiList = $locations
            ->pluck('kode_lokasi')
            ->filter()
            ->unique()
            ->values();

        $drafts = StocktakingProgressDraft::query()
            ->with('items')
            ->whereDate('draft_date', now()->toDateString())
            ->when(
                $kodeLokasiList->isNotEmpty(),
                fn ($query) => $query->whereIn('kode_lokasi', $kodeLokasiList->all())
            )
            ->get()
            ->keyBy('kode_lokasi');

        $mergedLocations = $locations->map(function (array $location) use ($drafts) {
            $draft = $drafts->get($location['kode_lokasi'] ?? null);

            if (!$draft) {
                $location['progress'] = [
                    'counted_items' => 0,
                    'total_items' => 0,
                    'remaining_items' => 0,
                    'progress_percent' => 0,
                ];

                return $location;
            }

            $countedItems = $draft->items
                ->where('is_counted', true)
                ->where('draft_action', '!=', 'deleted')
                ->count();
            $totalItems = (int) $draft->total_items;
            $progressPercent = $totalItems > 0
                ? round(($countedItems / $totalItems) * 100, 2)
                : 0;

            $location['progress'] = [
                'counted_items' => $countedItems,
                'total_items' => $totalItems,
                'remaining_items' => max($totalItems - $countedItems, 0),
                'progress_percent' => $progressPercent,
                'last_activity_at' => optional($draft->last_activity_at)->toIso8601String(),
            ];

            return $location;
        })->values();

        $payload['data']['locations'] = $mergedLocations;

        return response()->json($payload, $response->status());
    }

    public function locationProgress(Request $request)
    {
        $backend2Url = env('BACKEND2_BASE_URL') . '/api/admin/location-progress';
        $backend2Token = env('BACKEND2_TOKEN');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $backend2Token,
            'X-Report-Token' => $backend2Token,
        ])->get($backend2Url, [
            'kode_lokasi' => $request->input('kode_lokasi'),
        ]);

        return response()->json($response->json(), $response->status());
    }
}

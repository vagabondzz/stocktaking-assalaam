<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Item;
use App\Support\LocationSessionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTeamController extends Controller
{
    public function __construct(
        protected LocationSessionService $locationSessionService
    ) {
    }

    public function loginTeam(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_id' => 'required|string|max:191',
            'device_name' => 'nullable|string|max:191',
            'platform' => 'nullable|string|max:100',
        ]);

        $team = Team::where('SOPT_NO', $request->username)
                    ->where('KODE_TEAM', $request->password)
                    ->first();

        if (!$team) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }

        $deviceCheckResponse = $this->callBackend1(
            '/api/report/team-device-access/check',
            [
                'team_id' => $team->STOCK_OPNAME_TEAM_ID,
                'team_no' => $team->SOPT_NO,
                'kode_team' => $team->KODE_TEAM,
                'device_id' => $request->device_id,
                'device_name' => $request->input('device_name'),
            ]
        );

        if (!$deviceCheckResponse->successful()) {
            return response()->json(
                $deviceCheckResponse->json(),
                $deviceCheckResponse->status()
            );
        }

        $token = JWTAuth::fromUser($team);
        $payload = JWTAuth::setToken($token)->getPayload();
        $expiresAt = $payload->get('exp')
            ? Carbon::createFromTimestamp($payload->get('exp'))
            : null;

        $registerSessionResponse = $this->callBackend1(
            '/api/report/team-device-access/register',
            [
                'team_id' => $team->STOCK_OPNAME_TEAM_ID,
                'team_no' => $team->SOPT_NO,
                'kode_team' => $team->KODE_TEAM,
                'device_id' => $request->device_id,
                'device_name' => $request->input('device_name'),
                'platform' => $request->input('platform'),
                'user_agent' => substr((string) $request->userAgent(), 0, 65535),
                'ip_address' => $request->ip(),
                'token_identifier' => (string) $payload->get('jti', ''),
                'expires_at' => optional($expiresAt)->toIso8601String(),
            ]
        );

        if (!$registerSessionResponse->successful()) {
            return response()->json(
                $registerSessionResponse->json(),
                $registerSessionResponse->status()
            );
        }

        $teamMaxDevices = (int) ($deviceCheckResponse->json('data.max_devices') ?? 1);

        $teamData = [
            'stock_opname_team_id' => $team->STOCK_OPNAME_TEAM_ID,
            'no_team' => $team->SOPT_NO,
            'penghitung_1' => $team->SOPT_PENGHITUNG,
            'penghitung_2' => $team->SOPT_HELPER,
            'kode_team' => $team->KODE_TEAM,
            'max_devices' => $teamMaxDevices,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'data' => $teamData
        ]);
    }

    protected function callBackend1(string $path, array $payload)
    {
        $backend1BaseUrl = rtrim((string) env('BACKEND1_BASE_URL', 'http://127.0.0.1:8001'), '/');
        $backend1Token = env('BACKEND1_TOKEN', env('REPORT_SERVICE_TOKEN'));

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $backend1Token,
            'X-Report-Token' => $backend1Token,
        ])->post($backend1BaseUrl . $path, $payload);
    }

    public function logoutTeam(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|max:191',
        ]);

        $team = JWTAuth::parseToken()->authenticate();

        $response = $this->callBackend1(
            '/api/report/team-device-access/unregister',
            [
                'team_no' => $team->SOPT_NO,
                'device_id' => $request->input('device_id'),
            ]
        );

        return response()->json(
            $response->json(),
            $response->status()
        );
    }

    public function teamSessionStatus(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|max:191',
        ]);

        $team = JWTAuth::parseToken()->authenticate();

        $response = $this->callBackend1(
            '/api/report/team-device-access/status',
            [
                'team_no' => $team->SOPT_NO,
                'device_id' => $request->input('device_id'),
            ]
        );

        return response()->json(
            $response->json(),
            $response->status()
        );
    }

    public function validasiLokasi(Request $request)
    {
        $request->validate([
            'kode_lokasi' => 'required'
        ]);
    
        // ambil team dari token
        $team = JWTAuth::parseToken()->authenticate();
    
        // cek lokasi berdasarkan kode lokasi
        $lokasi = Item::with('team')
                    ->where('ISITEAMITEM_KODELOKASI', $request->kode_lokasi)
                    ->first();
    
        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Kode lokasi tidak terdaftar'
            ], 404);
        }

        $this->locationSessionService->closeIfExpired($lokasi);
        $lokasi->refresh();

        // Jika lokasi sedang dibuka (IS_OPEN == 0) oleh team LAIN
        if ($lokasi->ISITEAMITEM_IS_OPEN == 0 && $lokasi->ISITEAMITEM_NOTEAM != $team->SOPT_NO) {
            $activeTeam = [
                'stock_opname_team_id' => $lokasi->team->STOCK_OPNAME_TEAM_ID ?? $lokasi->ISITEAMITEM_TEAM_ID,
                'no_team' => $lokasi->team->SOPT_NO ?? $lokasi->ISITEAMITEM_NOTEAM,
                'penghitung_1' => $lokasi->team->SOPT_PENGHITUNG ?? null,
                'penghitung_2' => $lokasi->team->SOPT_HELPER ?? null,
                'kode_team' => $lokasi->team->KODE_TEAM ?? null,
            ];

            return response()->json([
                'success' => false,
                'message' => 'Lokasi tersebut sedang digunakan oleh team yang lain',
                'active_team' => $activeTeam,
            ], 403);
        }
    
        // Set team aktif yang sedang membuka lokasi ini.
        // Ini penting agar report/admin selalu membaca team terbaru yang benar-benar login.
        $lokasi->ISITEAMITEM_NOTEAM = $team->SOPT_NO;
        $lokasi->ISITEAMITEM_TEAM_ID = $team->STOCK_OPNAME_TEAM_ID;
    
        // update lokasi menjadi open (0 = sedang digunakan)
        $lokasi->ISITEAMITEM_IS_OPEN = 0;
        $lokasi->DATE_OPEN = Carbon::now();
        $lokasi->DATE_MODIFY = Carbon::now();
        
        // update ISITEAMITEM_STATUS = OPEN
        $lokasi->ISITEAMITEM_STATUS = 'OPEN';
        
        $lokasi->save();
    
        // format data team sesuai kebutuhan
        $teamData = [
            'stock_opname_team_id' => $team->STOCK_OPNAME_TEAM_ID,
            'no_team' => $team->SOPT_NO,
            'penghitung_1' => $team->SOPT_PENGHITUNG,
            'penghitung_2' => $team->SOPT_HELPER,
            'kode_team' => $team->KODE_TEAM
        ];
    
        return response()->json([
            'success' => true,
            'message' => 'Kode lokasi valid',
            'team' => $teamData,
            'lokasi' => $lokasi
        ]);
    }

    public function logoutLokasi(Request $request)
{
    $request->validate([
        'kode_lokasi' => 'required'
    ]);

    // ambil team dari token
    $team = JWTAuth::parseToken()->authenticate();

    // cari lokasi berdasarkan kode lokasi
    $lokasi = Item::where('ISITEAMITEM_KODELOKASI', $request->kode_lokasi)
                ->first();

    if (!$lokasi) {
        return response()->json([
            'success' => false,
            'message' => 'Kode lokasi tidak ditemukan'
        ], 404);
    }

    // update status lokasi jadi open kembali (logout)
    $lokasi->ISITEAMITEM_IS_OPEN = 1;
    $lokasi->ISITEAMITEM_STATUS = 'CLOSE';
    $lokasi->save();

    return response()->json([
        'success' => true,
        'message' => 'Berhasil logout dari lokasi',
        'lokasi' => $lokasi
    ]);
}

}


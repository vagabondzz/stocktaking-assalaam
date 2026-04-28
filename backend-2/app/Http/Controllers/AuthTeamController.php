<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Item;
use App\Support\LocationSessionService;
use Carbon\Carbon;
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
        'password' => 'required'
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

    // generate JWT token
    $token = JWTAuth::fromUser($team);

    // format data team
    $teamData = [
        'stock_opname_team_id' => $team->STOCK_OPNAME_TEAM_ID,
        'no_team' => $team->SOPT_NO,
        'penghitung_1' => $team->SOPT_PENGHITUNG,
        'penghitung_2' => $team->SOPT_HELPER,
        'kode_team' => $team->KODE_TEAM
    ];

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'token' => $token,
        'data' => $teamData
    ]);
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


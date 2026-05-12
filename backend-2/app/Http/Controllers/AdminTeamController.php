<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class AdminTeamController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', ''));

        $teams = Team::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($builder) use ($keyword) {
                    $builder
                        ->where('SOPT_NO', 'like', "%{$keyword}%")
                        ->orWhere('KODE_TEAM', 'like', "%{$keyword}%")
                        ->orWhere('SOPT_PENGHITUNG', 'like', "%{$keyword}%")
                        ->orWhere('SOPT_HELPER', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('SOPT_NO')
            ->get()
            ->map(function (Team $team) {
                return [
                    'team_id' => $team->STOCK_OPNAME_TEAM_ID,
                    'no_team' => $team->SOPT_NO,
                    'kode_team' => $team->KODE_TEAM,
                    'penghitung_1' => $team->SOPT_PENGHITUNG,
                    'penghitung_2' => $team->SOPT_HELPER,
                    'year' => $team->YEAR,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Data team berhasil diambil',
            'data' => [
                'teams' => $teams,
            ],
        ]);
    }
}

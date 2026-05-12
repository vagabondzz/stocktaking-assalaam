<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use App\Models\TeamDeviceLimit;
use App\Models\TeamDeviceSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TeamDeviceAccessController extends Controller
{
    public function adminIndex(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', ''));
        $teams = $this->fetchTeamsFromBackend2($keyword);
        $tablesReady = $this->hasDeviceTables();

        if ($tablesReady) {
            $this->cleanupExpiredSessions();
        }

        $teamNos = collect($teams)->pluck('no_team')->filter()->values();

        $limits = $tablesReady
            ? TeamDeviceLimit::query()
                ->when($teamNos->isNotEmpty(), fn ($query) => $query->whereIn('team_no', $teamNos->all()))
                ->get()
                ->keyBy('team_no')
            : collect();

        $sessions = $tablesReady
            ? TeamDeviceSession::query()
                ->active()
                ->when($teamNos->isNotEmpty(), fn ($query) => $query->whereIn('team_no', $teamNos->all()))
                ->orderByDesc('last_seen_at')
                ->get()
                ->groupBy('team_no')
            : collect();

        $data = collect($teams)->map(function (array $team) use ($limits, $sessions) {
            $teamNo = $team['no_team'] ?? null;
            $limit = $teamNo ? $limits->get($teamNo) : null;
            $activeSessions = $teamNo ? $sessions->get($teamNo, collect())->values() : collect();

            return [
                'team_id' => $team['team_id'] ?? null,
                'no_team' => $teamNo,
                'kode_team' => $team['kode_team'] ?? null,
                'penghitung_1' => $team['penghitung_1'] ?? null,
                'penghitung_2' => $team['penghitung_2'] ?? null,
                'year' => $team['year'] ?? null,
                'max_devices' => (int) ($limit?->max_devices ?? 1),
                'active_devices_count' => $activeSessions->count(),
                'active_devices' => $activeSessions->map(function (TeamDeviceSession $session) {
                    return [
                        'device_id' => $session->device_id,
                        'device_name' => $session->device_name ?: 'Perangkat tidak dikenal',
                        'platform' => $session->platform,
                        'ip_address' => $session->ip_address,
                        'last_login_at' => optional($session->last_login_at)->toIso8601String(),
                        'last_seen_at' => optional($session->last_seen_at)->toIso8601String(),
                        'expires_at' => optional($session->expires_at)->toIso8601String(),
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => $tablesReady
                ? 'Data batas device team berhasil diambil'
                : 'Tabel device limit belum tersedia. Jalankan migration backend-1 agar fitur tersimpan penuh.',
            'data' => [
                'teams' => $data,
                'summary' => [
                    'total_teams' => $data->count(),
                    'total_active_devices' => $data->sum('active_devices_count'),
                ],
            ],
        ]);
    }

    public function adminUpdate(Request $request, string $teamNo)
    {
        if (!$this->hasDeviceTables()) {
            return response()->json([
                'success' => false,
                'message' => 'Tabel device limit belum tersedia. Jalankan migration backend-1 terlebih dahulu.',
            ], 503);
        }

        $validated = $request->validate([
            'max_devices' => 'required|integer|min:1|max:100',
            'team_id' => 'nullable|string',
            'kode_team' => 'nullable|string',
        ]);

        $limit = TeamDeviceLimit::query()->updateOrCreate(
            ['team_no' => $teamNo],
            [
                'team_id' => $validated['team_id'] ?? null,
                'kode_team' => $validated['kode_team'] ?? null,
                'max_devices' => $validated['max_devices'],
            ]
        );

        $deactivatedDeviceIds = $this->deactivateExcessSessions(
            $teamNo,
            (int) $limit->max_devices,
        );

        return response()->json([
            'success' => true,
            'message' => $deactivatedDeviceIds
                ? 'Batas device team berhasil diperbarui dan sesi berlebih dinonaktifkan'
                : 'Batas device team berhasil diperbarui',
            'data' => [
                'team_id' => $limit->team_id,
                'no_team' => $limit->team_no,
                'kode_team' => $limit->kode_team,
                'max_devices' => (int) $limit->max_devices,
                'deactivated_device_ids' => $deactivatedDeviceIds,
            ],
        ]);
    }

    public function check(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'nullable|string',
            'team_no' => 'required|string',
            'kode_team' => 'nullable|string',
            'device_id' => 'required|string|max:191',
            'device_name' => 'nullable|string|max:191',
        ]);

        if (!$this->hasDeviceTables()) {
            return response()->json([
                'success' => true,
                'message' => 'Device diizinkan login. Tabel device limit belum tersedia.',
                'data' => [
                    'max_devices' => 1,
                    'storage_ready' => false,
                ],
            ]);
        }

        $this->cleanupExpiredSessions();

        $teamLimit = TeamDeviceLimit::query()->firstOrCreate(
            ['team_no' => $validated['team_no']],
            [
                'team_id' => $validated['team_id'] ?? null,
                'kode_team' => $validated['kode_team'] ?? null,
                'max_devices' => 1,
            ]
        );

        $currentSession = TeamDeviceSession::query()
            ->active()
            ->where('team_no', $validated['team_no'])
            ->where('device_id', $validated['device_id'])
            ->first();

        $activeSessionCount = TeamDeviceSession::query()
            ->active()
            ->where('team_no', $validated['team_no'])
            ->when($currentSession, fn ($query) => $query->where('id', '!=', $currentSession->id))
            ->count();

        if (!$currentSession && $activeSessionCount >= (int) $teamLimit->max_devices) {
            return response()->json([
                'success' => false,
                'message' => "Batas login device untuk team {$validated['kode_team']} sudah penuh",
                'data' => [
                    'max_devices' => (int) $teamLimit->max_devices,
                    'active_devices_count' => $activeSessionCount,
                ],
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Device diizinkan login',
            'data' => [
                'max_devices' => (int) $teamLimit->max_devices,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'nullable|string',
            'team_no' => 'required|string',
            'kode_team' => 'nullable|string',
            'device_id' => 'required|string|max:191',
            'device_name' => 'nullable|string|max:191',
            'platform' => 'nullable|string|max:100',
            'user_agent' => 'nullable|string',
            'ip_address' => 'nullable|string|max:64',
            'token_identifier' => 'nullable|string',
            'expires_at' => 'nullable|date',
        ]);

        if (!$this->hasDeviceTables()) {
            return response()->json([
                'success' => true,
                'message' => 'Sesi device diabaikan sementara karena tabel belum tersedia.',
                'data' => [
                    'storage_ready' => false,
                ],
            ]);
        }

        TeamDeviceLimit::query()->firstOrCreate(
            ['team_no' => $validated['team_no']],
            [
                'team_id' => $validated['team_id'] ?? null,
                'kode_team' => $validated['kode_team'] ?? null,
                'max_devices' => 1,
            ]
        );

        TeamDeviceSession::query()->updateOrCreate(
            [
                'team_no' => $validated['team_no'],
                'device_id' => $validated['device_id'],
            ],
            [
                'team_id' => $validated['team_id'] ?? null,
                'kode_team' => $validated['kode_team'] ?? null,
                'device_name' => $validated['device_name'] ?? null,
                'platform' => $validated['platform'] ?? null,
                'user_agent' => $validated['user_agent'] ?? null,
                'ip_address' => $validated['ip_address'] ?? null,
                'token_identifier' => $validated['token_identifier'] ?? null,
                'last_login_at' => now(),
                'last_seen_at' => now(),
                'expires_at' => !empty($validated['expires_at'])
                    ? Carbon::parse($validated['expires_at'])
                    : null,
                'is_active' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Sesi device team berhasil disimpan',
        ]);
    }

    public function unregister(Request $request)
    {
        $validated = $request->validate([
            'team_no' => 'required|string',
            'device_id' => 'required|string|max:191',
        ]);

        if (!$this->hasDeviceTables()) {
            return response()->json([
                'success' => true,
                'message' => 'Tabel device limit belum tersedia.',
                'data' => [
                    'storage_ready' => false,
                ],
            ]);
        }

        TeamDeviceSession::query()
            ->where('team_no', $validated['team_no'])
            ->where('device_id', $validated['device_id'])
            ->update([
                'is_active' => false,
                'last_seen_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Sesi device team berhasil dinonaktifkan',
        ]);
    }

    public function status(Request $request)
    {
        $validated = $request->validate([
            'team_no' => 'required|string',
            'device_id' => 'required|string|max:191',
        ]);

        if (!$this->hasDeviceTables()) {
            return response()->json([
                'success' => true,
                'message' => 'Status sesi device diizinkan. Tabel belum tersedia.',
                'data' => [
                    'is_active' => true,
                    'storage_ready' => false,
                ],
            ]);
        }

        $this->cleanupExpiredSessions();

        $session = TeamDeviceSession::query()
            ->where('team_no', $validated['team_no'])
            ->where('device_id', $validated['device_id'])
            ->first();

        if (!$session || !$session->is_active || ($session->expires_at && $session->expires_at->isPast())) {
            return response()->json([
                'success' => true,
                'message' => 'Sesi device tidak aktif',
                'data' => [
                    'is_active' => false,
                ],
            ]);
        }

        $session->forceFill([
            'last_seen_at' => now(),
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Sesi device aktif',
            'data' => [
                'is_active' => true,
            ],
        ]);
    }

    protected function cleanupExpiredSessions(): void
    {
        TeamDeviceSession::query()
            ->where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['is_active' => false]);
    }

    protected function deactivateExcessSessions(string $teamNo, int $maxDevices): array
    {
        if ($maxDevices < 1) {
            $maxDevices = 1;
        }

        $activeSessions = TeamDeviceSession::query()
            ->active()
            ->where('team_no', $teamNo)
            ->orderByDesc('last_seen_at')
            ->orderByDesc('last_login_at')
            ->get();

        if ($activeSessions->count() <= $maxDevices) {
            return [];
        }

        $sessionsToDeactivate = $activeSessions->slice($maxDevices)->values();
        $ids = $sessionsToDeactivate->pluck('id')->all();
        $deviceIds = $sessionsToDeactivate->pluck('device_id')->filter()->values()->all();

        TeamDeviceSession::query()
            ->whereIn('id', $ids)
            ->update([
                'is_active' => false,
                'last_seen_at' => now(),
            ]);

        return $deviceIds;
    }

    protected function hasDeviceTables(): bool
    {
        return Schema::hasTable('team_device_limits')
            && Schema::hasTable('team_device_sessions');
    }

    protected function fetchTeamsFromBackend2(string $keyword = ''): array
    {
        $backend2Url = env('BACKEND2_BASE_URL') . '/api/admin/teams';
        $backend2Token = env('BACKEND2_TOKEN');

        try {
            $response = Http::connectTimeout(3)
                ->timeout(8)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $backend2Token,
                    'X-Report-Token' => $backend2Token,
                ])->get($backend2Url, [
                    'keyword' => $keyword ?: null,
                ]);
        } catch (ConnectionException $exception) {
            Log::warning('[TeamDeviceAccessController] Backend-2 timeout', [
                'url' => $backend2Url,
                'keyword' => $keyword,
                'error' => $exception->getMessage(),
            ]);

            abort(503, 'Backend team tidak merespons. Coba lagi beberapa saat.');
        }

        if (!$response->successful()) {
            abort($response->status(), $response->json('message') ?: 'Gagal mengambil data team dari backend-2');
        }

        return $response->json('data.teams', []);
    }
}

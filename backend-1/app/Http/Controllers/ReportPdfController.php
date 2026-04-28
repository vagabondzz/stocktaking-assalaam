<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportPdfController extends Controller
{
    public function generateStocktakingPdf(Request $request)
    {
        try {
            $request->validate([
                'kode_lokasi' => 'required|string',
                'year' => 'nullable|integer|min:2000|max:3000'
            ]);

            $backendBaseUrl = config('services.backend2.base_url');
            if (!$backendBaseUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'BACKEND2_BASE_URL belum diset'
                ], 500);
            }

            $payload = [
                'kode_lokasi' => $request->kode_lokasi,
                'year' => $request->year ?? date('Y')
            ];

            $authHeader = $request->header('Authorization');
            $backendToken = config('services.backend2.token');

            $http = Http::timeout(30);
            if ($authHeader) {
                $http = $http->withHeaders(['Authorization' => $authHeader]);
            } elseif ($backendToken) {
                $http = $http->withToken($backendToken);
            }

            $response = $http->post(rtrim($backendBaseUrl, '/') . '/api/report/stocktaking', $payload);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data dari backend 2',
                    'status' => $response->status(),
                    'error' => $response->json()
                ], $response->status());
            }

            $reportData = $response->json('data');
            $reportData['logo_base64'] = $this->getLogoBase64();

            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.stocktaking', [
                    'report' => $reportData
                ]);
                return $pdf->download('stocktaking.pdf');
            }

            return response()->json([
                'success' => true,
                'message' => 'Data laporan berhasil diambil (PDF belum di-generate karena library PDF belum terpasang)',
                'data' => $reportData
            ]);

        } catch (\Exception $e) {
            Log::error('[generateStocktakingPdf] Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getLogoBase64()
    {
        $candidates = [
            public_path('images/logoassalam.png'),
            public_path('images/logo.png'),
        ];

        $path = null;
        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                $path = $candidate;
                break;
            }
        }

        if (!$path) {
            return null;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = $ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg' : 'image/png';
        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }
}

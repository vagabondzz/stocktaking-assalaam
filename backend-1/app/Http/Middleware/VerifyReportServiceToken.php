<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyReportServiceToken
{
    public function handle(Request $request, Closure $next)
    {
        $expected = config('services.report_service.token');

        if (!$expected) {
            return response()->json([
                'success' => false,
                'message' => 'Report service token belum diset',
            ], 500);
        }

        $token = $request->bearerToken() ?: $request->header('X-Report-Token');

        if (!$token || !hash_equals($expected, $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}

<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\AdminProgressDraftController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\ReportPdfController;
use App\Http\Controllers\TeamDeviceAccessController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => 'api'], function () {
        Route::post('/admin/login', [adminController::class, 'login']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api']], function () {
    Route::post('/register', [adminController::class, 'register']);
    Route::post('/logout/location', [adminController::class, 'logoutLokasi']);
    Route::get('/open-locations', [adminController::class, 'openLocations']);
    Route::get('/location-progress', [AdminProgressDraftController::class, 'index']);
    Route::get('/team-device-limits', [TeamDeviceAccessController::class, 'adminIndex']);
    Route::put('/team-device-limits/{teamNo}', [TeamDeviceAccessController::class, 'adminUpdate']);
    Route::post('/reports/sync', [AdminReportController::class, 'sync']);
    Route::get('/reports', [AdminReportController::class, 'index']);
    Route::get('/reports/{report}/pdf', [AdminReportController::class, 'downloadPdf']);
});

Route::group(['prefix' => 'report', 'middleware' => 'api'], function () {
        Route::post('/pdf', [ReportPdfController::class, 'generateStocktakingPdf']);
});

Route::group(['prefix' => 'report', 'middleware' => ['api', 'report.token']], function () {
        Route::post('/admin-sync', [AdminReportController::class, 'sync']);
        Route::post('/admin-ingest', [AdminReportController::class, 'ingest']);
        Route::post('/progress-draft', [AdminProgressDraftController::class, 'ingest']);
        Route::post('/team-device-access/check', [TeamDeviceAccessController::class, 'check']);
        Route::post('/team-device-access/register', [TeamDeviceAccessController::class, 'register']);
        Route::post('/team-device-access/unregister', [TeamDeviceAccessController::class, 'unregister']);
        Route::post('/team-device-access/status', [TeamDeviceAccessController::class, 'status']);
});

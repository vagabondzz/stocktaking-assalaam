<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\AdminProgressDraftController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\ReportPdfController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => 'api'], function () {
        Route::post('/admin/login', [adminController::class, 'login']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api']], function () {
    Route::post('/register', [adminController::class, 'register']);
    Route::post('/logout/location', [adminController::class, 'logoutLokasi']);
    Route::get('/open-locations', [adminController::class, 'openLocations']);
    Route::get('/location-progress', [AdminProgressDraftController::class, 'index']);
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
});

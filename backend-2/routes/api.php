<?php

use App\Http\Controllers\AuthTeamController;
use App\Http\Controllers\ItemTeamController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LogItemController;
use App\Http\Controllers\EksporPdfController;
use App\Http\Controllers\DraftProgressController;
use App\Http\Controllers\AdminManageController;
use App\Http\Controllers\AdminOpenLocationController;
use App\Http\Controllers\AdminLocationProgressController;
use App\Http\Controllers\AdminTeamController;
use App\Http\Controllers\ReportSnapshotController;
use Illuminate\Support\Facades\Route;

// Route khusus untuk menerima request dari backend-1 (Admin)
Route::group(['prefix' => 'admin', 'middleware' => ['api', 'report.token']], function () {
    Route::post('/logout/location', [AdminManageController::class, 'logoutLokasi']);
    Route::get('/open-locations', [AdminOpenLocationController::class, 'index']);
    Route::get('/location-progress', [AdminLocationProgressController::class, 'index']);
    Route::get('/teams', [AdminTeamController::class, 'index']);
});

Route::group(['prefix' => 'auth', 'middleware' => 'api'], function () {

    Route::post('/login/team', [AuthTeamController::class, 'loginTeam']);
    Route::post('/login/location', [AuthTeamController::class, 'validasiLokasi']);
    Route::post('/logout/location', [AuthTeamController::class, 'logoutLokasi']);
    Route::post('/logout/team', [AuthTeamController::class, 'logoutTeam']);
    Route::post('/session-status', [AuthTeamController::class, 'teamSessionStatus']);
});

Route::group(['prefix' => 'item', 'middleware' => 'api'], function () {

    Route::post('/location/team', [ItemTeamController::class, 'getItemDetailsByLokasi']);
    Route::post('/update/qty', [ItemTeamController::class, 'updateQty']);
    Route::post('/delete', [ItemTeamController::class, 'deleteItemDetail']);
    Route::post('/code', [ItemController::class, 'getBarangByCode']);
    
});

Route::group(['prefix' => 'log', 'middleware' => 'api'], function () {

    Route::post('/update/itemLog', [LogItemController::class, 'updateLog']);
    Route::post('/get/itemLog', [LogItemController::class, 'getPerubahanBarangByLokasi']);
    
});

Route::group(['prefix' => 'report', 'middleware' => ['api', 'report.token']], function () {

    Route::post('/stocktaking', [EksporPdfController::class, 'getStocktakingReportData']);
    Route::post('/stocktaking/snapshots', [ReportSnapshotController::class, 'index']);

});

Route::group(['prefix' => 'report', 'middleware' => 'api'], function () {

    Route::post('/stocktaking/pdf', [EksporPdfController::class, 'exportStocktakingPdf']);
    Route::post('/stocktaking/publish', [EksporPdfController::class, 'publishAdminReportSnapshot']);
    Route::post('/progress/draft', [DraftProgressController::class, 'sync']);

});

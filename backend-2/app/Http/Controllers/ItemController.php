<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\DetailLog;
use App\Models\ItemDetail;
use App\Models\TipeBarang;
use App\Models\SatuanBarang;
use App\Models\Item;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ItemController extends Controller {

    public function getBarangByCode(Request $request)
{
    $request->validate([
        'kode' => 'required|string',
        'kode_lokasi' => 'nullable|string',
    ]);

    $kode = $request->kode;
    $kodeLokasi = $request->kode_lokasi;

    // Cari barang berdasarkan PLU atau Barcode
    $barang = Barang::where('BRG_CODE', $kode)
        ->orWhere('BRG_CATALOG', $kode)
        ->first();

    if (!$barang) {
        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan.',
        ], 404);
    }

    // Ambil TIPE BARANG dari REF$TIPE_BARANG 
    $tipeBarang = TipeBarang::find($barang->{'REF$TIPE_BARANG_ID'});

    $tipeKode = $tipeBarang->TPBRG_CODE ?? '??';
    $tipeNama = $tipeBarang->TPBRG_NAME ?? 'Tidak Dikenal';

    // HANYA tipe 00 dan 06 yang diizinkan
    if (!in_array($tipeKode, ['00', '06'])) {
        return response()->json([
            'success' => false,
            'message' => "Kode PLU {$barang->BRG_CODE}, Barcode {$barang->BRG_CATALOG}, adalah tipe barang {$tipeNama}.",
        ], 403);
    }

    // Ambil UOM dari REF$SATUAN 
    $satuan = SatuanBarang::find($barang->{'REF$SATUAN_STOCK'});

    $uom = $satuan->SAT_CODE ?? 'UNKNOWN';
    $tahun = Carbon::now()->year;
    $formMode = 'create';
    $existingItem = null;

    try {
        $team = JWTAuth::parseToken()->authenticate();

        $itemQuery = Item::where('ISITEAMITEM_NOTEAM', $team->SOPT_NO)
            ->where('ISITEAMITEM_IS_OPEN', 0);

        if ($kodeLokasi) {
            $itemQuery->where('ISITEAMITEM_KODELOKASI', $kodeLokasi);
        }

        $itemLokasi = $itemQuery->first();

        if ($itemLokasi) {
            $itemDetail = ItemDetail::where('ISITEAMITEMDETAIL_ISITEAMITEM_ID', $itemLokasi->ISITEAMITEM_ID)
                ->where('ISITEAMITEMDETAIL_BRG_CODE', $barang->BRG_CODE)
                ->where('YEAR', $tahun)
                ->first();

            if ($itemDetail) {
                $formMode = 'edit';
                $existingItem = [
                    'item_detail_id' => $itemDetail->ISITEAMITEMDETAIL_ID,
                    'item_id' => $itemLokasi->ISITEAMITEM_ID,
                    'kode_lokasi' => $itemLokasi->ISITEAMITEM_KODELOKASI,
                    'kode_plu' => $itemDetail->ISITEAMITEMDETAIL_BRG_CODE,
                    'kode_barcode' => $itemDetail->ISITEAMITEMDETAIL_BRG_BARCODE,
                    'nama_barang' => $itemDetail->ISITEAMITEMDETAIL_BRG_NAME,
                    'qty' => $itemDetail->ISITEAMITEMDETAIL_QTY,
                    'desc' => $itemDetail->ISITEAMITEMDETAIL_DESC,
                    'year' => $itemDetail->YEAR,
                ];
            }
        }
    } catch (\Exception $e) {
        // Tetap kirim data barang jika token/lokasi belum tersedia.
    }

    // Return hasil JSON
    return response()->json([
        'success' => true,
        'data' => [
            'barang_id'   => $barang->BARANG_ID,
            'kode_plu'    => $barang->BRG_CODE,
            'kode_barcode'=> $barang->BRG_CATALOG,
            'nama_barang' => $barang->BRG_NAME,
            'uom'         => $uom,
            'tipe_barang' => $tipeNama,
            'is_decimal'  => (int) $barang->BRG_IS_DECIMAL,
            'form_mode'   => $formMode,
            'existing_item' => $existingItem,
        ],
    ]);
}
}

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

    $tahun = Carbon::now()->year;
    $itemLokasi = null;

    try {
        $team = JWTAuth::parseToken()->authenticate();

        $itemQuery = Item::where('ISITEAMITEM_NOTEAM', $team->SOPT_NO)
            ->where('ISITEAMITEM_IS_OPEN', 0);

        if ($kodeLokasi) {
            $itemQuery->where('ISITEAMITEM_KODELOKASI', $kodeLokasi);
        }

        $itemLokasi = $itemQuery->first();
    } catch (\Exception $e) {
        $itemLokasi = null;
    }

    // Prioritas 1: exact match berdasarkan PLU / barcode
    $barang = Barang::where('BRG_CODE', $kode)
        ->orWhere('BRG_CATALOG', $kode)
        ->first();

    if ($barang) {
        $mappedBarang = $this->mapBarangResult($barang, $itemLokasi, $tahun);

        if (!$mappedBarang['allowed']) {
            return response()->json([
                'success' => false,
                'message' => "Kode PLU {$barang->BRG_CODE}, Barcode {$barang->BRG_CATALOG}, adalah tipe barang {$mappedBarang['tipe_barang']}.",
            ], 403);
        }

        return response()->json([
            'success' => true,
            'search_type' => 'exact',
            'data' => $mappedBarang['data'],
        ]);
    }

    // Prioritas 2: pencarian nama barang / alias / merk
    $barangList = Barang::query()
        ->where(function ($query) use ($kode) {
            $query->where('BRG_NAME', 'like', '%' . $kode . '%')
                ->orWhere('BRG_ALIAS', 'like', '%' . $kode . '%')
                ->orWhere('BRG_MERK', 'like', '%' . $kode . '%');
        })
        ->orderBy('BRG_NAME')
        ->limit(20)
        ->get();

    if ($barangList->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan.',
        ], 404);
    }

    $mappedResults = $barangList
        ->map(fn ($barangItem) => $this->mapBarangResult($barangItem, $itemLokasi, $tahun))
        ->filter(fn ($result) => $result['allowed'])
        ->pluck('data')
        ->values();

    if ($mappedResults->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Barang ditemukan, tetapi semua hasil bukan tipe yang dapat dihitung.',
        ], 403);
    }

    if ($mappedResults->count() === 1) {
        return response()->json([
            'success' => true,
            'search_type' => 'name-single',
            'data' => $mappedResults->first(),
        ]);
    }

    return response()->json([
        'success' => true,
        'search_type' => 'name-multiple',
        'message' => 'Ditemukan beberapa varian barang.',
        'data' => [
            'keyword' => $kode,
            'items' => $mappedResults,
        ],
    ]);
}

    protected function mapBarangResult($barang, $itemLokasi = null, $tahun = null)
    {
        $tipeBarang = TipeBarang::find($barang->{'REF$TIPE_BARANG_ID'});
        $tipeKode = $tipeBarang->TPBRG_CODE ?? '??';
        $tipeNama = $tipeBarang->TPBRG_NAME ?? 'Tidak Dikenal';

        if (!in_array($tipeKode, ['00', '06'])) {
            return [
                'allowed' => false,
                'tipe_barang' => $tipeNama,
                'data' => null,
            ];
        }

        $satuan = SatuanBarang::find($barang->{'REF$SATUAN_STOCK'});
        $uom = $satuan->SAT_CODE ?? 'UNKNOWN';
        $formMode = 'create';
        $existingItem = null;

        if ($itemLokasi && $tahun) {
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

        return [
            'allowed' => true,
            'tipe_barang' => $tipeNama,
            'data' => [
                'barang_id' => $barang->BARANG_ID,
                'kode_plu' => $barang->BRG_CODE,
                'kode_barcode' => $barang->BRG_CATALOG,
                'nama_barang' => $barang->BRG_NAME,
                'uom' => $uom,
                'tipe_barang' => $tipeNama,
                'is_decimal' => (int) $barang->BRG_IS_DECIMAL,
                'form_mode' => $formMode,
                'existing_item' => $existingItem,
            ],
        ];
    }
}

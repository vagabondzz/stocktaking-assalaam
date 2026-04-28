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

class ItemTeamController extends Controller {

   public function getItemDetailsByLokasi(Request $request)
{
    try {

        $request->validate([
            'kode_lokasi' => 'required|string'
        ]);

        $lokasi = $request->kode_lokasi;

        // Ambil team dari JWT
        $team = JWTAuth::parseToken()->authenticate();

        // Validasi lokasi berdasarkan team
        $item = Item::where('ISITEAMITEM_NOTEAM', $team->SOPT_NO)
            ->where('ISITEAMITEM_KODELOKASI', $lokasi)
            ->where('ISITEAMITEM_IS_OPEN', 0)
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak tersedia untuk team ini atau sudah ditutup'
            ], 403);
        }

        $currentYear = date('Y');

        // Ambil detail item + relasi barang
        $details = ItemDetail::where('ISITEAMITEMDETAIL_ISITEAMITEM_ID', $item->ISITEAMITEM_ID)
            ->where('YEAR', $currentYear)
            ->with(['barang:BARANG_ID,BRG_IS_DECIMAL'])
            ->orderBy('ISITEAMITEMDETAIL_SEQ', 'ASC')
            ->get();

        // Ambil semua UOM sekaligus (hindari query di loop)
        $uomIds = $details->pluck('ISITEAMITEMDETAIL_BRG_UOM_ID')->unique();

        $uoms = SatuanBarang::whereIn('REF$SATUAN_ID', $uomIds)
            ->get()
            ->keyBy('REF$SATUAN_ID');

        $mappedDetails = $details->map(function ($detail) use ($uoms) {

            $uom = $uoms->get($detail->ISITEAMITEMDETAIL_BRG_UOM_ID);

            $isDecimal = $detail->barang ? $detail->barang->BRG_IS_DECIMAL : 1;

            $qty = $detail->ISITEAMITEMDETAIL_QTY;

            // Format QTY berdasarkan tipe barang
            if ($isDecimal == 0) {
                $qty = (int) $qty;
            } else {
                $qty = number_format((float)$qty, 3, '.', '');
            }

            return [
                'ISITEAMITEMDETAIL_ID' => $detail->ISITEAMITEMDETAIL_ID,
                'ISITEAMITEMDETAIL_SEQ' => $detail->ISITEAMITEMDETAIL_SEQ,
                'ISITEAMITEMDETAIL_BRG_ID' => $detail->ISITEAMITEMDETAIL_BRG_ID,
                'ISITEAMITEMDETAIL_BRG_CODE' => $detail->ISITEAMITEMDETAIL_BRG_CODE,
                'ISITEAMITEMDETAIL_BRG_BARCODE' => $detail->ISITEAMITEMDETAIL_BRG_BARCODE,
                'ISITEAMITEMDETAIL_BRG_NAME' => $detail->ISITEAMITEMDETAIL_BRG_NAME,
                'ISITEAMITEMDETAIL_QTY' => $qty,
                'ISITEAMITEMDETAIL_DESC' => $detail->ISITEAMITEMDETAIL_DESC,
                'ISITEAMITEMDETAIL_BRG_UOM' => $uom ? $uom->SAT_CODE : null,
                'ISITEAMITEMDETAIL_KONDISI' => $detail->ISITEAMITEMDETAIL_KONDISI,
                'IS_DECIMAL' => $isDecimal,
                'YEAR' => $detail->YEAR,
                'created_at' => $detail->created_at,
                'updated_at' => $detail->updated_at
            ];
        });

        return response()->json([
            'success' => true,
            'team' => [
                'stock_opname_team_id' => $team->STOCK_OPNAME_TEAM_ID,
                'no_team' => $team->SOPT_NO,
                'penghitung_1' => $team->SOPT_PENGHITUNG,
                'penghitung_2' => $team->SOPT_HELPER,
                'kode_team' => $team->KODE_TEAM
            ],
            'lokasi' => $lokasi,
            'item_id' => $item->ISITEAMITEM_ID,
            'year' => $currentYear,
            'data' => $mappedDetails
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function updateQty(Request $request)
{
    try {

        $request->validate([
            'kode_lokasi' => 'required|string',
            'items' => 'required|array',
            'items.*.kode' => 'required|string',
            'items.*.sec' => 'required|integer|min:0',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.desc' => 'nullable|string|max:255'
        ]);

        $kodeLokasi = $request->kode_lokasi;
        $items = $request->items;
        $tahun = Carbon::now()->year;

        // ambil team dari JWT
        $team = JWTAuth::parseToken()->authenticate();

        // cek lokasi berdasarkan team
        $itemLokasi = Item::where('ISITEAMITEM_NOTEAM', $team->SOPT_NO)
            ->where('ISITEAMITEM_KODELOKASI', $kodeLokasi)
            ->where('ISITEAMITEM_IS_OPEN', 0)
            ->first();

        if (!$itemLokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak valid untuk team ini atau sudah ditutup'
            ], 404);
        }

        $responseData = [];

        foreach ($items as $data) {

            $kode = $data['kode'];
            $sec = $data['sec'];
            $qty = $data['qty'];
            $desc = $data['desc'] ?? null;

            // ambil data barang
            $barang = Barang::where('BRG_CODE', $kode)
                ->orWhere('BRG_CATALOG', $kode)
                ->first();

            if (!$barang) {
                continue;
            }

            // validasi tipe barang
            $tipe = TipeBarang::where('REF$TIPE_BARANG_ID', $barang->{'REF$TIPE_BARANG_ID'})
                ->first();

            if (!$tipe || !in_array($tipe->TPBRG_CODE, ['00', '06'])) {
                continue;
            }

            // cek item detail
            $itemDetail = ItemDetail::where('ISITEAMITEMDETAIL_ISITEAMITEM_ID', $itemLokasi->ISITEAMITEM_ID)
                ->where('ISITEAMITEMDETAIL_BRG_CODE', $barang->BRG_CODE)
                ->where('YEAR', $tahun)
                ->first();

            if (!$itemDetail) {

                // buat item detail baru
                $itemDetail = new ItemDetail();
                $itemDetail->ISITEAMITEMDETAIL_ID = Str::uuid();
                $itemDetail->ISITEAMITEMDETAIL_ISITEAMITEM_ID = $itemLokasi->ISITEAMITEM_ID;
                $itemDetail->ISITEAMITEMDETAIL_BRG_ID = $barang->BARANG_ID;
                $itemDetail->ISITEAMITEMDETAIL_BRG_CODE = $barang->BRG_CODE;
                $itemDetail->ISITEAMITEMDETAIL_BRG_NAME = $barang->BRG_NAME;
                $itemDetail->ISITEAMITEMDETAIL_BRG_BARCODE = $barang->BRG_CATALOG;
                $itemDetail->ISITEAMITEMDETAIL_BRG_UOM_ID = $barang->{'REF$SATUAN_STOCK'} ?? null;
                $itemDetail->ISITEAMITEMDETAIL_SEQ = $sec;
                $itemDetail->ISITEAMITEMDETAIL_QTY = $qty;
                $itemDetail->ISITEAMITEMDETAIL_DESC = $desc;
                $itemDetail->YEAR = $tahun;
                $itemDetail->DATE_CREATE = now();

            } else {

                // update qty
                $itemDetail->ISITEAMITEMDETAIL_SEQ = $sec;
                $itemDetail->ISITEAMITEMDETAIL_QTY = $qty;
                $itemDetail->ISITEAMITEMDETAIL_DESC = $desc;
                $itemDetail->DATE_MODIFY = now();

            }

            $itemDetail->save();

            // simpan response
            $responseData[] = [
                'team' => $team->KODE_TEAM,
                'lokasi' => $kodeLokasi,
                'barang' => $barang->BRG_CODE,
                'sec' => $itemDetail->ISITEAMITEMDETAIL_SEQ,
                'desc' => $desc,
                'qty' => $qty,
                'item_detail_id' => $itemDetail->ISITEAMITEMDETAIL_ID
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Qty berhasil diperbarui',
            'data' => $responseData
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function deleteItemDetail(Request $request)
{
    $request->validate([
        'item_detail_id' => 'required|uuid',
        'kode_lokasi' => 'required|string'
    ]);

    $itemDetailId = $request->item_detail_id;
    $kodeLokasi = $request->kode_lokasi;

    try {

        // 🔹 1. Ambil team dari JWT
        $team = JWTAuth::parseToken()->authenticate();

        // 🔹 2. Cari item lokasi milik team
        $item = Item::where('ISITEAMITEM_NOTEAM', $team->SOPT_NO)
            ->where('ISITEAMITEM_KODELOKASI', $kodeLokasi)
            ->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak valid untuk team ini'
            ], 404);
        }

        // 🔹 3. Cari item detail
        $itemDetail = ItemDetail::where('ISITEAMITEMDETAIL_ID', $itemDetailId)
            ->where('ISITEAMITEMDETAIL_ISITEAMITEM_ID', $item->ISITEAMITEM_ID)
            ->first();

        if (!$itemDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Item detail tidak ditemukan di lokasi ini'
            ], 404);
        }

        // 🔹 4. Simpan data sebelum dihapus
        $deletedData = [
            'item_detail_id' => $itemDetail->ISITEAMITEMDETAIL_ID,
            'kode_barang' => $itemDetail->ISITEAMITEMDETAIL_BRG_CODE,
            'nama_barang' => $itemDetail->ISITEAMITEMDETAIL_BRG_NAME,
            'lokasi' => $kodeLokasi
        ];

        // 🔹 5. Hapus item detail
        $itemDetail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item detail berhasil dihapus',
            'data' => [
                'team' => $team->KODE_TEAM,
                'lokasi' => $kodeLokasi,
                'deleted_item' => $deletedData,
                'deleted_at' => now()
            ]
        ]);

    } catch (\Exception $e) {

        Log::error('Error deleting item detail', [
            'item_detail_id' => $itemDetailId,
            'lokasi' => $kodeLokasi,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage()
        ], 500);
    }
}

}

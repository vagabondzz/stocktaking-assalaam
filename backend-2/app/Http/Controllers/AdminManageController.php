<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class AdminManageController extends Controller
{

    public function logoutLokasi(Request $request)
    {
        $request->validate([
            'kode_lokasi' => 'required|string'
        ]);

        $kodeLokasi = $request->kode_lokasi;

        // Cari lokasi berdasarkan kode lokasi
        $lokasi = Item::where('ISITEAMITEM_KODELOKASI', $kodeLokasi)->first();

        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Kode lokasi tidak ditemukan'
            ], 404);
        }

        // Set IS_OPEN = 1 (logout / buka kembali lokasi)
        $lokasi->ISITEAMITEM_IS_OPEN = 1;
        $lokasi->ISITEAMITEM_STATUS = 'CLOSE';
        $lokasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout lokasi',
            'data' => [
                'kode_lokasi' => $lokasi->ISITEAMITEM_KODELOKASI,
                'is_open'     => $lokasi->ISITEAMITEM_IS_OPEN,
                'status'      => $lokasi->ISITEAMITEM_STATUS,
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Pemesanan;
use Illuminate\Http\Request;

class PemesananPetaniController extends Controller
{
    public function index(){
        $pemesanans = Pemesanan::with('penjualan', 'user', 'status')
            ->whereHas('penjualan', function($q){
                $q->where('id_jenis', 2);
            })
            ->get()->groupBy('id_pemesanan');
//        return $pemesanans;
        return view('admin.lihat_pemesanan_pelanggan', compact('pemesanans'));
    }

    public function detail($id_pemesanan){
        $pemesanans = Pemesanan::with('user', 'penjualan', 'status', 'pembayarans')
            ->where('id_pemesanan', $id_pemesanan)
            ->get()
            ->groupBy('id_pemesanan');
        $pelanggan = $pemesanans->first()[0]->user;
        $total = 0;
        foreach ($pemesanans->first() as $pemesanan){
            $total += $pemesanan->total_harga;
        }
//        return $pemesanans;

        return view('admin.lihat_detail_pemesanan_pelanggan', compact('pemesanans', 'pelanggan', 'total'));
    }

    public function updateStatus($id, $status){
        $pemesanans = Pemesanan::where('id_pemesanan', $id)->update([
            'id_status' => $status
        ]);

        return redirect('/admin/pemesanan-pelanggan');
    }
}
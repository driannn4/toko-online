<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Produk;


class BerandaController extends Controller
{
    public function berandaBackend()
    {
        return view('backend.v_beranda.index', [
            'judul' => 'Beranda',
            'sub' => 'Halaman Beranda'
        ]);
    }
    public function index()
    {
        // dd(session()->all()); // <- tambahin di sini

        $produk = Produk::where('status', 1)->orderBy('updated_at', 'desc')->paginate(6);

        // dd(Auth::check(), Auth::user());

        return view('v_beranda.index', [
            'judul' => 'Halaman Beranda',
            'produk' => $produk,
        ]);
    }

    
}

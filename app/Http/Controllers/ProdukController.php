<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\FotoProduk;
use App\Helpers\ImageHelper;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk yang sudah diurutkan berdasarkan tanggal pembaruan.
     * 📋
     */
    public function index()
    {
        $produk = Produk::orderBy('updated_at', 'desc')->get();
        return view('backend.v_produk.index', [
            'judul' => 'Data Produk',
            'index' => $produk
        ]);
    }

    /**
     * Menampilkan form untuk menambah produk baru.
     * ➕
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.v_produk.create', [
            'judul' => 'Tambah Produk',
            'kategori' => $kategori
        ]);
    }

    /**
     * Menyimpan produk yang baru ditambahkan ke dalam database.
     * 💾
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'kategori_id' => 'required',
            'nama_produk' => 'required|max:255|unique:produk',
            'detail' => 'required',
            'harga' => 'required',
            'berat' => 'required',
            'stok' => 'required',
            'foto' => 'required|image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ], [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ]);
        
        // Menambahkan user ID dan status default
        $validatedData['user_id'] = auth()->id();
        $validatedData['status'] = 0;

        // Menangani upload foto
        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';
            // Simpan gambar asli
            $fileName = ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            $validatedData['foto'] = $fileName;

            // Buat thumbnail
            $thumbnailLg = 'thumb_lg_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailLg, 800, null);
            $thumbnailMd = 'thumb_md_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailMd, 500, 519);
            $thumbnailSm = 'thumb_sm_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailSm, 100, 110);
        }

        // Simpan produk
        Produk::create($validatedData);

        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil tersimpan');
    }

    /**
     * Menampilkan detail produk berdasarkan ID.
     * 🔍
     */
    public function show(string $id)
    {
        $produk = Produk::with('fotoProduk')->findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.v_produk.show', [
            'judul' => 'Detail Produk',
            'show' => $produk,
            'kategori' => $kategori
        ]);
    }

    /**
     * Menampilkan form untuk mengedit produk berdasarkan ID.
     * ✏️
     */
    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('backend.v_produk.edit', [
            'judul' => 'Ubah Produk',
            'edit' => $produk,
            'kategori' => $kategori
        ]);
    }

    /**
     * Mengupdate produk yang sudah ada berdasarkan ID.
     * 🔄
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        // Validasi data input
        $validatedData = $request->validate([
            'nama_produk' => 'required|max:255|unique:produk,nama_produk,' . $id,
            'kategori_id' => 'required',
            'status' => 'required',
            'detail' => 'required',
            'harga' => 'required',
            'berat' => 'required',
            'stok' => 'required',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ], [
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.'
        ]);

        // Menambahkan user ID
        $validatedData['user_id'] = auth()->id();

        // Menangani update foto
        if ($request->file('foto')) {
            // Hapus gambar lama
            if ($produk->foto) {
                $oldImagePath = public_path('storage/img-produk/') . $produk->foto;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                // Hapus thumbnails
                $oldThumbnailLg = public_path('storage/img-produk/') . 'thumb_lg_' . $produk->foto;
                $oldThumbnailMd = public_path('storage/img-produk/') . 'thumb_md_' . $produk->foto;
                $oldThumbnailSm = public_path('storage/img-produk/') . 'thumb_sm_' . $produk->foto;
                unlink($oldThumbnailLg);
                unlink($oldThumbnailMd);
                unlink($oldThumbnailSm);
            }

            // Simpan foto baru
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-produk/';
            $fileName = ImageHelper::uploadAndResize($file, $directory, $originalFileName);
            $validatedData['foto'] = $originalFileName;

            // Buat thumbnail baru
            $thumbnailLg = 'thumb_lg_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailLg, 800, null);
            $thumbnailMd = 'thumb_md_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailMd, 500, 519);
            $thumbnailSm = 'thumb_sm_' . $originalFileName;
            ImageHelper::uploadAndResize($file, $directory, $thumbnailSm, 100, 110);
        }

        // Update produk
        $produk->update($validatedData);

        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil diperbaharui');
    }

    /**
     * Menghapus produk dan file terkait dari penyimpanan.
     * 🗑️
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $directory = public_path('storage/img-produk/');
        
        // Hapus gambar dan thumbnail
        if ($produk->foto) {
            $oldImagePath = $directory . $produk->foto;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $thumbnailLg = $directory . 'thumb_lg_' . $produk->foto;
            $thumbnailMd = $directory . 'thumb_md_' . $produk->foto;
            $thumbnailSm = $directory . 'thumb_sm_' . $produk->foto;
            unlink($thumbnailLg);
            unlink($thumbnailMd);
            unlink($thumbnailSm);
        }

        // Hapus foto produk lainnya
        $fotoProduks = FotoProduk::where('produk_id', $id)->get();
        foreach ($fotoProduks as $fotoProduk) {
            $fotoPath = $directory . $fotoProduk->foto;
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
            $fotoProduk->delete();
        }

        // Hapus produk dari database
        $produk->delete();

        return redirect()->route('backend.produk.index')->with('success', 'Data berhasil dihapus');
    }

    /**
     * Menampilkan produk berdasarkan kategori.
     * 🏷️
     */
    public function produkKategori($id)
    {
        $kategori = Kategori::findOrFail($id);

        $produks = Produk::where('kategori_id', $id)
            ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('v_produk.produkkategori', [
            'judul' => 'Produk Kategori: ' . $kategori->nama_kategori,
            'kategori' => $kategori,
            'produks' => $produks,
        ]);
    }
    public function produkAll()
{
    $kategori = \App\Models\Kategori::orderBy('nama_kategori', 'desc')->get();
    $produk = \App\Models\Produk::where('status', 1)
        ->orderBy('updated_at', 'desc')
        ->paginate(6);

    return view('v_produk.index', [
        'judul' => 'Semua Produk',
        'kategori' => $kategori,
        'produk' => $produk,
    ]);
}

}

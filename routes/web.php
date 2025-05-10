<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔁 Redirect halaman utama ke beranda frontend
Route::get('/', fn() => redirect()->route('beranda'));

// 🌐 Beranda Frontend
Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

// 🔐 Backend Routes (Login Admin dibutuhkan)
Route::middleware(['auth'])->prefix('backend')->name('backend.')->group(function () {
    // 📊 Dashboard Backend
    Route::get('/beranda', [BerandaController::class, 'berandaBackend'])->name('beranda');

    // 🧑‍💼 Manajemen User
    Route::resource('user', UserController::class);

    // 🗂️ Manajemen Kategori
    Route::resource('kategori', KategoriController::class);

    // 🛒 Manajemen Produk
    Route::resource('produk', ProdukController::class);

    // 📷 Foto Produk
    Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])->name('foto_produk.store');
    Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('foto_produk.destroy');

    // 🧾 Laporan Produk
    Route::get('laporan/formproduk', [ProdukController::class, 'formProduk'])->name('laporan.formproduk');
    Route::post('laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])->name('laporan.cetakproduk');

    // 🧾 Laporan User
    Route::get('laporan/formuser', [UserController::class, 'formUser'])->name('laporan.formuser');
    Route::post('laporan/cetakuser', [UserController::class, 'cetakUser'])->name('laporan.cetakuser');

    // 👥 Manajemen Customer
    Route::resource('customer', CustomerController::class);
});

// 🔑 Login Admin
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login.authenticate');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

// 🔐 Login via Google (Customer)
// API Google
Route::get('/auth/redirect', [CustomerController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/google/callback', [CustomerController::class, 'callback'])->name('auth.callback');
// Logout
Route::post('/logout', [CustomerController::class, 'logout'])->name('logout');

// 🚪 Logout Customer
Route::post('/logout-customer', [CustomerController::class, 'logout'])->name('customer.logout');

// 🛍️ Produk Frontend
Route::middleware(['web'])->group(function () {
    Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])->name('produk.detail');
    Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])->name('produk.kategori');
    Route::get('/produk/all', [ProdukController::class, 'produkAll'])->name('produk.all');
});

// Group route untuk customer
Route::middleware('is.customer')->group(function () {
    // 👤 Menampilkan halaman akun customer
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])->name('customer.akun');

    // ✏️ Mengupdate data akun customer
    Route::put('/customer/updateakun/{id}', [CustomerController::class, 'updateAkun'])->name('customer.updateakun');

    // 🛒 Menambahkan produk ke keranjang
    Route::post('add-to-cart/{id}', [OrderController::class, 'addToCart'])->name('order.addToCart');

    // 🧺 Menampilkan isi keranjang belanja
    Route::get('cart', [OrderController::class, 'viewCart'])->name('order.cart');
});

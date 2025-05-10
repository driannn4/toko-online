<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function addToCart($id)
    {
        // Mengambil data customer berdasarkan user_id yang terautentikasi
        $customer = Customer::where('user_id', Auth::id())->first();

        // Mencari produk berdasarkan id
        $produk = Produk::findOrFail($id);

        // Membuat atau mendapatkan order yang statusnya pending
        $order = Order::firstOrCreate(
            ['customer_id' => $customer->id, 'status' => 'pending'],
            ['total_harga' => 0]
        );

        // Menambahkan item produk ke dalam order
        $orderItem = OrderItem::firstOrCreate(
            ['order_id' => $order->id, 'produk_id' => $produk->id],
            ['quantity' => 1, 'harga' => $produk->harga]
        );

        // Jika order item sudah ada, tambah jumlahnya
        if (!$orderItem->wasRecentlyCreated) {
            $orderItem->quantity++;
            $orderItem->save();
        }

        // Menambahkan harga produk ke total harga order
        $order->total_harga += $produk->harga;
        $order->save();

        // Mengarahkan ke halaman cart dengan pesan sukses
        return redirect()->route('order.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function viewCart()
    {
        // Mengambil data customer berdasarkan user_id yang terautentikasi
        $customer = Customer::where('user_id', Auth::id())->first();

        // Mencari order dengan status pending atau paid
        $order = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'paid'])
            ->first();

        // Jika order ada, load relasi orderItems dan produk
        if ($order) {
            $order->load('orderItems.produk');
        }

        // Mengembalikan view dengan data order
        return view('v_order.cart', compact('order'));
    }
}

<?php

namespace App\Http\Controllers;

// import model product
use App\Models\Product;

// import return type view
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index() : View{

        //get all products
        // untuk mendapatkan semua produk
        // membuat variabel products diisi dengan model product
        // method latest untuk mengambil data dan diurutkan berdasarkan id yang terbaru
        // paginate(10) membatasi setiap halaman ditampilkan hanya 10 data
        $products = Product::latest()->paginate(10);

        // render view with products
        // products.index nanti akan berada di folder view
        // methode compact untuk mengirim data ke folder view
        return view('products.index', compact('products'));
    }
}

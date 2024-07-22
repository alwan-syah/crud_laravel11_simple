<?php

namespace App\Http\Controllers;

// import model product
use App\Models\Product;

// import return type view
use Illuminate\View\View;

// import return type redirect Response
use Illuminate\Http\RedirectResponse;

// import Http Request
// digunakan untuk menerima request
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): View
    {

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

    public function create(): View
    {
        return view('products.create');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //validate form
        // untuk validasi
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        //upload image
        $image = $request->file('image');
        // gambar akan disimpan di folder storage -> public ->products,
        // -hashname artinya nama gambar akan di random
        $image->storeAs('public/products', $image->hashName());

        //create product
        // proses memasukan data
        Product::create([
            'image'         => $image->hashName(),
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock
        ]);

        //redirect to index
        // jika berhasil akan redirect ke route product.index
        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
}

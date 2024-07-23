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

// import Facades Storage
// digunakan untuk menghapus file gambar didalam project laravel
// ketika mengubah gambar nya
use Illuminate\Support\Facades\Storage;

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
        // $image->storeAs('public/products', $image->hashName());

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

    public function show(string $id): View
    {
        // get product by ID
        // membuat variable product, memanggil model product, memanggil method findorfail
        // findorfail jika menemukan akan tampil, jika tidak ditemukan maka akan menampilkan 404
        // parameter id
        $product = Product::findOrFail($id);

        // render view with product 
        // jika data berhasil didapatkan maka kirim ke folder products dgn nama file show
        return view('products.show', compact('product'));
    }

    public function edit(string $id): View
    {

        // get product by ID
        $product = Product::findOrFail($id);

        // render view with product
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        // validate form
        // validasi saat edit data
        // image tidak wajib diubah
        $request->validate([
            'image' => 'image|mimes:jpeg,jpg,png|max:2048',
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        // get product by ID
        // mencari data product berdasarkan id
        $product = Product::findOrFail($id);

        // check if image is uploaded
        // jika ada request berupa image artinya jika ada gambar yg diubah 
        // atau gambar baru
        if ($request->hasFile('image')) {

            // upload new image
            // maka upload gambar baru
            $image = $request->file('image');
            // gambar tersebut akan disimpan dalam folder product
            $image->storeAs('public/products/', $image->hashName());

            // delete old image
            // menghapus gambar yg lama
            Storage::delete('public/products/' . $product->image);

            // update product with new image
            $product->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
            ]);
        } else {

            // update product without image
            $product->update([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock
            ]);
        }

        // redirect to index
        return redirect()->route('products.index')->with(['succes' => 'Data Berhasil diubah']);
    }

    public function destroy($id): RedirectResponse
    {
        // get product by ID
        $product = Product::findOrFail($id);

        // delete image
        Storage::delete('public/products/' . $product->image);

        // delete product
        $product->delete();

        // redirect to index
        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}

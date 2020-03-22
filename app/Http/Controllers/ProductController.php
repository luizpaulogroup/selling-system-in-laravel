<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;

use App\Models\Product;

class ProductController extends Controller
{

    private $objProduct;

    public function __construct(){
        
        $this->objProduct = new product();

    }

    public function index()
    {

        if(!session()->has('admin')){
            return redirect('auth');
        }
        
        $title = "Produto - LISTA";

        $products = $this->objProduct->paginate(10);
        
        $session = session()->get('admin');

        return view('product.index', array(
            'title' => $title,
            'products' => $products,
            'session' => $session
        ));
    }

    public function search(ProductRequest $request)
    {

        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Produto - PESQUISA";

        $s = $request->get('search');

        $products = $this->objProduct->where('name', 'like', '%' . $s . '%')->paginate(10);

        return view('product.index', array(
            'title' => $title,
            'products' => $products,
        ));
    }

    public function create()
    {
        
        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Produto - CADASTRO";

        return view('product.create', array(
            'title' => $title,
        ));
    }

    public function store(ProductRequest $request)
    {

        $request->validate([
            'name' => 'required|min:3|max:255',
            'value' => 'required',
            'status' => 'required',
        ]);

        $value = $this->money($request->value);

        $this->objProduct->create([
            'name' => $request->name,
            'value' => $value,
            'status' => $request->status,             
        ]);

        return redirect('product');

    }

    public function product($id)
    {
        
        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Produto - DETALHES";

        $product = $this->objProduct->find($id);

        if(!$product) {
            return redirect('product');
        }

        return view('product.product', array(
            'title' => $title,
            'product' => $product,
        ));            
    }

    public function update(ProductRequest $request, $id)
    {
       
        $request->validate([
            'name' => 'required|min:3|max:255',
            'value' => 'required',
            'status' => 'required',
        ]);

        $value = $this->money($request->value);

        $this->objProduct->where(array('id' => $id))->update(array(
            'name' => $request->name,
            'value' => $value,
            'status' => $request->status,
        ));

        return redirect('product');
    }

    public function destroy($id)
    {
        $del = $this->objProduct->destroy($id);

        return response()->json(array(
            'fail' => $del ? false : true,
            'url' => url('product')
        ));
    }

    protected function money($value)
    {

        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return $value;
    }

}
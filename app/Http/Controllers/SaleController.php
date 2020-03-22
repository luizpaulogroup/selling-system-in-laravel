<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;

class SaleController extends Controller
{

    private $objSale;

    public function __construct(){
        
        $this->objSale = new Sale();
        $this->objClient = new Client();
        $this->objProduct = new Product();

    }

    public function index()
    {

        if(!session()->has('admin')){
            return redirect('auth');
        }
        
        $title = "Venda - LISTA";

        $sales =  $this->objSale
            ->join('client', 'client.id', '=', 'sale.client_id')
            ->join('product', 'product.id', '=', 'sale.product_id')
            ->select('sale.*', 'client.name AS client_name', 'product.name AS product_name')
            ->paginate(10);

        $session = session()->get('admin');

        return view('sale.index', array(
            'title' => $title,
            'sales' => $sales,
            'session' => $session
        ));
    }

    public function search(SaleRequest $request)
    {

        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Venda - PESQUISA";

        $s = $request->get('search');

        $sales =  $this->objSale
            ->join('client', 'client.id', '=', 'sale.client_id')
            ->join('product', 'product.id', '=', 'sale.product_id')
            ->select('sale.*', 'client.name AS client_name', 'product.name AS product_name')
            ->where('client.name', 'like', '%' . $s . '%')
            ->paginate(10);

        return view('sale.index', array(
            'title' => $title,
            'sales' => $sales,
        ));
    }

    public function create()
    {
        
        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Venda - CADASTRO";

        $clients = $this->objClient->where('status', 'A')->get();
        
        $products = $this->objProduct->where('status', 'A')->get();

        return view('sale.create', array(
            'title' => $title,
            'clients' => $clients,
            'products' => $products,
        ));
    }

    public function store(SaleRequest $request)
    {

        $request->validate([
            'client_id' => 'required',
            'product_id' => 'required',
            'status' => 'required',
        ]);

        $product = $this->objProduct->where('id', $request->product_id)->first();

        if(!$product){
            return redirect('sale/create');
        }

        $this->objSale->create([
            'client_id' => $request->client_id,
            'product_id' => $request->product_id,
            'value' => $product->value,
            'status' => $request->status,             
        ]);

        return redirect('sale');

    }

    public function sale($id)
    {
        
        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Venda - DETALHES";

        $sale =  $this->objSale
            ->join('client', 'client.id', '=', 'sale.client_id')
            ->join('product', 'product.id', '=', 'sale.product_id')
            ->select('sale.*', 'client.name AS client_name', 'product.name AS product_name')
            ->where('sale.id', $id)
            ->first();

        if(!$sale) {
            return redirect('sale');
        }

        return view('sale.sale', array(
            'title' => $title,
            'sale' => $sale,
        ));            
    }

    public function update(SaleRequest $request, $id)
    {
       
        $request->validate([
            'status' => 'required',
        ]);

        $this->objSale->where(array('id' => $id))->update(array(
            'status' => $request->status,
        ));

        return redirect('sale');
    }

    public function destroy($id)
    {
        $del = $this->objSale->destroy($id);

        return response()->json(array(
            'fail' => $del ? false : true,
            'url' => url('sale')
        ));
    }

}
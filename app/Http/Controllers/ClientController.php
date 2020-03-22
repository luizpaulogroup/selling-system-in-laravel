<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;

use App\Models\Client;

class ClientController extends Controller
{

    private $objClient;

    public function __construct(){
        
        $this->objClient = new Client();

    }

    public function index()
    {

        if(!session()->has('admin')){
            return redirect('auth');
        }
        
        $title = "Cliente - LISTA";

        $clients = $this->objClient->paginate(10);
        
        $session = session()->get('admin');

        return view('client.index', array(
            'title' => $title,
            'clients' => $clients,
            'session' => $session
        ));
    }

    public function search(ClientRequest $request)
    {

        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Cliente - PESQUISA";

        $s = $request->get('search');

        $clients = $this->objClient->where('name', 'like', '%' . $s . '%')->paginate(10);

        return view('client.index', array(
            'title' => $title,
            'clients' => $clients,
        ));
    }

    public function create()
    {
        
        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Cliente - CADASTRO";

        return view('client.create', array(
            'title' => $title,
        ));
    }

    public function store(ClientRequest $request)
    {

        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:client',
            'status' => 'required',
        ]);

        $this->objClient->create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,             
        ]);

        return redirect('client');

    }

    public function client($id)
    {
        
        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Cliente - DETALHES";

        $client = $this->objClient->find($id);

        if(!$client) {
            return redirect('client');
        }

        return view('client.client', array(
            'title' => $title,
            'client' => $client,
        ));            
    }

    public function update(ClientRequest $request, $id)
    {
       
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'status' => 'required',
        ]);

        $this->objClient->where(array('id' => $id))->update(array(
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ));

        return redirect('client');
    }

    public function destroy($id)
    {
        $del = $this->objClient->destroy($id);

        return response()->json(array(
            'fail' => $del ? false : true,
            'url' => url('client')
        ));
    }

    public function logout()
    {
        session()->forget('admin');
        return redirect('auth');
    }

}

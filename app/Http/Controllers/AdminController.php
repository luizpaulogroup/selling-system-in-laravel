<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;

use App\Models\Admin;

class AdminController extends Controller
{

    private $objAdmin;

    public function __construct(){
        
        $this->objAdmin = new Admin();

    }

    public function index()
    {

        if(!session()->has('admin')){
            return redirect('auth');
        }
        
        $title = "Admin - LISTA";

        $admins = $this->objAdmin->paginate(10);
        
        $session = session()->get('admin');

        return view('admin.index', array(
            'title' => $title,
            'admins' => $admins,
            'session' => $session
        ));
    }

    public function search(AdminRequest $request)
    {

        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Admin - PESQUISA";

        $s = $request->get('search');

        $admins = $this->objAdmin->where('name', 'like', '%' . $s . '%')->paginate(10);

        return view('admin.index', array(
            'title' => $title,
            'admins' => $admins,
        ));
    }

    public function create()
    {
        
        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Admin - CADASTRO";

        return view('admin.create', array(
            'title' => $title,
        ));
    }

    public function store(AdminRequest $request)
    {

        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:admin',
            'password' => 'required|min:6',
            'status' => 'required',
        ]);

        $this->objAdmin->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'status' => $request->status,             
        ]);

        return redirect('admin');

    }

    public function admin($id)
    {
        
        if(!session()->has('admin')){
            return redirect('auth');
        }

        $title = "Admin - DETALHES";

        $admin = $this->objAdmin->find($id);

        if(!$admin) {
            return redirect('admin');
        }

        return view('admin.admin', array(
            'title' => $title,
            'admin' => $admin,
        ));            
    }

    public function update(AdminRequest $request, $id)
    {
       
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'status' => 'required',
        ]);

        $this->objAdmin->where(array('id' => $id))->update(array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'status' => $request->status,
        ));

        return redirect('admin');
    }

    public function destroy($id)
    {
        $del = $this->objAdmin->destroy($id);

        return response()->json(array(
            'fail' => $del ? false : true,
            'url' => url('admin')
        ));
    }

}
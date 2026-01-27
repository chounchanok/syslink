<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class saleController extends Controller
{
    public function index(Request $request){
        if($request->search != null){
            $sale=User::where('role','sale')->where(function ($query) use ($request) {
                $query->where('username', 'like', '%' . $request->search . '%')
                      ->orWhere('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }else{
            $sale=User::where('role','sale')->orderBy('created_at', 'desc')->paginate(10);
        }
        // $sale=User::where('role','sale')->orderBy('created_at', 'desc')->paginate(10);
        return view('backoffice.sale.index',[
            'sale'=>$sale,
            'search'=>$request->search,

        ]);
    }
    public function add(){
        return view('backoffice.sale.add');
    }
    public function addsub(Request $request){
        $validator=Validator::make($request->all(),[
            "name"=>'required|string',
            "username"=>'required|string',
            "password"=>'required|string',
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user=new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password =password_hash($request->password, PASSWORD_DEFAULT);
            $user->team_id = $request->team_id;
            $user->role = "sale";
            $user->save();
            return redirect("/sale")->with('success',1);
        }
    }
    public function edit($id){
        $sale=User::find($id);
        return view('backoffice.sale.edit',[
            'sale'=>$sale
        ]);
    }
    public function editsub(Request $request){
        $validator=validator::make($request->all(),[
            "name"=>"required|string",
            "username"=>"required|string",
            // "password"=>"|string",
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user=User::find($request->id);
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password =password_hash($request->password, PASSWORD_DEFAULT);
            $user->team_id = $request->team_id;
            $user->save();
            return redirect("/sale")->with('success',1);
        }
    }
    public function delete(Request $request){
        $sale=User::destroy($request->id);
        return redirect('/sale')->with('success',1);
    }
}

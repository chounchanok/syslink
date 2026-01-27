<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class InspecController extends Controller
{
    public function index(Request $request){
        if($request->search != null){
            $engineer = User::where('role', 'engineer')
            ->where(function ($query) use ($request) {
                $query->where('username', 'like', '%' . $request->search . '%')
                      ->orWhere('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }else{
            $engineer=User::where('role','engineer')->orderBy('created_at', 'desc')->paginate(10);
        }
        // $engineer=User::where('role','engineer')->orderBy('created_at', 'desc')->paginate(10);
        return view('backoffice.engineer.index',[
            'engineer'=>$engineer,
            'search'=>$request->search,
        ]);
    }
    public function add(){
        return view('backoffice.engineer.add');
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
            $user->role = "engineer";
            $user->save();
            return redirect("/engineer")->with('success',1);
        }
    }
    public function edit($id){
        $engineer=User::find($id);
        return view('backoffice.engineer.edit',[
            'engineer'=>$engineer
        ]);
    }
    public function editsub(Request $request){
        $validator=validator::make($request->all(),[
            "name"=>"required|string",
            "username"=>"required|string",
        ]);
        if($validator->fails()){
            // dd('a');
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            // dd('b');
            $user=User::find($request->id);
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password =password_hash($request->password, PASSWORD_DEFAULT);
            $user->team_id = $request->team_id;
            $user->save();
            return redirect("/engineer")->with('success',1);
        }
    }
    public function delete(Request $request){
        $engineer=User::destroy($request->id);
        return redirect('/engineer')->with('success',1);
    }
}

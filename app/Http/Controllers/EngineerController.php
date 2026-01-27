<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Validator;

class EngineerController extends Controller
{
    public function index(Request $request){
        if($request->search != null){
            $engineer=User::where('role','technician')->where(function ($query) use ($request) {
                $query->where('username', 'like', '%' . $request->search . '%')
                      ->orWhere('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }else{
            $engineer=User::where('role','technician')->orderBy('created_at', 'desc')->paginate(10);
        }
        return view('backoffice.user.index',[
            'engineer'=>$engineer,
            'search'=>$request->search,
        ]);
    }
    public function add(){
        return view('backoffice.user.add');
    }
    public function addsub(Request $request){
        $validator=Validator::make($request->all(),[
            "name"=>'required|string',
            "username"=>'required|string|unique:users,username',
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
            $user->role = "technician";
            $user->save();
            return redirect("/technician")->with('success',1);
        }
    }
    public function edit($id){
        $engineer=User::find($id);
        return view('backoffice.user.edit',[
            'engineer'=>$engineer
        ]);
    }
    public function editsub(Request $request){
            // $check=User::where('id',$request->id)->first();
            // dd($request->all());
            // if(auth()->guard('admin')->user()->role == 'admin'){
                // $validator=validator::make($request->all(),[
                //     // "name"=>"string",
                //     "username"=>'=required|string|unique:users,username',
                //     // "password"=>"required|string",
                // ]);
                // if($validator->fails()){
                //     // dd('a');
                //     return redirect()->back()->withErrors($validator)->withInput();
                // }
                //     dd('b');
                    $user=User::find($request->id);
                    $user->name = $request->name;
                    $user->username = $request->username;
                    if($request->password != null){
                        $user->password =password_hash($request->password, PASSWORD_DEFAULT);
                    }
                    $user->team_id = $request->team_id;
                    $user->save();
                    return redirect("/technician")->with('success',1);
                // }
            // }else{
                // dd('c');
                // $user=User::find($request->id);
                // $user->team_id = $request->team_id;
                // $user->save();
                // return redirect("/technician")->with('success',1);
            // }
        // if($check->username == $request->username){

    // }

    }
    public function delete_engineer(Request $request){
        $engineer=User::destroy($request->id);
        return redirect('/technician')->with('success',1);
    }
}

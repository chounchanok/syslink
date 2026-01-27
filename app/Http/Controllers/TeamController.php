<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;

class TeamController extends Controller
{
    public function index(Request $request){

        $team=Team::orderBy('created_at', 'desc')->where('team_name','LIKE','%'.$request->search.'%')->get();
        return view('backoffice.team.index',[
            'team'=>$team
        ]);
    }
    public function list($id){
        $team=User::where('team_id',$id)->get();
        return view('backoffice.team.list',[
            'team'=>$team
        ]);
    }
    public function add(){
        return view('backoffice.team.add');
    }
    public function addsub(Request $request){
        $team= new Team();
        $team->team_name = $request->team_name;
        $team->save();
        return redirect('/team');
    }
    public function edit($id){
        $team=Team::find($id);
        return view('backoffice.team.edit',[
            'team'=>$team
        ]);
    }
    public function editsub(Request $request){
        $team= Team::find($request->id);
        // dd($team->team_name);
        $team->team_name = $request->team_name;
        $team->save();
        return redirect('/team');
    }
    public function delete(Request $request){
        $team=Team::destroy($request->id);
        return redirect('/team')->with('success',1);
    }
    public function delete_user(Request $request){
        $user = User::where('id',$request->id)->first();
        // dd($user);
        $user->team_id= null;
        $user->save();
        return redirect('/team')->with('success',1);
    }
    public function search(Request $request){
        $team=Team::where('team_name','like','%'.$request->search.'%')->get();
        return view('backoffice.team.index',[
            'team'=>$team
        ]);
    }
}

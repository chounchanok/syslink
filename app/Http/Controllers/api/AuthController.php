<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Team;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password')))
        {

            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('username', $request['username'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer','data'=>$user ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'You have successfully logged out and the token was successfully deleted']);
    }

    public function profile(){
        $user = auth()->user();
        $user_show = User::where('id',$user->id)->select('username','name','role')->first();
        if($user->team_id != null){
            $team_name = Team::where('id',$user->team_id)->select('team_name')->first();
            $in_team = User::where('team_id',$user->team_id)->select('name')->get();
        }else{
            $team_name = $user->role;
            $in_team = $user->role;
        }
        return response()->json([
            'user'=>$user_show,
            'team_name'=>$team_name,
            'in_team'=>$in_team
        ]);
    }

}

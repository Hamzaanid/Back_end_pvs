<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\users;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Namshi\JOSE\JWT;

class AuthentController extends Controller
{

    protected function respondWithToken($token,$user)
    {
        $token_status=511;
        if($token){
            $token_status=200;
        }
        return response()->json([
            'access_token' => $token,
            'name' => $user->nom,
            'role' =>$user->role,
           //
        ],$token_status);

    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // get parametrs 'email' and 'password'
        $usr=$request->only('email', 'password');

        //then get id of users
        try{
        $id = users::select('id')
                  ->where('email','=',$usr['email'])
                  ->where('password','=',$usr['password'])
                  ->where('active','=',true)
                  ->get();
        }catch(Exception $e){
            return response()->json(['error' => 'sql'], 507);
        }

        //then get object of users whose id is $d
        $credentials = users::with('Role:id,nom')->find($id)->first();

        //then try to get token for users
        try {

            //$token = JWTAuth::fromUser($credentials);//other methode
            $token = auth('api')->login($credentials);
            //$useer = JWTAuth::authenticate($token);//to get user

        }catch (JWTException $e) {
            return response()->json(['error' => 'error'], 511);
        }

        //return token avec status
        return $this->respondWithToken($token,$credentials);//response()->json(compact('token'), 200);
    }

    public function logout(Request $request){

        $token = $request->header('token_auth');
        if($token){
            auth()->logout(true);
            JWTAuth::setToken($token)->invalidate();
            return 'succes';
        }else{
            return 'failed';
        }
    }

    public function profile(Request $request){

        if($userlog = $request->user){
        return response()->json(['user' => $userlog],200);
        }else{
            response()->json(['user','error'],400);
        }
 }
}

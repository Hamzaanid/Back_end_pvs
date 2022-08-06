<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Namshi\JOSE\JWT;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\UsersControllers\RoleController;
use App\Models\Role;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {

           $user = JWTAuth::parseToken()->authenticate();
           $user->idRole = Role::select('nom')
                                ->where('id','=',$user->idRole)
                                ->get()->first();
           $request->user = $user;
            return $next($request);
            
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error' => 'error'],511);//token invalider
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error' => 'error'],511);//token expired
            }else{
                return response()->json(['status' => 'Authorization Token not found'],401);
            }
        }



        /*if($token = $request->header('token_auth')){
            //$user = JWTAuth::toUser($token);
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
            return $next($request);
        }else{
            return 'error';
        }*/
    }
}

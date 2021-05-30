<?php

namespace App\Http\Middleware;

use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $headers = getallheaders();
        if(isset($headers['token']) && $headers['token'] != '')
        {
            $check = Member::where('auth_token',$headers['token'])->first();
            if(!isset($check->id))
            {
                return response()->json(['status'=>'fail','data'=>array(),'message'=>'token mis matched']);
            }else{
                //$this->userId = $check->id;
                auth()->id = $check->id;
            }
        }else{
            return response()->json(['status'=>'fail','data'=>array(),'message'=>'token blanked']);
        }

        return $next($request);
    }
}

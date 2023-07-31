<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try{
            $user = $request->user_email;
            $token = $request->bearerToken();
            $response = json_decode(VerifyToken::checkSessionToken($user, $token));
            return $next($request);
        }
        catch(Exception $e){
            // var_dump("test");
        }
    }

    // check token sso + session
    public static function checkSessionToken($user, $token)
    {
        $url = getenv('SSO_BACKEND_URL') . "/verifyTokenHeader";
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->post($url,
        [
            "USER" =>$user,
        ]);

        return $response;
    }
}

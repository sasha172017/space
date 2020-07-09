<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckToken
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
        $user = User::where('token', $request->bearerToken())->first();
        if($user && (new \DateTime($user->expired_at))->modify('+1 day')->getTimestamp() > (new \DateTime())->getTimestamp()){
            return $next($request);
        }
        return response('Unauthorized', 401);
    }
}

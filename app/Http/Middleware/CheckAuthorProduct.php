<?php

namespace App\Http\Middleware;

use App\Models\Product;
use App\Models\User;
use Closure;

class CheckAuthorProduct
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
        $product = Product::findOrFail($request->id);
        if(($product->user_id == $user->id) || $user->roles->contains('name', 'ADMIN')){
            return $next($request);
        }
        return response('You arent author this product', 401);
    }
}

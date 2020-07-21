<?php

namespace App\Http\Middleware;

use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Closure;

class CheckAuthorImage
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
        $image = Image::findOrFail($request->id);
        if(($image->product->id == $user->id) || $user->roles->contains('name', 'ADMIN')){
            return $next($request);
        }
        return response('You arent author this product', 401);
    }
}

<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class entr_vendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard='entr_vendr')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('admin_index');
        }

        return $next($request);
    }
}

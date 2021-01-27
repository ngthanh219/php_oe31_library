<?php

namespace App\Http\Middleware;

use App\Models\Request;
use App\Models\User;
use Auth;
use Closure;

class Admin
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
        if (Auth::user()->role_id == config('role.client')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}

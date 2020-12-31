<?php

namespace App\Http\Middleware;

use Closure;
use Lang;
use Session;

class Localization
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
        $language = Session::get('language');
        Lang::setLocale($language);

        return $next($request);
    }
}

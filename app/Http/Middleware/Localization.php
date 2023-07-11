<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Localization
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
		if (Auth::user() !== null) {
			App::setLocale(Auth::user()->locale);
		} elseif($request->lang !== null) {
			App::setLocale($request->lang);
		} else {
            App::setLocale(env('DEFAULT_LOCALE'));
        }

		return $next($request);
	}
}

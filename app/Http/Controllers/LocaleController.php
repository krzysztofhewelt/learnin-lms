<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
	public function setLocale(Request $request)
	{
		$locale = $request->userLocale;

		if ($locale !== 'pl' && $locale !== 'en') {
			$locale = env('LOCALE_DEFAULT');
		}

		App::setLocale($locale);

		if (Auth::user() != null) {
			Auth::user()->locale = $locale;
			Auth::user()->save();
		}
	}
}

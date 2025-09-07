<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetAppLocale
{
    public function handle(Request $request, Closure $next)
    {
        // إمكانية تمرير ?lang=ar أو ?lang=en للحالات الخاصة
        $lang = $request->get('lang');
        if ($lang && in_array($lang, ['ar','en'])) {
            session(['app_locale' => $lang]);
        }

        $locale = session('app_locale', config('app.locale'));
        App::setLocale($locale);

        return $next($request);
    }
}

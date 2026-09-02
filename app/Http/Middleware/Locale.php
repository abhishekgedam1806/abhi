<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Language;

class Locale
{
    /**
     * Handle an incoming request with dynamic query (?lang=xx) or session locale
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // 1. Query Parameter Detection (?lang=hi, ?locale=fr)
        if ($request->filled('lang') || $request->filled('locale')) {
            $code = strtolower(trim($request->get('lang') ?: $request->get('locale')));
            $lang = Language::where('iso_code', $code)->where('is_active', 1)->first();
            if ($lang) {
                App::setLocale($lang->iso_code);
                session(['locale' => $lang->iso_code]);
                session(['localeDir' => ((bool)$lang->is_rtl) ? 'rtl' : 'ltr']);
                return $next($request);
            }
        }

        // 2. Session Locale
        if (Session::has('locale')) {
            $sessionCode = Session::get('locale');
            App::setLocale($sessionCode);
            return $next($request);
        }

        // 3. Default Database Language
        $defaultLang = Language::where('is_default', 1)->first();
        if ($defaultLang) {
            App::setLocale($defaultLang->iso_code);
            session(['locale' => $defaultLang->iso_code]);
            session(['localeDir' => ((bool)$defaultLang->is_rtl) ? 'rtl' : 'ltr']);
        }

        return $next($request);
    }
}

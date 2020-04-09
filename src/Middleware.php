<?php

namespace Sungmee\Admini;

use Config;
use Closure;

class Middleware
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
        $default= Config::get('app.locale');
        $conf   = Config::get('admini');
        $lang   = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
                  ? explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0]
                  : $default;
        $auto   = $conf['auto_language'] ? $lang : null;
        $locale = $_REQUEST['lang'] ?? session('locale', $auto);
        $locale = collect($conf['languages'])->firstWhere('locale', $locale);
        $locale = $locale['locale'] ?? $default;

        session(['locale' => $locale]);
        \App::setLocale($locale);

        return $request->has('lang') ? redirect(url()->current()) : $next($request);
    }
}

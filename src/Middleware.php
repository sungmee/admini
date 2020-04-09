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
        $conf   = Config::get('admini');
        $lang   = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0] : config('app.locale');
        $auto   = $conf['auto_language'] ? $lang : null;
        $locale = $_REQUEST['lang'] ?? session('locale', $auto);
        $locale = collect($conf['languages'])->firstWhere('locale', $locale);
        $locale = $locale['locale'] ?? Config::get('app.locale');

        session(['locale' => $locale]);
        \App::setLocale($locale);

        return $request->has('lang') ? redirect(url()->current()) : $next($request);
    }
}

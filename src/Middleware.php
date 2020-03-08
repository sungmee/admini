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
        $locale = $_REQUEST['lang'] ?? session('locale', explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0]);
        $locale = collect(Config::get('admini.languages'))->firstWhere('locale', $locale);
        $locale = $locale['locale'] ?? Config::get('app.locale');

        session(['locale' => $locale]);
        \App::setLocale($locale);

        return $request->has('lang') ? redirect(url()->current()) : $next($request);
    }
}

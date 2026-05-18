<?php

namespace Sungmee\Admini;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class Middleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $default = Config::get('app.locale');
        $conf    = Config::get('admini');
        $lang    = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
                  ? explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0]
                  : $default;
        $auto    = $conf['auto_language'] ? $lang : null;
        $locale  = $request->lang ?? $request->cookie('locale') ?? session('locale', $auto);
        $locale  = collect($conf['languages'])->firstWhere('locale', $locale);
        $locale  = $locale['locale'] ?? $default;

        session(['locale' => $locale]);
        App::setLocale($locale);

        if ($request->has('lang')) {
            $params  = $request->except('lang');
            $params  = http_build_query($params);
            $params  = empty($params) ? '' : "?$params";
            $url     = url()->current() . $params;
            $minutes = 60 * 24 * 7;
            return redirect($url)->cookie('locale', $locale, $minutes);
        }

        return $next($request);
    }
}

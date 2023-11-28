<?php

namespace App\Http\Middleware;

use App\Models\Club;
use Closure;

class LoadClub
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
        /*
        if (env('APP_ENV') === 'local' || env('APP_ENV') === 'testing') {
            $club = Club::find(1);
            $request->request->add(['club' => $club]);
            return $next($request);
        }*/
        if ($request->header('X-Language')) {
            \App::setLocale($request->header('X-Language'));
        }
        if ($request->header('X-Club')) {
            $id = intval($request->header('X-Club', 1));
            $club = Club::find($id);
            $request->request->add(['club' => $club]);

            return $next($request);
        }
        $url_r = parse_url($request->header('origin'));
        $origin = $url_r && isset($url_r['host']) ? $url_r['host'] : '';
        if (str_contains($origin, 'api.') || str_contains($origin, 'www.')) {
            return $next($request);
        }
        if (strpos($origin, 'hts.test') !== false || strpos($origin, 'localhost') !== false) {
            $subdomain = 'hackl';
            $club = Club::where('subdomain', $subdomain)->first();
            $request->request->add(['club' => $club]);

            return $next($request);
        }
        if (str_contains($origin, 'app.') || str_contains($origin, 'localhost') || $request->header('X-Club')) {
            $id = intval($request->header('X-Club', 1));
            $club = Club::find($id);
            $request->request->add(['club' => $club]);
            return $next($request);
        }
        if (str_contains($origin, 'inspirium.hr')) {
            $subdomain = str_replace('.inspirium.hr', '', $origin);
            $club = Club::where('subdomain', $subdomain)->first();
        } else {
            $origin = str_replace('www.', '', $origin);
            $club = Club::where('domain', $origin)->first();
        }
        $request->request->add(['club' => $club]);

        return $next($request);
    }
}

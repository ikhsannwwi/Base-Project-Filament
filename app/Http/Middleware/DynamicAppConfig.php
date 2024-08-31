<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Outerweb\Settings\Models\Setting;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class DynamicAppConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        Config::set('app.name', Setting::get('general.brand_name'));
        Config::set('app.locale', Setting::get('general.locale'));
        Config::set('app.timezone', Setting::get('general.timezone'));
        Config::set('app.url', Setting::get('developer.base_url'));
        Config::set('app.asset_url', Setting::get('developer.asset_url'));
        Config::set('app.debug', Setting::get('developer.debug'));

        // dd(config());
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is authenticated, use their language settings
        if ($request->user() && $request->user()->settings) {
            $locale = $request->user()->settings->language ?? config('app.locale');
        } else {
            // If the user is not authenticated, use the default language of the application
            $locale = config('app.locale');
        }

        app()->setLocale($locale);
        
        return $next($request);
    }
}

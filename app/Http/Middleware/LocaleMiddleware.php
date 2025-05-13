<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $acceptLang = $request->header('Accept-Language');

        // Extract the first locale, e.g. "en_US" from "en_US,en;q=0.9"
        $locale = collect(explode(',', $acceptLang))
            ->map(fn($lang) => trim(explode(';', $lang)[0])) // get rid of ;q=x
            ->first();

        // Default fallback
        $locale = $locale ?: config('app.fallback_locale');

        App::setLocale($locale);

        return $next($request);
    }
}

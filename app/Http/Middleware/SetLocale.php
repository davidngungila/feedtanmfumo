<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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
        // Get locale from session, user preferences, or default
        $locale = Session::get('locale');

        if (! $locale && auth()->check()) {
            $preferences = auth()->user()->preferences ?? [];
            $locale = $preferences['language'] ?? null;
        }

        if (! $locale) {
            $locale = \App\Models\Setting::get('language') ?? config('app.locale', 'en');
        }

        // Validate locale
        if (! in_array($locale, ['en', 'sw'])) {
            $locale = 'en';
        }

        // Set application locale
        App::setLocale($locale);

        return $next($request);
    }
}

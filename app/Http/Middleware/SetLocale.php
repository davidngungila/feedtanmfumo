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
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, user preferences, or default
        $locale = Session::get('locale');

        // If no session locale, check user preferences
        if (!$locale && auth()->check()) {
            $user = auth()->user();
            $preferences = $user->preferences ?? [];
            $locale = $preferences['language'] ?? null;
        }

        // Fallback to config default
        if (!$locale) {
            $locale = config('app.locale', 'en');
        }

        // Validate locale
        if (!in_array($locale, ['en', 'sw'])) {
            $locale = 'en';
        }

        // Set application locale
        App::setLocale($locale);

        return $next($request);
    }
}

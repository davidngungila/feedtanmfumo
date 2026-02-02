<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch application language
     */
    public function switchLanguage(Request $request, string $locale)
    {
        // Validate locale
        if (! in_array($locale, ['en', 'sw'])) {
            $locale = 'en';
        }

        // Set locale
        App::setLocale($locale);
        Session::put('locale', $locale);

        // Store in user preferences if authenticated
        if (auth()->check()) {
            $user = auth()->user();
            $preferences = $user->preferences ?? [];
            $preferences['language'] = $locale;
            $user->update(['preferences' => $preferences]);
        }

        // Redirect back to previous page
        return redirect()->back();
    }
}

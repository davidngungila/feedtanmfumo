<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    public function setLocale(Request $request)
    {
        $locale = $request->input('locale', 'en');
        if (!in_array($locale, ['en', 'sw'])) {
            $locale = 'en';
        }

        session(['locale' => $locale]);

        if (Auth::check()) {
            $user = Auth::user();
            $user->preferred_language = $locale;
            $user->save();
        }

        return redirect()->back();
    }
}

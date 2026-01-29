<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    /**
     * Show Terms of Service page
     */
    public function terms(): \Illuminate\Contracts\View\View
    {
        return view('legal.terms');
    }

    /**
     * Show Privacy Policy page
     */
    public function privacy(): \Illuminate\Contracts\View\View
    {
        return view('legal.privacy');
    }
}

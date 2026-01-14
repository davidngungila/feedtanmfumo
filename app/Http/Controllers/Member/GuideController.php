<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class GuideController extends Controller
{
    public function index()
    {
        return view('member.guide.index');
    }
}

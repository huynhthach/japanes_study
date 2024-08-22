<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function changeLanguage($locale)
    {
        Session::put('locale', $locale);
        return redirect()->back();
    }
}

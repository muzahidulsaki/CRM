<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Switch application language between 'en' and 'bn'.
     */
    public function switch(string $lang): RedirectResponse
    {
        if (in_array($lang, ['en', 'bn'])) {
            Session::put('locale', $lang);
            $message = $lang === 'bn' ? 'Language changed to Bengali' : 'Language changed to English';
            return redirect()->back()->with('success', $message);
        }

        return redirect()->back();
    }
}

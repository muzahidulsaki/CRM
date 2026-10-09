<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                if ($setting->type === 'json' && is_string($value)) {
                    // split comma separated if user typed text
                    $items = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $value))));
                    Setting::set($key, array_values($items), $setting->group, 'json', $setting->description);
                } else {
                    Setting::set($key, $value, $setting->group, $setting->type, $setting->description);
                }
            }
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}

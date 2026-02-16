<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Index', [
            'settings' => Setting::orderBy('group')->orderBy('key')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:150', 'unique:settings,key'],
            'group' => ['nullable', 'string', 'max:100'],
            'value' => ['nullable', 'string'],
        ]);

        Setting::create($data);

        return back();
    }

    public function update(Request $request, Setting $setting)
    {
        $data = $request->validate([
            'group' => ['nullable', 'string', 'max:100'],
            'value' => ['nullable', 'string'],
        ]);

        $setting->update($data);

        return back();
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();
        return back();
    }
}

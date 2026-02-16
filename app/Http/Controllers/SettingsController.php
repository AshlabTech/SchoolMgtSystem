<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\Setting;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Index', [
            'settings' => Setting::orderBy('group')->orderBy('key')
                ->get()
                ->map(fn (Setting $setting) => $this->hydrateSetting($setting)),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'max:150', 'unique:settings,key'],
            'label' => ['nullable', 'string', 'max:150'],
            'group' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'in:text,number,boolean,select,date'],
            'value' => ['nullable'],
            'options' => ['nullable', 'array'],
            'options.*.label' => ['required_with:options', 'string'],
            'options.*.value' => ['required_with:options'],
            'options_source' => ['nullable', 'string', 'max:150'],
            'options_label' => ['nullable', 'string', 'max:150'],
            'options_value' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'is_locked' => ['nullable', 'boolean'],
        ]);

        $data['type'] = $data['type'] ?? 'text';
        Setting::create($data);

        return back();
    }

    public function update(Request $request, Setting $setting)
    {
        $rules = [
            'group' => ['nullable', 'string', 'max:100'],
            'value' => ['nullable'],
        ];

        if ($setting->key === 'set_current_academic_year') {
            $rules['value'] = ['required', 'integer', 'exists:academic_years,id'];
        }

        if ($setting->key === 'set_current_term') {
            $rules['value'] = ['required', 'integer', 'exists:terms,id'];
        }

        $data = $request->validate($rules);

        $setting->update($data);

        if ($setting->key === 'set_current_academic_year') {
            AcademicYear::query()->update(['is_current' => false]);
            AcademicYear::whereKey($setting->value)->update(['is_current' => true]);
        }

        if ($setting->key === 'set_current_term') {
            Term::query()->update(['is_current' => false]);
            Term::whereKey($setting->value)->update(['is_current' => true]);
        }

        return back();
    }

    public function destroy(Setting $setting)
    {
        abort(403, 'Settings cannot be deleted.');
    }

    private function hydrateSetting(Setting $setting): Setting
    {
        $options = $setting->options ?? [];

        if ($setting->options_source) {
            $labelKey = $setting->options_label ?: 'name';
            $valueKey = $setting->options_value ?: 'id';
            $options = match ($setting->options_source) {
                'academic_years' => AcademicYear::orderByDesc('name')
                    ->get([$valueKey, $labelKey])
                    ->map(fn ($item) => ['label' => $item->{$labelKey}, 'value' => $item->{$valueKey}])
                    ->all(),
                'terms' => Term::orderBy('order')
                    ->get([$valueKey, $labelKey])
                    ->map(fn ($item) => ['label' => $item->{$labelKey}, 'value' => $item->{$valueKey}])
                    ->all(),
                'class_levels' => ClassLevel::orderBy('name')
                    ->get([$valueKey, $labelKey])
                    ->map(fn ($item) => ['label' => $item->{$labelKey}, 'value' => $item->{$valueKey}])
                    ->all(),
                default => $options,
            };
        }

        $setting->options = $options;

        if ($setting->type === 'select' && is_numeric($setting->value)) {
            $setting->value = (int) $setting->value;
        }

        return $setting;
    }
}

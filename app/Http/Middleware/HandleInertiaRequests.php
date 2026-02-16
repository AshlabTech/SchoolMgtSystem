<?php

namespace App\Http\Middleware;

use App\Models\AcademicYear;
use App\Models\Setting;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $currentYearId = Setting::query()->where('key', 'set_current_academic_year')->value('value');
        $currentTermId = Setting::query()->where('key', 'set_current_term')->value('value');

        $currentYear = $currentYearId
            ? AcademicYear::query()->find($currentYearId)
            : AcademicYear::query()->where('is_current', true)->first();

        $currentTerm = $currentTermId
            ? Term::query()->find($currentTermId)
            : Term::query()->where('is_current', true)->first();
        if (!$currentTerm && $currentYear) {
            $currentTerm = $currentYear->terms()->orderBy('order')->first();
        }

        $schoolSettings = Setting::query()
            ->where('group', 'school_profile')
            ->pluck('value', 'key');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'schoolContext' => [
                'academicYear' => $currentYear?->only(['id', 'name']),
                'term' => $currentTerm?->only(['id', 'name']),
                'schoolName' => $schoolSettings['school_name'] ?? '',
                'schoolMotto' => $schoolSettings['school_motto'] ?? '',
                'schoolType' => $schoolSettings['school_type'] ?? 'combined',
            ],
            'auth' => [
                'user' => $request->user(),
                'roles' => $request->user()?->getRoleNames(),
                'permissions' => $request->user()?->getAllPermissions()->pluck('name'),
            ],
        ];
    }
}

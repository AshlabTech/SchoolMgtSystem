<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PinsController extends Controller
{
    public function index()
    {
        return Inertia::render('Pins/Index', [
            'pins' => Pin::query()->orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:200'],
        ]);

        for ($i = 0; $i < $data['quantity']; $i++) {
            Pin::create([
                'code' => Str::upper(Str::random(10)),
                'times_used' => 0,
                'is_used' => false,
            ]);
        }

        return back();
    }

    public function destroy(Pin $pin)
    {
        $pin->delete();

        return back();
    }
}

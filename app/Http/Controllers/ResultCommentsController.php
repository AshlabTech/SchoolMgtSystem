<?php

namespace App\Http\Controllers;

use App\Models\ResultComment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResultCommentsController extends Controller
{
    public function index()
    {
        return Inertia::render('Comments/Index', [
            'comments' => ResultComment::query()->orderBy('type')->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'comment' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:teacher,principal'],
            'is_active' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (!empty($data['is_default'])) {
            ResultComment::query()->where('type', $data['type'])->update(['is_default' => false]);
        }

        ResultComment::create($data + [
            'is_active' => (bool) ($data['is_active'] ?? true),
            'is_default' => (bool) ($data['is_default'] ?? false),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        return back();
    }

    public function update(Request $request, ResultComment $comment)
    {
        $data = $request->validate([
            'comment' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:teacher,principal'],
            'is_active' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (!empty($data['is_default'])) {
            ResultComment::query()
                ->where('type', $data['type'])
                ->whereKeyNot($comment->id)
                ->update(['is_default' => false]);
        }

        $comment->update($data + [
            'is_active' => (bool) ($data['is_active'] ?? false),
            'is_default' => (bool) ($data['is_default'] ?? false),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        return back();
    }
}

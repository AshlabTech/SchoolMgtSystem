<?php

namespace App\Http\Controllers;

use App\Models\LibraryBook;
use App\Models\LibraryLoan;
use App\Models\SchoolClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LibraryController extends Controller
{
    public function index()
    {
        return Inertia::render('Library/Index', [
            'books' => LibraryBook::with('schoolClass')->orderBy('title')->get(),
            'loans' => LibraryLoan::with(['book', 'borrower'])->orderByDesc('issued_at')->get(),
            'classes' => SchoolClass::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
        ]);
    }

    public function storeBook(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'author' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'description' => ['nullable', 'string'],
            'url' => ['nullable', 'string', 'max:255'],
            'total_copies' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['issued_copies'] = 0;

        LibraryBook::create($data);

        return back();
    }

    public function destroyBook(LibraryBook $book)
    {
        $book->delete();
        return back();
    }

    public function updateBook(Request $request, LibraryBook $book)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'author' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'description' => ['nullable', 'string'],
            'url' => ['nullable', 'string', 'max:255'],
            'total_copies' => ['nullable', 'integer', 'min:0'],
        ]);

        $book->update($data);

        return back();
    }

    public function storeLoan(Request $request)
    {
        $data = $request->validate([
            'book_id' => ['required', 'exists:library_books,id'],
            'borrower_user_id' => ['required', 'exists:users,id'],
            'issued_at' => ['required', 'date'],
            'due_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $book = LibraryBook::findOrFail($data['book_id']);
        if ($book->total_copies !== null && $book->issued_copies >= $book->total_copies) {
            return back()->withErrors(['book_id' => 'No copies available for this book.']);
        }

        $loan = LibraryLoan::create([
            'book_id' => $book->id,
            'borrower_user_id' => $data['borrower_user_id'],
            'issued_at' => $data['issued_at'],
            'due_at' => $data['due_at'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'issued',
        ]);

        $book->update(['issued_copies' => $book->issued_copies + 1]);

        return back();
    }

    public function returnLoan(LibraryLoan $loan)
    {
        if ($loan->returned_at) {
            return back();
        }

        $loan->update([
            'returned_at' => Carbon::now(),
            'status' => 'returned',
        ]);

        $book = $loan->book;
        if ($book && $book->issued_copies > 0) {
            $book->update(['issued_copies' => $book->issued_copies - 1]);
        }

        return back();
    }
}

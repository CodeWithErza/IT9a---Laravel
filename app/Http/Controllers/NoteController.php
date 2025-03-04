<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('notes.index', [
            'notes' => Note::with('user')->latest()->get(),
        ]);    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): View
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);
     
        $request->user()->notes()->create($validated);
     
        return view('notes.index', [
            'notes' => Note::with('user')->latest()->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note): View
    {
        Gate::authorize('update', $note);

        return view('notes.edit', [
            'note' => $note,
        ]);    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note): View
    {
        Gate::authorize('update', $note);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $note->update($validated);

        return view('notes.index', [
            'notes' => Note::with('user')->latest()->get(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note): View
    {
        Gate::authorize('delete', $note);

        $note->delete();

        return view('notes.index', [
            'notes' => Note::with('user')->latest()->get(),
        ]);
    }
}

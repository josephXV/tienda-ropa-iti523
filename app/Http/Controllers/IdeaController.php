<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea;

class IdeaController extends Controller
{
    public function index()
    {
        $ideas = Idea::get();
        return view('ideas.index', ['ideas' => $ideas]);
    }

    public function create()
    {
        return view('ideas.create_or_edit');
    }

    public function guardar(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:300',
        ]);

        Idea::create([
            'user_id' => auth()->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('idea.index');
    }

    public function edit(Idea $idea)
    {
        return view('ideas.create_or_edit', compact('idea'));
    }

    public function update(Request $request, Idea $idea)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:300',
        ]);

        $idea->update($validated);

        return redirect(route('idea.index'));
    }

    public function mostrarIdea(Idea $idea)
    {
        return view('ideas.ver', compact('idea'));
    }

    public function delete(Idea $idea)
    {
        $idea->delete();

        return redirect(route('idea.index'));
    }
}

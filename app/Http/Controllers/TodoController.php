<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Models\Group;

class TodoController extends Controller
{
    public function index()
    {
        $groups = Group::with('todos')->get();
        return view('todos.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'group_id' => 'required|exists:groups,id'
        ]);
        Todo::create($request->all());
        return redirect()->route('todos.index');
    }

    public function update(Request $request, Todo $todo)
    {
        if ($request->has('completed')) {
            $todo->update(['completed' => !$todo->completed]);
            return response()->json(['success' => true, 'completed' => $todo->completed ? 'yes' : 'no']);
        } else {
            $request->validate(['title' => 'required|max:255']);
            $todo->update($request->all());
            return redirect()->route('todos.index');
        }
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index');
    }
}

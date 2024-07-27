<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('todos')->get();
        return view('todos.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|max:255']);
        Group::create($request->all());
        return redirect()->route('todos.index');
    }



    public function update(Request $request, Group $group)
    {
        $request->validate(['name' => 'required|max:255']);
        $group->update($request->all());
        return redirect()->route('groups.index');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('todos.index');
    }
}
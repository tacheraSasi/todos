<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(){
        // $tasks = Auth::user()->tasks;
        $user_id = Auth::user()->id;
        $tasks = DB::table("tasks")
        ->where("user_id",$user_id)
        ->get();
        // dd($tasks);
        return view("tasks.index",compact("tasks"));

    }
    public function create(){
        return view("tasks.create");
    }
    

    public function store(Request $request){
        $request->validate([
            "title"=>"required",
        ]);
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tasks');
    }
    // Show the form to edit an existing task
    public function edit(Task $task)
    {
        if ($task->user_id != Auth::id()) {
            abort(403);
        }
        return view('tasks.edit', compact('task'));
    }

    // Update an existing task
    public function update(Request $request, Task $task)
    {
        if ($task->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks');
    }

    // Delete a task
    public function destroy(Task $task)
    {
        if ($task->user_id != Auth::id()) {
            abort(403);
        }

        $task->delete();
        return redirect()->route('tasks');
    }
}

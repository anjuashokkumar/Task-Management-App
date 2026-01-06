<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;
use App\Models\Todo;

class TodoController extends Controller
{
    public function store(TodoRequest $request)
    {
        $todo = Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task added successfully!',
            'todo' => [
                'id' => $todo->id,
                'title' => $todo->title,
                'description' => $todo->description,
                'status' => $todo->status,
                'due_date' => $todo->due_date ? $todo->due_date->format('d M Y') : '',
                'created_at' => $todo->created_at->format('d M Y'),
            ]
        ]);
    }

    public function complete(Todo $todo)
    {
        $todo->status = 'Completed';
        $todo->save();

        return response()->json([
            'success' => true,
            'todo' => [
                'id' => $todo->id,
                'status' => $todo->status,
            ]
        ]);
    }

    public function show(Todo $todo)
    {
        return response()->json([
            'id' => $todo->id,
            'title' => $todo->title,
            'description' => $todo->description,
            'status' => $todo->status,
            'due_date' => optional($todo->due_date)->format('Y-m-d'),
        ]);
    }

    public function update(TodoRequest $request, Todo $todo)
    {
        $todo->update($request->only([
            'title','description','status','due_date'
        ]));

        return response()->json([
            'success' => true,
            'todo' => [
                'id' => $todo->id,
                'title' => $todo->title,
                'description' => $todo->description,
                'status' => $todo->status,
                'due_date' => $todo->due_date?->format('Y-m-d'),
                'due_date_formatted' => $todo->due_date?->format('d M Y'),
            ]
        ]);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json([
            'success' => true
        ]);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Pending,In Progress,Completed',
            'due_date' => 'nullable|date|after_or_equal:today',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Task title is required',
            'title.max' => 'Task title cannot exceed 255 characters',

            'status.required' => 'Please select a status',
            'status.in' => 'Invalid status selected',

            'due_date.date' => 'Please select a valid due date',
            'due_date.after_or_equal' => 'Due date cannot be in the past',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->task->user_id !== $this->user()->id) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'completed' => 'sometimes|bool',
            'due_date' => 'sometimes|date_format:Y-m-d H:i:s|after_or_equal:' . date('Y-m-d H:i:s'),
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->dueDate) {
            $this->merge([
                'due_date' => $this->dueDate,
            ]);
        }
    }
}

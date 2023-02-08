<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'title' => 'required|string',
            'description' => 'sometimes|string',
            'completed' => 'sometimes|bool',
            'dueDate' => 'sometimes|date_format:Y-m-d H:i:s|after_or_equal:' . date('Y-m-d H:i:s'),
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

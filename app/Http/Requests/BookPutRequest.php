<?php

namespace App\Http\Requests;

use App\Models\Pivot\BookUser;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookPutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->book);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'author' => 'required',
            'status' => ['required', Rule::in(array_keys(BookUser::$statuses))],
        ];
    }
}

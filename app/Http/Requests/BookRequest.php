<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $id = $this->route('books');

        return [
            'name' => [
                'min:5|
                required', Rule::unique('authors')->ignore($id),
            ],
            'image' => 'image',
            'description' => 'max: 1000',
            'category_id' => 'required|array',
            'author_id' => 'required|integer',
            'publisher_id' => 'required|integer',
            'in_stock' => 'required|integer|min:0|max:100',
            'total' => 'required|integer|min:0|max:100',
            'status' => 'required|integer',
        ];
    }
}

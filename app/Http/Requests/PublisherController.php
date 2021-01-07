<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublisherController extends FormRequest
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
        $id = $this->route('publisher');

        return [
            'name' => [
                'max:255|
                required', Rule::unique('publishers')->ignore($id),
            ],
            'image' => 'mimes:jpeg,jpg,png,gif|max:10000',
            'email' => 'email|nullable',
            'phone' => 'regex:/(0)[0-9]{9}/|max:11',
            'address' => 'nullable|max:10000',
        ];
    }
}

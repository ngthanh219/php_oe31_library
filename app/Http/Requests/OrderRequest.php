<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrderRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $maxDate = Carbon::now()->addDays(config('request.max_date'))->toString();
        $maxDate = date('d-m-Y', strtotime($maxDate));

        return [
            'borrowed_date' => 'required|date|after_or_equal:today|before_or_equal:'.$maxDate,
            'return_date' => 'required|date|after_or_equal:borrowed_date',
        ];
    }
}

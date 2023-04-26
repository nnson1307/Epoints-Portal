<?php

namespace Modules\ChatHub\Http\Requests\Response;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'response_name' => 'required',
            'response_content' => 'required',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'response_name.required' => __('Vui lòng nhập tiêu đề'),
            'response_content.required' => __('Vui lòng nhập câu trã lời'),
        ];
    }

}
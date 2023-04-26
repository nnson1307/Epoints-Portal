<?php

namespace Modules\ChatHub\Http\Requests\ResponseContent;

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
            'title' => 'required|max:255',
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
            'title.required' => __('Tiêu đề là bắt buộc'),
            'title.max' => __('Tiêu đề không được quá 588 ký tự'),
            'response_content.required' => __('Nội dung phản hồi là bắt buộc'),
        ];
    }

}
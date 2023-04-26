<?php

namespace Modules\Notification\Http\Requests\ConfigStaff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'send_type' => 'required',
            'title' => 'required|max:250',
            'message' => 'required|max:1000',
            'detail_content' => 'required',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'title.required' => __('Hãy nhập tiêu đề'),
            'title.max' => __('Tiêu đề tối đa 250 kí tự'),
            'message.required' => __('Hãy nhập nội dung'),
            'message.max' => __('Nội dung tối đa 1000 kí tự'),
            'send_type.required' => __('Hãy chọn loại gửi'),
            'detail_content.required' => __('Hãy chọn loại gửi')
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'title' => 'strip_tags|trim',
            'message' => 'strip_tags|trim'
        ];
    }
}
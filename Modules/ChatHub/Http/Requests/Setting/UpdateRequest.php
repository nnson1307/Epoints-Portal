<?php

namespace Modules\ChatHub\Http\Requests\Setting;

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
        $param = request()->all();
        return [
            'project_id_dialogflow' => 'max:255',
            'client_email_dialogflow' => 'max:255',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'project_id_dialogflow.max' => __('Id dự án luồng hội thoại không quá 255 ký tự'),
            'client_email_dialogflow.max' => __('Email khách hàng không được quá 255 ký tự'),
        ];
    }
}
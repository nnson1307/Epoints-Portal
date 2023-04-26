<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 11:31
 */

namespace Modules\ConfigDisplay\Http\Requests;

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
        $rules = [
            'image' => ["required"],
            'main_title' => ["required", "max:50"],
            'sub_title' => ["max:30"],
            'action_name' =>  ["required", "max:50"],
            'destination' => ["required"]
        ];
        return $rules;
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        $messages = [
            'image.required' => __("Ảnh hiển thị chưa được cài đặt"),
            'main_title.required' => __("Tiêu đề chính không được bỏ trống"),
            'main_title.max' => __("Tối đa 50 kí tự"),
            'sub_title.max' => __("Tối đa 30 kí tự"),
            'action_name.required' => __("Tên hành động không được bỏ trống"),
            'action_name.max' => __("Tối đa 30 kí tự"),
            'destination.required' => __("Chọn đích đến"),
        ];

        return $messages;
    }
}

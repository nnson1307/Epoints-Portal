<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/07/2021
 * Time: 17:27
 */

namespace Modules\OnCall\Http\Requests\Extension;

use Illuminate\Foundation\Http\FormRequest;

class SettingAccountRequest extends FormRequest
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
            'user_name' => 'required|max:191',
            'password' => 'required',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'user_name.required' => __('Hãy nhập tên tài khoản'),
            'user_name.max' => __('Tên tài khoản tối đa 191 kí tự'),
            'password.required' => __('Hãy nhập mật khẩu'),
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
            'user_name' => 'strip_tags|trim',
            'password' => 'strip_tags|trim'
        ];
    }
}
<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/28/2020
 * Time: 6:19 PM
 */

namespace Modules\User\Http\Requests\ForgetPassword;


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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|min:6|max:50',
            're_password' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => __('Vui lòng nhập mật khẩu'),
            'password.min' => __('Mật khẩu tối thiểu 6 ký tự'),
            'password.max' => __('Mật khẩu tối đa 50 ký tự'),
            're_password.required' => __('Nhập lại mật khẩu phải giống mật khẩu'),
            're_password.same' => __('Nhập lại mật khẩu phải giống mật khẩu'),
        ];
    }
}
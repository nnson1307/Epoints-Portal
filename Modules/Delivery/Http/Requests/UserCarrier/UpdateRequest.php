<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/8/2020
 * Time: 4:46 PM
 */

namespace Modules\Delivery\Http\Requests\UserCarrier;


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
            'full_name' => 'required|max:250',
            'phone' => 'required|digits:10|unique:user_carrier,phone,'. $param['user_carrier_id'] .',user_carrier_id,is_deleted,0',
            'address' => 'max:250',
            'user_name' => 'required|min:5|max:30|unique:user_carrier,user_name,'. $param['user_carrier_id'] .',user_carrier_id,is_deleted,0',
            'password_new' => ['nullable', 'required_with:password_confirm', 'same:password_confirm', 'min:5']
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'full_name.required' => __('Hãy nhập họ và tên'),
            'full_name.max' => __('Họ và tên tối đa 250 kí tự'),
            'phone.required' => __('Hãy nhập số điện thoại người nhận'),
            'phone.max' => __('Số điện thoại tối đa 10 kí tự'),
            'phone.digits' => __('Số điện thoại không hợp lệ'),
            'address.max' => __('Địa chỉ người nhận tối đa 250 kí tự'),
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
            'full_name' => 'strip_tags|trim',
            'phone' => 'strip_tags|trim',
            'address' => 'strip_tags|trim',
            'user_name' => 'strip_tags|trim',
            'password_new' => 'strip_tags|trim',
            'password_confirm' => 'strip_tags|trim'
        ];
    }
}
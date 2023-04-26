<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/8/2020
 * Time: 4:45 PM
 */

namespace Modules\Delivery\Http\Requests\UserCarrier;


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
            'full_name' => 'required|max:250',
            'phone' => 'required|digits:10|unique:user_carrier,phone,'.',user_carrier_id,is_deleted,0',
            'address' => 'max:250',
            'user_name' => 'required|min:5|max:30|unique:user_carrier,user_name,'.',user_carrier_id,is_deleted,0',
            'password' => ['required', 'required_with:password_confirm', 'same:password_confirm', 'min:5']
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
            'password' => 'strip_tags|trim',
            'password_confirm' => 'strip_tags|trim'
        ];
    }
}
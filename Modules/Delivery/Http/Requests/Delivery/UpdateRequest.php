<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 1:46 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Http\Requests\Delivery;


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
            'contact_name' => 'required|max:250',
            'contact_phone' => 'required|digits:10',
            'contact_address' => 'required|max:250',
            'total_transport_estimate' => 'required|integer',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'contact_name.required' => __('Hãy nhập người nhận'),
            'contact_name.max' => __('Người nhận tối đa 250 kí tự'),
            'contact_phone.required' => __('Hãy nhập số điện thoại người nhận'),
            'contact_phone.max' => __('Số điện thoại người nhận tối đa 10 kí tự'),
            'contact_phone.digits' => __('Số điện thoại người nhận không hợp lệ'),
            'contact_address.required' => __('Hãy nhập địa chỉ người nhận'),
            'contact_address.max' => __('Địa chỉ người nhận tối đa 250 kí tự'),
            'total_transport_estimate.required' => __('Hãy nhập số lần giao dự kiến'),
            'total_transport_estimate.integer' => __('Số lần giao dự kiến không hợp lệ')
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
            'contact_name' => 'strip_tags|trim',
            'contact_phone' => 'strip_tags|trim',
            'contact_address' => 'strip_tags|trim',
            'total_transport_estimate' => 'strip_tags|trim',
            'delivery_status' => 'strip_tags|trim',
        ];
    }
}
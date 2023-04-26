<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-23
 * Time: 2:24 PM
 * @author SonDepTrai
 */

namespace Modules\Admin\Http\Requests\Order;


use Illuminate\Foundation\Http\FormRequest;

class SubmitAddressRequest extends FormRequest
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
            'customer_name' => 'required|max:191',
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
            'address' => 'required|max:191',
            'customer_phone' => 'required|digits:10'
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'customer_name.required' => __('Vui lòng nhập tên người nhận'),
            'customer_name.max' => __('Tên người nhận vượt quá 191 ký tự'),
            'province_id.required' => __('Vui lòng chọn Tỉnh/Thành phố'),
            'district_id.required' => __('Vui lòng chọn Quận/Huyện'),
            'ward_id.required' => __('Vui lòng chọn Phường/Xã'),
            'address.required' => __('Vui lòng nhập địa chỉ'),
            'address.max' => __('Địa chỉ vượt quá 191 ký tự'),
            'customer_phone.required' => __('Vui lòng nhập số điện thoại người nhận'),
            'customer_phone.digits' => __('Số điện thoại người nhận không đúng định dạng'),
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
            'customer_name' => 'strip_tags|trim',
            'province_id' => 'strip_tags|trim',
            'district_id' => 'strip_tags|trim',
            'ward_id' => 'strip_tags|trim',
            'address' => 'strip_tags|trim',
            'customer_phone' => 'strip_tags|trim',
        ];
    }
}
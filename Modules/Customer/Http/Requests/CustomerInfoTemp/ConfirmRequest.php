<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 18/05/2021
 * Time: 10:15
 */

namespace Modules\Customer\Http\Requests\CustomerInfoTemp;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmRequest extends FormRequest
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
            'full_name' => 'required|max:190',
            'birthday' => 'nullable|date_format:d/m/Y',
            'email' => 'nullable|email|max:190|unique:customers,email,' . $param['customer_id'] . ',customer_id,is_deleted,0',
            'province_id' => 'required',
            'district_id' => 'required',
            'address' => 'required',
            'phone' => 'required|digits:10|unique:customers,phone1,' . $param['customer_id'] . ',customer_id,is_deleted,0'
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => __('Hãy nhập tên khách hàng'),
            'full_name.max' => __('Tên khách hàng tối đa 190 kí tự'),
            'birthday.date_format' => __('Ngày sinh không hợp lệ'),
            'email.email' => __('Email không hợp lệ'),
            'email.max' => __('Email tối đa 190 kí tự'),
            'email.unique' => __('Email đã tồn tại'),
            'province_id.required' => __('Hãy chọn tỉnh thành'),
            'district_id.required' => __('Hãy chọn quận huyện'),
            'address.required' => __('Hãy nhập địa chỉ'),
            'phone.digits'         => __('Số điện thoại không hợp lệ'),
            'phone.phone'          => __('Số điện thoại không hợp lệ'),
            'phone.unique' => __('Số điện thoại đã tồn tại'),
            'phone.required' => __('Hãy nhập số điện thoại'),
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

        ];
    }
}
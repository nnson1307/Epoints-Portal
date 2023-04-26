<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\CustomerLead\Http\Requests\CustomerLead;


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
            // 'phone' => 'required|phone|unique:cpo_customer_lead,phone,'. $param['customer_lead_id'] .',customer_lead_id,is_deleted,0', //|digits:10
            'address' => 'max:250',
            'email' => 'nullable|email',
            'pipeline_code' => 'required',
            'journey_code' => 'required',
            'customer_type' => 'required',
            'customer_source' => 'required'
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
            // 'phone.required' => __('Hãy nhập số điện thoại người nhận'),
            'phone.max' => __('Số điện thoại tối đa 10 kí tự'),
            'phone.digits' => __('Số điện thoại không hợp lệ'),
            // 'phone.unique' => __('Số điện thoại đã tồn tại'),
            // 'phone.phone'          => __('Số điện thoại không đúng định dạng'),
            'address.max' => __('Địa chỉ người nhận tối đa 250 kí tự'),
            'email.email' => __('Email không hợp lệ'),
            'pipeline_code.required' => __('Hãy chọn pipeline'),
            'journey_code.required' => __('Hãy chọn hành trình khách hàng'),
            'customer_type.required' => __('Hãy chọn loại khách hàng'),
            'customer_source.required' => __('Hãy chọn nguồn khách hàng'),
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
            'email' => 'strip_tags|trim',
            'gender' => 'strip_tags|trim',
            'hotline' => 'strip_tags|trim',
            'fanpage' => 'strip_tags|trim',
            'zalo' => 'strip_tags|trim',
            'customer_source' => 'strip_tags|trim'
        ];
    }
}
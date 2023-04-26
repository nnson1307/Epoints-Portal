<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/5/2021
 * Time: 10:11 AM
 */

namespace Modules\Warranty\Http\Requests\Maintenance;


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
            'customer_code' => 'required',
            'object_type' => 'required',
            'object_type_id' => 'required',
            'object_status' => 'nullable|max:191',
            'staff_id' => 'required',
            'date_estimate_delivery' => 'required',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'customer_code.required' => __('Hãy chọn khách hàng'),
            'object_type.required' => __('Hãy chọn loại đối tượng'),
            'object_type_id.required' => __('Hãy chọn đối tượng'),
            'object_status.max' => __('Tình trạng đối tượng tối đa 191 kí tự'),
            'staff_id.required' => __('Hãy chọn nhân viên thực hiện'),
            'date_estimate_delivery.required' => __('Hãy chọn ngày trả hàng dự kiến'),
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
            'customer_code' => 'strip_tags|trim',
            'object_type' => 'strip_tags|trim',
            'object_type_id' => 'strip_tags|trim',
            'object_status' => 'strip_tags|trim',
            'staff_id' => 'strip_tags|trim',
            'date_estimate_delivery' => 'strip_tags|trim',
            'maintenance_content' => 'strip_tags|trim',
        ];
    }
}
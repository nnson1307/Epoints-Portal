<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/08/2021
 * Time: 14:10
 */

namespace Modules\Contract\Http\Requests\ExpectedRevenue;

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
            'title' => 'required',
            'contract_category_remind_id' => 'required',
            'send_type' => 'required',
            'send_value' => 'nullable|integer',
            'send_value_child' => 'nullable|integer',
            'amount' => 'required'
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'title.required' => __('Tiêu đề không được trống'),
            'contract_category_remind_id.required' => __('Hãy chọn nội dung nhắc nhở'),
            'send_type.required' => __('Hãy chọn thời gian dự kiến thu'),
            'send_value.required' => __('Giá trị là kiểu số'),
            'send_value_child.required' => __('Số tháng là kiểu số'),
            'amount.required' => __('Hãy nhập giá trị thanh toán'),
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
            'contract_name' => 'strip_tags|trim'
        ];
    }
}
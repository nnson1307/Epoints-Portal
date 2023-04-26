<?php


namespace Modules\CustomerLead\Http\Requests\CustomerDeal;


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
            'deal_name' => 'required',
            'staff' => 'required',
            'customer_code' => 'required',
            'pipeline_code' => 'required',
            'journey_code' => 'required',
            'end_date_expected' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'deal_name.required' => __('Hãy nhập tên deal'),
            'staff.required' => __('Hãy chọn người sở hữu deal'),
            'customer_code.required' => __('Hãy chọn khách hàng'),
            'pipeline_code.required' => __('Hãy chọn pipeline'),
            'journey_code.required' => __('Hãy chọn hành trình khách hàng'),
            'end_date_expected.required' => __('Hãy chọn ngày kết thúc dự kiến'),
        ];
    }
}
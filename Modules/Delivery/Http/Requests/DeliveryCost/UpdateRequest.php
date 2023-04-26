<?php


namespace Modules\Delivery\Http\Requests\DeliveryCost;

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
            'delivery_cost_name' => 'required|max:250',
            'delivery_cost' => 'required',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'delivery_cost_name.required' => __('Hãy nhập tên chi phí vận chuyển'),
            'delivery_cost_name.max' => __('Tên chi phí vận chuyển tối đa 250 kí tự'),
            'delivery_cost.required' => __('Hãy nhập chi phí vận chuyển'),
        ];
    }
}
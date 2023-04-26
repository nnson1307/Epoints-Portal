<?php

namespace Modules\Delivery\Http\Requests\PickupAddress;

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
            'address' => 'required|max:250',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'address.required' => __('Hãy nhập địa chỉ lấy hàng'),
            'address.max' => __('Địa chỉ lấy hàng tối đa 250 kí tự'),
        ];
    }
}
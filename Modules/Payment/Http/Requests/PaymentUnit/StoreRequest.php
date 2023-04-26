<?php

namespace Modules\Payment\Http\Requests\PaymentUnit;

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
            'name' => 'max:191|unique:payment_units,name,'.',payment_unit_id,is_deleted,0',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'name.unique' => __('Tên đơn vị thanh toán là duy nhất'),
            'name.max' => __('Tên đơn vị thanh toán không quá 191 kí tự'),
        ];
    }

}
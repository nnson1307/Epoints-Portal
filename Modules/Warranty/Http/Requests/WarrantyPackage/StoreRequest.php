<?php

namespace Modules\Warranty\Http\Requests\WarrantyPackage;

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
            'packageName' => 'required|max:250',
            'percent' => 'required',
            'moneyMaximum' => 'required',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'packageName.required' => __('Hãy nhập tên gói bảo hành'),
            'packageName.max' => __('Tên gói bảo hành tối đa 250 kí tự'),
            'percent.required' => __('Hãy nhập giá trị bảo hành'),
            'moneyMaximum.required' => __('Hãy nhập số tiền tối đa được bảo hành'),
        ];
    }
}
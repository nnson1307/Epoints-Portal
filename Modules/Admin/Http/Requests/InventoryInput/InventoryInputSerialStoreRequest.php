<?php

namespace Modules\Admin\Http\Requests\InventoryInput;

use Illuminate\Foundation\Http\FormRequest;

class InventoryInputSerialStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'serial' => 'required|max:191',
        ];
    }

    public function messages()
    {
        return [
            'serial.required' => __('Vui lòng nhập số serial'),
            'serial.max' => __('Số serial vượt quá 191 ký tự'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'serial' => 'strip_tags|trim',
        ];
    }
}

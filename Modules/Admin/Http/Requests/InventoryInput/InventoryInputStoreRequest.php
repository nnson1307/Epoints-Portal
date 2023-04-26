<?php

namespace Modules\Admin\Http\Requests\InventoryInput;

use Illuminate\Foundation\Http\FormRequest;

class InventoryInputStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'warehouse_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'warehouse_id.required' => __('Vui lòng chọn nhà kho'),
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
}

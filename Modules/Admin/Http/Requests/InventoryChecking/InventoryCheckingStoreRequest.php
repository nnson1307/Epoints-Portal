<?php

namespace Modules\Admin\Http\Requests\InventoryChecking;

use Illuminate\Foundation\Http\FormRequest;

class InventoryCheckingStoreRequest extends FormRequest
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
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'warehouse_id.required' => __('Vui lòng chọn nhà kho'),
            'description.required' => __('Vui lòng nhập lý do'),
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
            'warehouse_id' => 'strip_tags|trim',
            'description' => 'strip_tags|trim',
        ];
    }
}

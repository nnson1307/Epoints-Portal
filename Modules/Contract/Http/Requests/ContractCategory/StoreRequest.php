<?php

namespace Modules\Contract\Http\Requests\ContractCategory;

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
//            'contract_category_code' => 'unique:contract_categories,contract_category_code,'.',contract_category_code,is_deleted,0',
            'contract_category_name' => 'unique:contract_categories,contract_category_name,'.',contract_category_name,is_deleted,0',
            'contract_code_format' => 'unique:contract_categories,contract_code_format,'.',contract_code_format,is_deleted,0',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
//            'contract_category_code.unique' => __('Mã loại hợp đồng đã tồn tại'),
            'contract_category_name.unique' => __('Tên loại hợp đồng đã tồn tại'),
            'contract_code_format.unique' => __('Cấu hình mã hợp đồng đã tồn tại'),
        ];
    }

}
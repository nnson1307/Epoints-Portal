<?php

namespace Modules\Contract\Http\Requests\ContractCategory;

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
        $param = request()->all();
        return [
//            'contract_category_code' => 'unique:contract_categories,contract_category_code,'. $param['contract_category_id'] .',contract_category_id,is_deleted,0',
            'contract_category_name' => 'unique:contract_categories,contract_category_name,'. $param['contract_category_id'] .',contract_category_id,is_deleted,0',
            'contract_code_format' => 'unique:contract_categories,contract_code_format,'. $param['contract_category_id'] .',contract_category_id,is_deleted,0',
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
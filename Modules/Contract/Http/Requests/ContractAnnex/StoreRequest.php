<?php

namespace Modules\Contract\Http\Requests\ContractAnnex;

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
            'contract_annex_code' => 'unique:contract_annex,contract_annex_code,'.',contract_annex_code,is_deleted,0',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'contract_annex_code.unique' => __('Mã phụ lục đã tồn tại'),
        ];
    }

}
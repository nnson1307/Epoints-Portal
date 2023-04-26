<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2021
 * Time: 11:31
 */

namespace Modules\Contract\Http\Requests\Contract;

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
            'contract_name' => 'required',
            'contract_no' => 'required|unique:contracts,contract_no,'.',contract_id,is_deleted,0'
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'contract_name.required' => __('Tên hợp đồng không được trống'),
            'contract_no.required' => __('Số hợp đồng không được trống'),
            'contract_no.unique' => __('Số hợp đồng đã tồn tại'),
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'contract_name' => 'strip_tags|trim',
            'contract_no' => 'strip_tags|trim'
        ];
    }
}
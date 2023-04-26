<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/07/2022
 * Time: 14:40
 */

namespace Modules\Team\Http\Requests\Company;

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
            'company_name' => 'required|max:190|unique:company,company_name,'. $param['company_id'] .',company_id,is_deleted,0'
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'company_name.required' => __('Hãy nhập tên công ty'),
            'company_name.max' => __('Tên công ty tối đa 190 kí tự'),
            'company_name.unique' => __('Tên công ty đã tồn tại'),
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
            'team_name' => 'strip_tags|trim',
        ];
    }
}
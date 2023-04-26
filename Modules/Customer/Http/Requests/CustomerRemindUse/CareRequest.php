<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/06/2021
 * Time: 15:09
 */

namespace Modules\Customer\Http\Requests\CustomerRemindUse;

use Illuminate\Foundation\Http\FormRequest;

class CareRequest extends FormRequest
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
            'type_name' => 'required|max:191',
            'content' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'type_name.required' => __('Hãy nhập loại chăm sóc'),
            'type_name.max' => __('Loại chăm sóc tối đa 190 kí tự'),
            'content.required' => __('Hãy nhập nội dung chăm sóc'),
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

        ];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 18/11/2021
 * Time: 16:00
 */

namespace Modules\Config\Http\Requests\ConfigCustomerParameter;

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
            'parameter_name' => 'required|max:190|unique:config_customer_parameter,parameter_name,'.$param['parameter_id'].',config_customer_parameter_id,is_deleted,0',
            'content' => 'required|max:190',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'parameter_name.required' => __('Hãy nhập tên tham số'),
            'parameter_name.max' => __('Tên tham số tối đa 190 kí tự'),
            'parameter_name.unique' => __('Tên tham số đã tồn tại'),
            'content.required' => __('Hãy nhập nội dung'),
            'content.max' => __('Nội dung tối đa 190 kí tự'),
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
            'parameter_name' => 'strip_tags|trim',
            'content' => 'strip_tags|trim',
        ];
    }
}
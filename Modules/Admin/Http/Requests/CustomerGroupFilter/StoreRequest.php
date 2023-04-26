<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/24/2019
 * Time: 10:52 AM
 */

namespace Modules\Admin\Http\Requests\CustomerGroupFilter;

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
            'name' => 'required|max:255|unique:customer_group_filter,name,NULL,id',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên nhóm khách hàng',
            'name.max' => __('Tên nhóm khách hàng tối đa 255 ký tự'),
            'name.unique' => __('Tên nhóm khách hàng đã tồn tại'),
        ];
    }
}
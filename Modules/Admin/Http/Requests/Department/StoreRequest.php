<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/28/2020
 * Time: 6:19 PM
 */

namespace Modules\Admin\Http\Requests\Department;


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
            'department_name' => 'required|max:190|unique:departments,department_name,'.',department_id,is_deleted,0',
//            'staff_title_id' => 'required',
//            'branch_id' => 'required',
//            'staff_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'department_name.required' => __('Hãy nhập tên phòng ban'),
            'department_name.max' => __('Tên phòng ban tối đa 190 kí tự'),
            'department_name.unique' => __('Tên phòng ban đã tồn tại'),
            'staff_title_id.required' => __('Hãy chọn chức vụ'),
            'branch_id.required' => __('Hãy chọn thông tin nhánh cha'),
            'staff_id.required' => __('Hãy chọn người quản lý'),
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
            'department_name' => 'strip_tags|trim',
            'staff_title_id' => 'strip_tags|trim',
            'branch_id' => 'strip_tags|trim',
            'staff_id' => 'strip_tags|trim',
        ];
    }
}
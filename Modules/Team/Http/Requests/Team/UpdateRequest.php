<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/07/2022
 * Time: 10:45
 */

namespace Modules\Team\Http\Requests\Team;

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
            'team_name' => 'required|max:190|unique:team,team_name,'. $param['team_id'] .',team_id,is_deleted,0',
            'staff_title_id' => 'required',
            'department_id' => 'required',
            'staff_id' => 'required'
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'team_name.required' => __('Hãy nhập tên nhóm'),
            'team_name.max' => __('Tên nhóm tối đa 190 kí tự'),
            'team_name.unique' => __('Tên nhóm đã tồn tại'),
            'staff_title_id.required' => __('Hãy chọn chức vụ'),
            'department_id.required' => __('Hãy chọn thông tin nhánh cha'),
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
            'team_name' => 'strip_tags|trim',
            'staff_title_id' => 'strip_tags|trim',
            'department_id' => 'strip_tags|trim',
            'staff_id' => 'strip_tags|trim',
        ];
    }
}
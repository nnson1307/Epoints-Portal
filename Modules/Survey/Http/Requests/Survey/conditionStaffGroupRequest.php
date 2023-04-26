<?php

namespace Modules\Survey\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class conditionStaffGroupRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // TODO: Implement authorize() method.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "seleted_condition" => "required_without_all:listConditionDepartment,listConditionTitle,listConditionBranch",
            "typeCondition" => "required|in:or,and"

        ];
    }
    /**
     * Customize message
     *
     * @return array
     */
    public function messages()
    {
        return [
            "seleted_condition.required_without_all" => __("Vui lòng chọn điều kiện thêm nhân viên"),
            "typeCondition.required" => __("Vui lòng chọn điều kiện lọc"),
            "typeCondition.in"  => __("Loại điều kiện không tồn tại"),
        ];
    }
}

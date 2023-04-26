<?php

namespace Modules\ManagerProject\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()

    {
        $params = request()->all();
        $idProject = $params['manage_project_id'];
        $rules = [
            'manage_project_name' => "required|max:50|unique:manage_project,manage_project_name,$idProject,manage_project_id",
            'manage_project_id' => "required|exists:manage_project,manage_project_id",
//            'manager_id' => 'required',
            'department_id' => 'required',
//            'progress' => 'required',
            'manage_project_status_id' => 'required',
            'date_start'          => 'required|date_format:Y-m-d',
            'date_end'            => 'required|required_with:date_start|date_format:Y-m-d|after_or_equal:date_start'
        ];
        if ($params['date_start'] && !$params['date_end']) {
            $rules['date_end'] = 'required';
        }
        if ($params['date_end'] && !$params['date_start']) {
            $rules['date_start'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'manage_project_name.required' => __('Vui lòng nhập tên dự án'),
            'manage_project_name.max' => __('Nhập tối đa 50 kí tự'),
            'manage_project_name.unique' => __('Tên dự án đã tồn tại'),
            'manager_id.required' => __('Vui lòng chọn người quản trị'),
            'department_id.required' => __('Vui lòng chọn phòng ban trực thuộc'),
            'prefix_code.required' => __('Vui lòng tiền tố công việc'),
            'prefix_code.unique' => __('Tên tiền tố đã tồn tại'),
            'date_start.date_format' => __('Ngày bắt đầu hoạt động không đúng'),
            'date_end.date_format' => __('Ngày kết thúc hoạt động không đúng'),
            'date_end.after_or_equal' => __('Ngày bắt đầu phải nhỏ hơn ngày kết thúc hoạt động'),
            'manage_project_status_id.required' => __('Vui lòng chọn trạng thái dự án'),
            'date_start.required' => __('Vui lòng chọn ngày bắt đầu'),
            'date_end.required' => __('Vui lòng chọn ngày kết thúc'),
            'progress.required' => __('Vui lòng nhập tiến độ dự án'),

        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

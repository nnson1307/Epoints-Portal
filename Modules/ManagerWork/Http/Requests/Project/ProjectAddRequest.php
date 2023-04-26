<?php

namespace Modules\ManagerProject\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectAddRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $parent = $this->input('parent_id');
        return [
//            'staff' => 'required',
            'name' => 'required',
            'phone' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
//            'staff.required' => __('Vui lòng chọn nhân viên nhắc nhở'),
            // 'date_remind.required' => __('Vui lòng chọn thời gian nhắc'),
            'phone.required' => __('Vui lòng nhập phone'),
            'phone.integer' => __('Vui lòng nhập phone 111'),
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

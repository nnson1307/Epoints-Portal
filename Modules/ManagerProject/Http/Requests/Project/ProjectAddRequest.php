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
            'fullName' => 'required|max:50',
            'userName'=> 'required',
            'roomName'=> 'required',
            'statusName'=> 'required',
           
        ];
    }

    public function messages()
    {
        return [
//            'staff.required' => __('Vui lòng chọn nhân viên nhắc nhở'),
            'fullName.required' => __('Vui lòng chọn tên dự án'),
            'fullName.max' => __('Vượt quá 50 kí tự'),
            'userName.required' => __('Vui lòng chọn thời gian nhắc'),
            'roomName.required' => __('Vui lòng chọn thời gian nhắc'),
            'statusName.required' => __('Vui lòng chọn thời gian nhắc'),
            
            
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

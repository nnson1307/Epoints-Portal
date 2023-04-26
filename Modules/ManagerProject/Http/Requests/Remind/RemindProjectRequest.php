<?php

namespace Modules\ManagerProject\Http\Requests\Remind;

use Illuminate\Foundation\Http\FormRequest;

class RemindProjectRequest extends FormRequest
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
            'staff' => 'required',
            'date_remind' => 'required',
            'description_remind' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'staff.required' => __('Vui lòng chọn nhân viên nhắc nhở'),
            'date_remind.required' => __('Vui lòng chọn thời gian nhắc'),
            'description_remind.required' => __('Vui lòng nhập nội dung nhắc nhở'),
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

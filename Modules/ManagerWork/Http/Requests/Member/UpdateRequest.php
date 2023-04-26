<?php

namespace Modules\ManagerWork\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => 'required',
            'role' => 'required',
            'projectStaffId' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user.required' => __('Vui lòng chọn nhân viên'),
            'role.required' => __('Vui lòng chọn vai trò')
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

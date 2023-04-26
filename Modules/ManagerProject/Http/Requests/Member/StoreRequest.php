<?php

namespace Modules\ManagerProject\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'listUser' => 'required',
            'role' => 'required',
            'idProject' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'listUser.required' => __('Vui lòng chọn nhân viên'),
            'role.required' => __('Vui lòng chọn vai trò'),
            'idProject.required' => __('Vui lòng chọn dự án')   

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

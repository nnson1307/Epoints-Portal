<?php

namespace Modules\ManagerWork\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class MoveFileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_folder_change' => 'required',
            'new_folder_display_name' => 'required|max:191',
        ];
    }

    public function messages()
    {
        return [
            'name_folder_change.required' => __('Vui lòng chọn thư mục'),
            'new_folder_display_name.required' => __('Tên tài liệu không được để trống'),
            'new_folder_display_name.max:' => __('Tên tài liệu vượt quá 191 kí tự'),
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

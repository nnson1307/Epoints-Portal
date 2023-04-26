<?php

namespace Modules\Admin\Http\Requests\StaffCommission;

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
        return [
            'staff_id' => 'required',
            'staff_money' => 'required',
            'note' => 'nullable|max:191',
        ];
    }

    public function messages()
    {
        return [
            'staff_id.required' => __('Vui lòng chọn nhân viên.'),
            'staff_money.required' => __('Vui lòng nhập tiền hoa hồng.'),
            'note.max' => __('Ghi chú không được quá 191 ký tự.'),
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

        ];
    }
}
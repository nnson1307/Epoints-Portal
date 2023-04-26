<?php

namespace Modules\Warranty\Http\Requests\Repair;

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
            'object_type' => 'required',
            'object_id' => 'required',
            'object_status' => 'nullable|max:191',
            'staff_id' => 'required',
            'repair_date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'object_type.required' => __('Hãy chọn loại đối tượng'),
            'object_id.required' => __('Hãy chọn đối tượng'),
            'object_status.max' => __('Tình trạng đối tượng tối đa 191 kí tự'),
            'staff_id.required' => __('Hãy chọn nhân viên đưa đi bảo dưỡng'),
            'repair_date.required' => __('Hãy chọn ngày đưa đi bảo dưỡng'),
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
            'object_type' => 'strip_tags|trim',
            'object_id' => 'strip_tags|trim',
            'object_status' => 'strip_tags|trim',
            'staff_id' => 'strip_tags|trim',
            'repair_date' => 'strip_tags|trim',
            'repair_content' => 'strip_tags|trim',
        ];
    }
}
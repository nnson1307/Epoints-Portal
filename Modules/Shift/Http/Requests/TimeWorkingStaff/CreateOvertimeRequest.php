<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/10/2022
 * Time: 16:31
 */

namespace Modules\Shift\Http\Requests\TimeWorkingStaff;

use Illuminate\Foundation\Http\FormRequest;

class CreateOvertimeRequest extends FormRequest
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
            'branch_id' => 'required',
            'timekeeping_coefficient' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'branch_id.required' => __('Hãy chọn chi nhánh'),
            'timekeeping_coefficient.required' => __('Hãy nhập hệ số công'),
            'time_start.required' => __('Hãy nhập thời gian bắt đầu làm việc'),
            'time_end.required' => __('Hãy nhập thời gian kết thúc làm việc')
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
            'branch_id' => 'strip_tags|trim',
            'timekeeping_coefficient' => 'strip_tags|trim',
            'time_start' => 'strip_tags|trim',
            'time_end' => 'strip_tags|trim'
        ];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/10/2022
 * Time: 17:23
 */

namespace Modules\Shift\Http\Requests\TimeWorkingStaff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeWorkingRequest extends FormRequest
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
            'min_time_work' => 'required',
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
            'min_time_work.required' => __('Hãy nhập số giờ làm'),
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
            'min_time_work' => 'strip_tags|trim',
            'timekeeping_coefficient' => 'strip_tags|trim',
            'time_start' => 'strip_tags|trim',
            'time_end' => 'strip_tags|trim'
        ];
    }
}
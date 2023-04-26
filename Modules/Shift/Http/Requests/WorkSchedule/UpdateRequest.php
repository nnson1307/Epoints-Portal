<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/04/2022
 * Time: 09:53
 */

namespace Modules\Shift\Http\Requests\WorkSchedule;

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
        $params = request()->all();

        return [
            'work_schedule_name' => 'required|max:190|unique:sf_work_schedules,work_schedule_name,'. $params['work_schedule_id'] .',work_schedule_id,is_deleted,0',
            'start_day_shift' => 'required',
            'end_day_shift' => 'required'
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'work_schedule_name.required' => __('Hãy nhập tên lịch làm việc'),
            'work_schedule_name.max' => __('Tên lịch làm việc tối đa 190 kí tự'),
            'work_schedule_name.unique' => __('Tên lịch làm việc đã tồn tại'),
            'start_day_shift.required' => __('Hãy chọn ngày bắt đầu'),
            'end_day_shift.required' => __('Hãy chọn ngày kết thúc'),
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
            'promotion_name' => 'strip_tags|trim',
            'start_day_shift' => 'strip_tags|trim',
            'end_day_shift' => 'strip_tags|trim',
        ];
    }
}
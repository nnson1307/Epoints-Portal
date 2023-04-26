<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\Shift\Http\Requests\Shift;


use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'shift_name' => 'required|max:50|unique:sf_shifts,shift_name,'.',shift_id,is_deleted,0',
            'start_work_time' => 'required',
            'end_work_time' => 'required',
            'min_time_work' => 'required',
            'branch_id' => 'required',
            'timekeeping_coefficient' => 'required|'
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'shift_name.required' => __('Hãy nhập tên ca'),
            'shift_name.max' => __('Tên ca tối đa 50 kí tự'),
            'shift_name.unique' => __('Tên ca đã tồn tại'),
            'start_work_time.required' => __('Hãy nhập thời gian bắt đầu làm việc'),
            'end_work_time.required' => __('Hãy nhập thời gian kết thúc làm việc'),
            'min_time_work.required' => __('Hãy nhập số giờ làm'),
            'branch_id.required' => __('Hãy chọn chi nhánh'),
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
            'shift_name' => 'strip_tags|trim',
            'shift_type' => 'strip_tags|trim',
            'start_work_time' => 'strip_tags|trim',
            'end_work_time' => 'strip_tags|trim',
            'start_lunch_break' => 'strip_tags|trim',
            'end_lunch_break' => 'strip_tags|trim',
            'branch_id' => 'strip_tags|trim'
        ];
    }
}
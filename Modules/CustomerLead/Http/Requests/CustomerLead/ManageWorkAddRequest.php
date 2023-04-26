<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\CustomerLead\Http\Requests\CustomerLead;


use Illuminate\Foundation\Http\FormRequest;

class ManageWorkAddRequest extends FormRequest
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

        $booking = $this->input('is_booking');
        if (isset($booking) && $booking == 1){
            return [
                'manage_work_title' => 'required|max:255',
                'manage_type_work_id' => 'required',
                'content' => 'required',
                'date_start' => 'required',
                'date_end' => 'required',
                'processor_id' => 'required',
                'manage_status_id' => 'required',
            ];
        } else {
            return [
                'manage_work_title' => 'required|max:255',
                'manage_type_work_id' => 'required',
                'content' => 'required',
            ];
        }

    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'manage_work_title.required' => __('Hãy nhập tiêu đề công việc'),
            'manage_type_work_id.required' => __('Hãy chọn loại công việc'),
            'manage_work_title.max' => __('Tiêu đề công việc vượt quá 255 ký tự'),
            'content.required' => __('Hãy nhập nội dung công việc'),
            'date_start.required' => __('Hãy chọn thời gian bắt đầu công việc'),
            'date_end.required' => __('Hãy chọn thời gian kết thúc công việc'),
            'processor_id.required' => __('Hãy chọn nhân viên thực hiện'),
            'manage_status_id.required' => __('Hãy chọn trạng thái công việc'),

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
            'manage_work_title' => 'strip_tags|trim',
        ];
    }
}
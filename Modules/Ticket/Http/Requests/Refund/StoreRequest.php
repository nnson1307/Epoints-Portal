<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/24/2019
 * Time: 10:52 AM
 */

namespace Modules\Ticket\Http\Requests\Refund;

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
            'staff_id' => 'required|numeric',
            'approve_id' => 'required|numeric',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'staff_id.required' => __('Vui lòng chọn nhân viên'),
            'staff_id.numeric' => __('?'),
            'approve_id.required' => __('Vui lòng chọn người duyệt'),
            'approve_id.numeric' => __('?'),
        ];
    }
}
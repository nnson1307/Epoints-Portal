<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/10/2022
 * Time: 16:00
 */

namespace Modules\StaffSalary\Http\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;

class AjaxCreateRequest extends FormRequest
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
            'staff_salary_template_name' => 'required|max:190|unique:staff_salary_templates,staff_salary_template_name,'.',staff_salary_template_id,is_deleted,0',
            'staff_salary_pay_period_code' => 'required',
            'payment_type' => 'required',
            'staff_salary_type_code' => 'required',
            'staff_salary_unit_code' => 'required',
            'salary_default' => 'required'

        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'staff_salary_template_name.required' => __('Hãy nhập tên mẫu lương'),
            'staff_salary_template_name.max' => __('Tên mẫu lương tối đa 190 kí tự'),
            'staff_salary_template_name.unique' => __('Tên mẫu lương đã tồn tại'),
            'staff_salary_pay_period_code.required' => __('Hãy chọn kỳ hạn trả lương'),
            'payment_type.required' => __('Hãy chọn hình thức trả lương'),
            'staff_salary_type_code.required' => __('Hãy chọn loại lương'),
            'staff_salary_unit_code.required' => __('Hãy chọn đơn vị tiền tệ'),
            'salary_default.required' => __('Hãy nhập mức lương')
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
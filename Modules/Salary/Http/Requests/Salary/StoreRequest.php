<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/24/2019
 * Time: 10:52 AM
 */

namespace Modules\Salary\Http\Requests\Salary;

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
            'salary_period' => 'required',
            'time' => 'required',
            'name' => 'required|max:191',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'salary_period.required' => 'Vui lòng chọn kỳ lương',
            'time.required' => 'Vui lòng chọn thời gian',
            'name.required' => 'Vui lòng nhập tên bảng lương',
            'name.max' => 'Tên bảng lương không quá 191 ký tự',
        ];
    }
}
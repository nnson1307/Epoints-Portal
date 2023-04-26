<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/24/2019
 * Time: 10:52 AM
 */

namespace Modules\Salary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditSalaryRequest extends FormRequest
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
            'name' => 'required|max:191',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'name.required' => __('Tên bảng lương là trường bắt buộc phải nhập'),
            'name.max' => __('Tên bảng lương vượt quá 191 ký tự'),
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
            'name' => 'strip_tags|trim',
        ];
    }
}
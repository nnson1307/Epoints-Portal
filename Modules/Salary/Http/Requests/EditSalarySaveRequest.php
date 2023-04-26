<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/24/2019
 * Time: 10:52 AM
 */

namespace Modules\Salary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditSalarySaveRequest extends FormRequest
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
            'note' => 'max:191',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'note.max' => __('Ghi chú vượt quá 191 ký tự'),
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
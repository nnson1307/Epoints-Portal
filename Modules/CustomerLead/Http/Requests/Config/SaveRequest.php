<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\CustomerLead\Http\Requests\Config;


use Illuminate\Foundation\Http\FormRequest;

class SaveRequest extends FormRequest
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
            'link' => 'required',
            'team_marketing_id' => 'required',
            'department_id' => 'required',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'link.required' => __('Hãy nhập link google sheet'),
            'team_marketing_id.required' => __('Hãy chọn team marketing'),
            'department_id.required' => __('Hãy chọn phòng ban'),
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
            'full_name' => 'strip_tags|trim',
        ];
    }
}
<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/11/2020
 * Time: 9:53 AM
 */

namespace Modules\Admin\Http\Requests\OrderApp;


use Illuminate\Foundation\Http\FormRequest;

class SyncOrderRequest  extends FormRequest
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
            'number_time' => 'required|min:1|integer',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'number_time.required' => __('Hãy nhập số giờ đồng bộ'),
            'number_time.min' => __('Số giờ đồng bộ tối thiểu 1'),
            'number_time.integer' => __('Số giờ đồng bộ không hợp lệ')
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
<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/11/2020
 * Time: 2:04 AM
 */

namespace Modules\Promotion\Http\Requests\Promotion;


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
            'promotion_name' => 'required|max:250|unique:promotion_master,promotion_name,'.',promotion_id,is_deleted,0',
            'description' => 'max:250',
            'start_date' => 'required',
            'end_date' => 'required'
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'promotion_name.required' => __('Hãy nhập tên chương trình'),
            'promotion_name.max' => __('Tên chương trình tối đa 250 kí tự'),
            'promotion_name.unique' => __('Tên chương trình đã tồn tại'),
            'description.max' => __('Mô tả ngắn tối đa 250 kí tự'),
            'start_date.required' => __('Hãy chọn ngày bắt đầu'),
            'end_date.required' => __('Hãy chọn ngày kết thúc'),
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
            'promotion_name_en' => 'strip_tags|trim',
            'description' => 'strip_tags|trim',
            'start_date' => 'strip_tags|trim',
            'end_date' => 'strip_tags|trim',
        ];
    }
}
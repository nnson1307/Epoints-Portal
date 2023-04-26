<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/13/2020
 * Time: 10:54 AM
 */

namespace Modules\FNB\Http\Requests\Promotion;


use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $param = request()->all();

        return [
            'promotion_name_en' => 'required|max:250|unique:promotion_master,promotion_name_en,'.$param['promotion_id'].',promotion_id,is_deleted,0',
            'description_en' => 'max:250',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'promotion_name_en.required' => __('Hãy nhập tên chương trình (EN)'),
            'promotion_name_en.max' => __('Tên chương trình (EN) tối đa 250 kí tự'),
            'promotion_name_en.unique' => __('Tên chương trình (EN) đã tồn tại'),
            'description_en.max' => __('Mô tả ngắn (EN) tối đa 250 kí tự'),
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
            'promotion_name_en' => 'strip_tags|trim',
            'description' => 'strip_tags|trim',
        ];
    }
}
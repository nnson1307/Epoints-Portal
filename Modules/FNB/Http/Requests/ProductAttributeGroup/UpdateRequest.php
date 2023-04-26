<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/13/2020
 * Time: 10:54 AM
 */

namespace Modules\FNB\Http\Requests\ProductAttributeGroup;


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
            'product_attribute_group_name_en' => 'required|max:250|unique:product_attribute_groups,product_attribute_group_name_en,'.$param['product_attribute_group_id'].',product_attribute_group_id,is_deleted,0',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'product_attribute_group_name_en.required' => __('Vui lòng nhập tên nhóm thuộc tính'),
            'product_attribute_group_name_en.max' => __('Tên nhóm thuộc tính tối đa 250 kí tự'),
            'product_attribute_group_name_en.unique' => __('Nhóm thuộc tính đã tồn tại'),
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
            'product_attribute_group_name_en' => 'strip_tags|trim',
        ];
    }
}
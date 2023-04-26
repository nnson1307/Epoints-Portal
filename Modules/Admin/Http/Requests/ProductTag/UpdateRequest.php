<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 15:04
 */

namespace Modules\Admin\Http\Requests\ProductTag;

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
            'tag_name' => 'required|max:190|unique:product_tags,name,'.$param['product_tag_id'].',product_tag_id,is_deleted,0',
        ];
    }

    public function messages()
    {
        return [
            'tag_name.required' => __('Hãy nhập tên tag'),
            'tag_name.max' => __('Tên tag tối đa 190 kí tự'),
            'tag_name.unique' => __('Tên tag đã tồn tại')
        ];
    }
}
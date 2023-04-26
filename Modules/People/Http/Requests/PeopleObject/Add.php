<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\People\Http\Requests\PeopleObject;


use Illuminate\Foundation\Http\FormRequest;

class Add extends FormRequest
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
            'name' => 'required|max:191|min:4|unique:people_object,name,0,people_object_id,is_deleted,0',
            'code' => 'required|max:10|min:1|unique:people_object,code,0,people_object_id,is_deleted,0',
            'people_object_group_id' => 'required',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'name.required' => __('Hãy nhập tên đối tượng'),
            'name.unique' => __('Tên đối tượng đã tồn tại'),
            'name.max' => __('Tên đối tượng có tối đa 191 kí tự'),
            'name.min' => __('Tên đối tượng có tối thiểu 4 kí tự'),
            'code.required' => __('Hãy nhập mã đối tượng'),
            'code.unique' => __('Mã đối tượng đã tồn tại'),
            'code.max' => __('Mã đối tượng có tối đa 10 kí tự'),
            'code.min' => __('Mã đối tượng có tối thiểu 1 kí tự'),
            'people_object_group_id.required' => __('Hãy chọn nhóm đối tượng'),
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
            'people_object_group_id' => 'strip_tags|trim',
            'code' => 'strip_tags|trim',
        ];
    }
}
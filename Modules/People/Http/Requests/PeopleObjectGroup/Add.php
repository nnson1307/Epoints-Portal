<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\People\Http\Requests\PeopleObjectGroup;


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
            'name' => 'required|max:191|min:5|unique:people_object_group,name,0,people_object_group_id,is_deleted,0',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'name.required' => __('Hãy nhập tên nhóm đối tượng'),
            'name.unique' => __('Tên nhóm đối tượng đã tồn tại'),
            'name.max' => __('Tên nhóm đối tượng có tối đa 191 kí tự'),
            'name.min' => __('Tên nhóm đối tượng có tối thiểu 5 kí tự'),
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
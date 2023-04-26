<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\People\Http\Requests\PeopleObjectGroup;


use Illuminate\Foundation\Http\FormRequest;

class Edit extends FormRequest
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
        $people_object_group_id = request()->people_object_group_id;
        return [
            'people_object_group_id' => 'required',
            'name' => "required|max:191|min:5|unique:people_object_group,name,{$people_object_group_id},people_object_group_id,is_deleted,0",
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'people_object_group_id.required' => __('error people_object_group_id null'),
            'name.unique' => __('Tên nhóm đối tượng đã tồn tại'),
            'name.max' => __('Tên nhóm đối tượng có tối đa 191 kí tự'),
            'name.min' => __('Tên nhóm đối tượng có tối thiểu 5 kí tự'),
            'name.required' => __('Hãy nhập Tên nhóm đối tượng'),
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
            'people_object_group_id' => 'strip_tags|trim',
            'name' => 'strip_tags|trim',
        ];
    }
}
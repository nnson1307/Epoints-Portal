<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\People\Http\Requests\PeopleObject;


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
        $people_object_id = request()->people_object_id;
        $code = request()->code;
        return [
            'people_object_id' => 'required',
            'name' => "max:191|min:4|unique:people_object,name,{$people_object_id},people_object_id,is_deleted,0",
            'code' => "max:10|min:1|unique:people_object,code,{$code},code,is_deleted,0",
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'people_object_id.required' => __('error people_object_id null'),
            'name.unique' => __('Tên đối tượng đã tồn tại'),
            'name.max' => __('Tên đối tượng có tối đa 191 kí tự'),
            'name.min' => __('Tên đối tượng có tối thiểu 4 kí tự'),
            'code.unique' => __('Mã đối tượng đã tồn tại'),
            'code.max' => __('Mã đối tượng có tối đa 10 kí tự'),
            'code.min' => __('Mã đối tượng có tối thiểu 1 kí tự'),
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
            'people_object_id' => 'strip_tags|trim',
            'name' => 'strip_tags|trim',
            'code' => 'strip_tags|trim',
        ];
    }
}
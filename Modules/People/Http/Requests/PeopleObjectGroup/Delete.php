<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\People\Http\Requests\PeopleObjectGroup;


use Illuminate\Foundation\Http\FormRequest;

class Delete extends FormRequest
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
            'people_object_group_id' => 'required',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'people_object_group_id.required' => __('error people_object_group_id null'),
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
        ];
    }
}
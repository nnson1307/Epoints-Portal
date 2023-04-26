<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/23/2020
 * Time: 1:58 PM
 */

namespace Modules\CustomerLead\Http\Requests\PipelineCategory;


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
            'pipeline_category_name' => 'required|max:250|unique:cpo_pipeline_categories,pipeline_category_name,'.
                $param['pipeline_category_id'].',pipeline_category_id,is_deleted,0',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'pipeline_category_name.required' => __('Hãy nhập tên danh mục pipeline'),
            'pipeline_category_name.max' => __('Tên danh mục pipeline tối đa 250 kí tự'),
            'pipeline_category_name.unique' => __('Tên danh mục pipeline đã tồn tại')
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
            'pipeline_category_name' => 'strip_tags|trim'
        ];
    }
}
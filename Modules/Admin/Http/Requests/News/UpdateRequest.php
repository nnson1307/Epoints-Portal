<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/28/2020
 * Time: 6:20 PM
 */

namespace Modules\Admin\Http\Requests\News;


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
            'title_vi' => 'required|max:250|unique:news,title_vi,'. $param['new_id'] .',new_id,is_deleted,0',
            'title_en' => 'required|max:250|unique:news,title_en,'. $param['new_id'] .',new_id,is_deleted,0',
            'description_vi' => 'required|max:250',
            'description_en' => 'required|max:250',
        ];
    }

    public function messages()
    {
        return [
            'title_vi.required' => __('Hãy nhập tiêu đề VI'),
            'title_vi.max' => __('Tiêu đề VI tối đa 250 kí tự'),
            'title_vi.unique' => __('Tiêu đề VI đã tồn tại'),
            'title_en.required' => __('Hãy nhập tiêu đề EN'),
            'title_en.max' => __('Tiêu đề EN tối đa 250 kí tự'),
            'title_en.unique' => __('Tiêu đề EN đã tồn tại'),
            'description_vi.required' => __('Hãy nhập nội dung VI'),
            'description_vi.max' => __('Nội dung VI tối đa 250 kí tự'),
            'description_en.required' => __('Hãy nhập nội dung EN'),
            'description_en.max' => __('Nội dung EN tối đa 250 kí tự'),
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
            'title_vi' => 'strip_tags|trim',
            'title_en' => 'strip_tags|trim',
            'description_vi' => 'strip_tags|trim',
            'description_en' => 'strip_tags|trim',
        ];
    }
}
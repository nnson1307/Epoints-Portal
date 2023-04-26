<?php
namespace Modules\CustomerLead\Http\Requests\Pipeline;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'pipeline_name' => 'required|max:250|unique:cpo_pipelines,pipeline_name,'.
                ',pipeline_id,pipeline_category_code,'.$param['pipeline_cat'].',is_deleted,0',
            'time_revoke_lead' => 'required|integer|min:0',
            'owner_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'pipeline_name.required' => __('Hãy nhập tên pipeline'),
            'pipeline_name.max' => __('Tên pipeline tối đa 250 kí tự'),
            'pipeline_name.unique' => __('Tên pipeline đã tồn tại trong danh mục'),
            'time_revoke_lead.required' => __('Hãy nhập số ngày'),
            'time_revoke_lead.integer' => __('Số ngày phải là số nguyên'),
            'time_revoke_lead.min' => __('Số ngày tối thiểu là 0'),
            'owner_id.required' => __('Hãy chọn chủ sở hữu'),
        ];
    }

}
<?php

namespace Modules\Admin\Http\Requests\FaqGroup;

use Illuminate\Foundation\Http\FormRequest;

class FaqGroupStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $parent = $this->input('parent_id');
        return [
            'faq_group_title_vi' => 'required|max:250|unique:faq_group,faq_group_title_vi,NULL,faq_group_id,is_deleted,0',
            'faq_group_title_en' => 'required|max:250|unique:faq_group,faq_group_title_en,NULL,faq_group_id,is_deleted,0',
            'faq_group_position' => 'integer|max:1000000|numeric|unique:faq_group,faq_group_position,'.
                'NULL,faq_group_id,parent_id,'.$parent.',is_deleted,0',
        ];
    }

    public function messages()
    {
        return [
            'faq_group_title_vi.required' => __('admin::validation.faq_group.faq_group_title_required_vi'),
            'faq_group_title_vi.max' => __('admin::validation.faq_group.faq_group_title_max_vi'),
            'faq_group_title_vi.unique' => __('admin::validation.faq_group.faq_group_title_unique_vi'),
            'faq_group_title_en.required' => __('admin::validation.faq_group.faq_group_title_required_en'),
            'faq_group_title_en.max' => __('admin::validation.faq_group.faq_group_title_max_en'),
            'faq_group_title_en.unique' => __('admin::validation.faq_group.faq_group_title_unique_en'),
            'faq_group_position.numeric' => __('admin::validation.faq_group.faq_group_position_number'),
            'faq_group_position.max' => __('admin::validation.faq_group.faq_group_position_max'),
            'faq_group_position.integer' => __('admin::validation.faq_group.faq_group_position_number'),
            'faq_group_position.unique' => __('admin::validation.faq_group.faq_group_position_unique'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}

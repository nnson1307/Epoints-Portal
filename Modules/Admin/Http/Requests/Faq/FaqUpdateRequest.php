<?php

namespace Modules\Admin\Http\Requests\Faq;

use Illuminate\Foundation\Http\FormRequest;

class FaqUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $faqGroup = $this->input('faq_group');
        $faqId = $this->input('faq_id');
        return [
            'faq_title_vi' => 'required|max:250|unique:faq,faq_title_vi,'.$faqId.',faq_id,faq_group,'.$faqGroup.',is_deleted,0',
            'faq_title_en' => 'required|max:250|unique:faq,faq_title_en,'.$faqId.',faq_id,faq_group,'.$faqGroup.',is_deleted,0',
            'faq_position' => 'integer|max:1000000|numeric|unique:faq,faq_position,'
                .$faqId.
                ',faq_id,faq_group,'
                .$faqGroup.
                ',is_deleted,0',
            'faq_group' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'faq_title_vi.required' => __('admin::validation.faq.faq_title_required_vi'),
            'faq_title_vi.max' => __('admin::validation.faq.faq_title_max_vi'),
            'faq_title_vi.unique' => __('admin::validation.faq.faq_title_unique_vi'),

            'faq_title_en.required' => __('admin::validation.faq.faq_title_required_en'),
            'faq_title_en.max' => __('admin::validation.faq.faq_title_max_en'),
            'faq_title_en.unique' => __('admin::validation.faq.faq_title_unique_en'),

            'faq_position.numeric' => __('admin::validation.faq.faq_position_number'),
            'faq_position.max' => __('admin::validation.faq.faq_group_position_max'),
            'faq_position.integer' => __('admin::validation.faq.faq_position_number'),
            'faq_position.unique' => __('admin::validation.faq.faq_position_unique'),
            'faq_group.required' => __('admin::validation.faq.faq_group_required'),
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

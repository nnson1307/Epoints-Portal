<?php

namespace Modules\Loyalty\Requests\Membership;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Loyalty\Models\LoyaltyMembershipTable;
use Illuminate\Support\Facades\DB;

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
        $rules = [
            'accumulation_program_name' => ['required', 'max:255'],
            'survey_id' => ['required'],
            'validity_period_type' => ['required'],
            'is_active' => ['required', 'in:0,1'],
            'apply_type' => ['required', 'in:rank,all'],
            'accumulation_point' => 'required_if:apply_type, ==,all',
            'valuePoint' => 'required_if:apply_type, ==,rank',
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'accumulation_program_name.required' => __('loyalty::validation.accumulate_point.name_required'),
            'accumulation_program_name.max' => __('loyalty::validation.accumulate_point.name_max'),

            'survey_id.required' => __('loyalty::validation.accumulate_point.survey_required'),
            'validity_period_type.required' => __('loyalty::validation.accumulate_point.validity_period_type_required'),
            'is_active.required' => __('loyalty::validation.accumulate_point.is_active_required'),
            'apply_type.required' => __('loyalty::validation.accumulate_point.apply_type_required')
        ];
        return $messages;
    }
}

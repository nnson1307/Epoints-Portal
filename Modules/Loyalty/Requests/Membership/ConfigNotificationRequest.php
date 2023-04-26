<?php

namespace Modules\Loyalty\Requests\Membership;


use Illuminate\Foundation\Http\FormRequest;
use Modules\Loyalty\Models\LoyaltyMembershipTable;
use Illuminate\Support\Facades\DB;

class ConfigNotificationRequest extends FormRequest
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
            'title_template' => ['required', 'max:255'],
            'message_template' => ['required'],
            'des_detail_template' => ['required'],
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'title_template.required' => __('loyalty::template_config_notifi.title_required'),
            'title_template.max' => __('loyalty::template_config_notifi.title_max_255'),

            'message_template.required' => __('loyalty::template_config_notifi.description_required'),
            'des_detail_template.required' => __('loyalty::template_config_notifi.description_detail_required'),

        ];
        return $messages;
    }
}

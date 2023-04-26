<?php

namespace Modules\Survey\Http\Requests\Survey;

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
        return true;
        // TODO: Implement authorize() method.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $params = request()->all();
        $rules = [
            'survey_name' => 'required',
            'survey_code' => 'required|regex:/(^[A-Za-z0-9]+$)+/|unique:survey,survey_code,' . $params['survey_id'] . ',survey_id,is_delete,0',
        ];
        // Thời gian hiệu lực chương trình
        if ($params['is_exec_time'] == 1) {
            $rules['start_date'] = 'required';
            $rules['end_date'] = 'required';
        }
        // Tần suất thực hiện khảo sát
        // Hàng tuần
        if ($params['frequency'] == 'weekly') {
            $rules['frequency_value_weekly'] = 'required';
        } elseif ($params['frequency'] == 'monthly') {
            // Hàng tháng
            // Lặp lại vào tháng
            $rules['frequency_value_monthly'] = 'required';
            if (!$params['frequency_monthly_type']) {
                $rules['frequency_monthly_type'] = 'required';
            } elseif ($params['frequency_monthly_type'] == 'day_in_month') {
                // Ngày trong tháng
                $rules['day_in_monthly'] = 'required';
            } elseif ($params['frequency_monthly_type'] == 'day_in_week') {
                // Ngày trong tuần
                // Lặp lại vào tuần
                $rules['day_in_week'] = 'required';
                // Lặp lại vào thứ
                $rules['day_in_week_repeat'] = 'required';
            }
        }
        // Thời gian thực hiện trong ngày
        if ($params['is_limit_exec_time'] == 1) {
            if (empty($params['exec_time_from'])) {
                $rules['exec_time_from'] = 'required';
            } else {
                if (empty($params['exec_time_to'])) {
                    $rules['exec_time_to'] = 'required';
                }
            }
        }
        // Số khảo sát trên mỗi cửa hàng tối đa -  Số khảo sát trên mỗi cửa hàng tối đa
        if (isset($params['config_turn']) && $params['config_turn'] == '1') {
            $rules['max_times'] = 'required|numeric|min:1';
        }
        return $rules;
    }
    /**
     * Customize message
     *
     * @return array
     */
    public function messages() {
        $params = request()->all();
        $messages = [
            'survey_name.required' => __('survey::validation.survey_name_required'),
            'survey_code.required' => __('survey::validation.survey_code_required'),
            'survey_code.regex' => __('survey::validation.survey_code_regex'),
            'survey_code.unique' => __('survey::validation.survey_code_unique'),
        ];
        // Thời gian hiệu lực chương trình
        if ($params['is_exec_time'] == 1) {
            $messages['start_date.required'] = __('survey::validation.start_date_required');
            $messages['end_date.required'] = __('survey::validation.end_date_required');
        }
        // Tần suất thực hiện khảo sát
        // Hàng tuần
        if ($params['frequency'] == 'weekly') {
            $messages['frequency_value_weekly.required'] = __('survey::validation.frequency_value_weekly_required');
        } elseif ($params['frequency'] == 'monthly') {
            // Hàng tháng
            // Lặp lại vào tháng
            $messages['frequency_value_monthly.required'] = __('survey::validation.frequency_value_monthly_required');
            if (!$params['frequency_monthly_type']) {
                $messages['frequency_monthly_type.required'] = __('survey::validation.frequency_monthly_type_required');
            } elseif ($params['frequency_monthly_type'] == 'day_in_month') {
                // Ngày trong tháng
                $messages['day_in_monthly.required'] = __('survey::validation.day_in_monthly_required');
            } elseif ($params['frequency_monthly_type'] == 'day_in_week') {
                // Ngày trong tuần
                // Lặp lại vào tuần
                $messages['day_in_week.required'] = __('survey::validation.day_in_week_required');
                // Lặp lại vào thứ
                $messages['day_in_week_repeat.required'] = __('survey::validation.day_in_week_repeat_required');
            }
        }
        // Thời gian thực hiện trong ngày
        if ($params['is_limit_exec_time'] == 1) {
            if (empty($params['exec_time_from'])) {
                $messages['exec_time_from.required'] = __('survey::validation.exec_time_from_required');
            } else {
                if (empty($params['exec_time_to'])) {
                    $messages['exec_time_to.required'] = __('survey::validation.exec_time_to_required');
                }
            }
        }
        // Số khảo sát trên mỗi cửa hàng tối đa -  Số khảo sát trên mỗi cửa hàng tối đa
        if (isset($params['config_turn']) && $params['config_turn'] == '1') {
            $messages['max_times.required'] = __('Vui lòng nhập số lượng khảo sát tối đa cho mỗi cửa hàng.');
            $messages['max_times.numeric'] = __('survey::validation.max_times_min');
            $messages['max_times.min'] = __('survey::validation.branch_max_times_min');
        }
        return $messages;
    }
}
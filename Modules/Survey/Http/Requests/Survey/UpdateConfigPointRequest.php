<?php

namespace Modules\Survey\Http\Requests\Survey;

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConfigPointRequest extends FormRequest
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
            'show_answer' => 'required|in:N,C,E',
            'idSurvey' => 'required|exists:survey,survey_id',
            'id' => 'required|exists:survey_config_point,id_config_point',
            'point_default' => 'required'
        ];
        // Cấu hình thời gian 
        if ($params['show_answer'] == 'C') {
            $rules['time_start'] = 'required';
            $rules['time_end'] = 'required';
        }
        return $rules;
    }
    /**
     * Customize message
     *
     * @return array
     */
    public function messages()
    {
        $params = request()->all();
        $messages = [
            'show_answer.required' => __('Vui lòng chọn hiển thị đáp'),
            'point_default.required' => __('Vui lòng chọn điểm')
        ];
        // Cấu hình thời gian 
        if ($params['show_answer'] == 'C') {
            $messages['time_start.required'] = __('Vui lòng chọn thời gian bắt đầu');
            $messages['time_end.required'] = __('Vui lòng chọn thời gian kết thúc');
        }

        return $messages;
    }
}

<?php

namespace Modules\Survey\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplyRequest extends FormRequest
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
            "typeApply" => 'required|in:staffs,customers,all_customer,all_staff',
            "survey_id" => 'required'
        ];
        if ($params['typeApply'] == 'staffs') {
            if ($params['countListStaff'] <= 0) {
                $rules['seleted_staff_apply'] = 'required_without_all:condition_branch,condition_department,condition_titile';
                $rules['type_condition'] = 'required|in:or,and';
            }
        }

        if (($params['typeApply'] == 'customers')) {
            if ($params['countListCustomer'] <= 0) {
                $rules['idGroupAutoCustomer'] = 'required';
            }
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
        $messgaes = [
            "typeApply.required" => __('Vui lòng chọn kiểu đối tương áp dụng'),
            "typeApply.in" => __('Kiểu đối tượng không tồn tại'),
            "survey_id.required" => __('Khảo sát không tồn tại')
        ];

        if ($params['typeApply'] == 'staffs') {
            if ($params['countListStaff'] <= 0) {
                $messgaes['seleted_staff_apply.required_without_all'] = __('Vui lòng đối tượng áp dụng');
                $messgaes['type_condition.required'] = __('Vui lòng kiểu đối tượng động');
                $messgaes['type_condition.in'] = __('Kiểu đối tượng động không tồn tại');
            }
        }
        if (($params['typeApply'] == 'customers')) {
            if ($params['countListCustomer'] <= 0) {
                $messgaes['idGroupAutoCustomer.required'] = __('Vui lòng chọn đối tượng áp dụng');
            }
        }
        return $messgaes;

    }
}

<?php

namespace Modules\Dashbroad\Http\Requests\Dashboard;

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
            'name_vi' => 'unique:dashboard,name_vi,'. $param['dashboard_id'] .',dashboard_id,is_deleted,0',
            'name_en' => 'unique:dashboard,name_en,'. $param['dashboard_id'] .',dashboard_id,is_deleted,0',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'name_vi.unique' => __('Tên bố cục (Tiếng Việt) là duy nhất'),
            'name_en.unique' => __('Tên bố cục (Tiếng Anh) là duy nhất'),
        ];
    }
}
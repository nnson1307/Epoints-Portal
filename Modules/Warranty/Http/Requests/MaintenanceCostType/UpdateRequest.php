<?php

namespace Modules\Warranty\Http\Requests\MaintenanceCostType;

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
            'maintenance_cost_type_name_vi' => 'required|max:191|unique:maintenance_cost_type,maintenance_cost_type_name_vi,'. $param['maintenance_cost_type_id'] .',maintenance_cost_type_id,is_delete,0',
            'maintenance_cost_type_name_en' => 'required|max:191|unique:maintenance_cost_type,maintenance_cost_type_name_en,'. $param['maintenance_cost_type_id'] .',maintenance_cost_type_id,is_delete,0',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'maintenance_cost_type_name_vi.required' => __('Hãy nhập tên loại chi phí phát sinh (Tiếng Việt)'),
            'maintenance_cost_type_name_vi.unique' => __('Tên loại chi phí phát sinh (Tiếng Việt) là duy nhất'),
            'maintenance_cost_type_name_vi.max' => __('Tên loại chi phí phát sinh (Tiếng Việt)  tối đa 191 ký tự'),
            'maintenance_cost_type_name_en.required' => __('Hãy nhập tên loại chi phí phát sinh (Tiếng Anh)'),
            'maintenance_cost_type_name_en.unique' => __('Tên loại chi phí phát sinh (Tiếng Anh) là duy nhất'),
            'maintenance_cost_type_name_en.max' => __('Tên loại chi phí phát sinh (Tiếng Anh)  tối đa 191 ký tự'),
        ];
    }
}
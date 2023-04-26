<?php

namespace Modules\Payment\Http\Requests\DiscountCauses;

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
            'discount_causes_name_vi' => 'required|max:191|unique:discount_causes,discount_causes_name_vi,'. $param['discount_causes_id'] .',discount_causes_id,is_delete,0',
            'discount_causes_name_en' => 'required|max:191|unique:discount_causes,discount_causes_name_en,'. $param['discount_causes_id'] .',discount_causes_id,is_delete,0',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'discount_causes_name_vi.required' => __('Hãy nhập tên loại lý do giảm giá (Tiếng Việt)'),
            'discount_causes_name_vi.unique' => __('Tên loại lý do giảm giá (Tiếng Việt) là duy nhất'),
            'discount_causes_name_vi.max' => __('Tên loại lý do giảm giá (Tiếng Việt) tối đa 191 ký tự'),
            'discount_causes_name_en.required' => __('Hãy nhập tên loại lý do giảm giá (Tiếng Anh)'),
            'discount_causes_name_en.unique' => __('Tên loại lý do giảm giá (Tiếng Anh) là duy nhất'),
            'discount_causes_name_en.max' => __('Tên loại lý do giảm giá (Tiếng Anh) tối đa 191 ký tự'),
        ];
    }
}
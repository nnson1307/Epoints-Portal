<?php

namespace Modules\Payment\Http\Requests\PaymentType;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'name_vi' => 'required|max:250',
            'name_en' => 'required|max:250'
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'name_vi.required' => __('Tên loại phiếu chi Tiếng Việt là bắt buộc'),
            'name_vi.max' => __('Tên loại phiếu chi Tiếng Việt không được quá 250 ký tự'),
            'name_en.required' => __('Tên loại phiếu chi Tiếng Anh là bắt buộc'),
            'name_en.max' => __('Tên loại phiếu chi Tiếng Anh không được quá 250 ký tự')
        ];
    }
}
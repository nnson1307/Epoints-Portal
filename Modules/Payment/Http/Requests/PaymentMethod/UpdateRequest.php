<?php

namespace Modules\Payment\Http\Requests\PaymentMethod;

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
            'payment_method_name_vi' => 'required|max:255|unique:payment_method,payment_method_name_vi,'. $param['payment_method_id'] .',payment_method_id,is_delete,0',
            'payment_method_name_en' => 'required|max:255|unique:payment_method,payment_method_name_en,'. $param['payment_method_id'] .',payment_method_id,is_delete,0',
            'payment_method_type' => 'required',
            'note' => 'nullable|max:190'
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'payment_method_code.unique' => __('Mã hình thức thanh toán là duy nhất'),
            'payment_method_name_vi.required' => __('Hãy nhập tên hình thức thanh toán (Tiếng Việt)'),
            'payment_method_name_vi.unique' => __('Tên hình thức thanh toán (Tiếng Việt) là duy nhất'),
            'payment_method_name_vi.max' => __('Tên hình thức thanh toán (Tiếng Việt)  không quá 255 kí tự'),
            'payment_method_name_en.required' => __('Hãy nhập tên hình thức thanh toán (Tiếng Anh)'),
            'payment_method_name_en.unique' => __('Tên hình thức thanh toán (Tiếng Anh) là duy nhất'),
            'payment_method_name_en.max' => __('Tên hình thức thanh toán (Tiếng Anh)  không quá 255 kí tự'),
            'payment_method_type.required' => __('Loại hình thức thanh toán là bắt buộc'),
            'note.max' => __('Ghi chú tối đa 190 kí tự')
        ];
    }
}
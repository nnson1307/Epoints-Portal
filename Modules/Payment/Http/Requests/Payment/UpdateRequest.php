<?php

namespace Modules\Payment\Http\Requests\Payment;

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
            'accounting_name' => 'max:255',
            'document_code' => 'max:20',
            'note' => 'max:255',
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'accounting_name.max' => __('Tên người nhận không được quá 255 ký tự'),
            'document_code.max' => __('Mã tham chiếu không được quá 20 ký tự'),
            'note.max' => __('Mô tả không được quá 255 ký tự'),
        ];
    }
}
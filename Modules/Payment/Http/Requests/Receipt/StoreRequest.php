<?php

namespace Modules\Payment\Http\Requests\Receipt;

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
            'objectAccountingName' => 'max:250',
            'note' => 'max:500'
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'objectAccountingName.max' => __('Tên người trả tiền không được quá 250 ký tự'),
            'note.max' => __('Nội dung thu không được quá 500 ký tự')
        ];
    }
}
<?php

namespace Modules\Contract\Http\Requests\Vat;

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
            'vat' => 'required|unique:vats,vat,'.',vat_id'
        ];
    }

    /**
     * function custom messages
     *
     */
    public function messages()
    {
        return [
            'vat.required' => __('Hãy nhập % VAT'),
            'vat.unique' => __('% VAT đã toồn tại'),
        ];
    }
}
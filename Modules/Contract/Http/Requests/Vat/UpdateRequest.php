<?php

namespace Modules\Contract\Http\Requests\Vat;

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
            'vat' => 'required|unique:vats,vat,'. $param['vat_id'] .',vat_id'
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
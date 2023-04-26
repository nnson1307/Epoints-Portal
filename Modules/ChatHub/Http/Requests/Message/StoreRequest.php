<?php

namespace Modules\ChatHub\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

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
            'email'=>'email|unique:cpo_customer_registers,email,'.request()->email.',email',
            'phone' => 'regex:/(0)[0-9]{9}/|numeric',
        ];
    }

    public function messages()
    {
        return [
            'email.email'=>__('chathub::message.index.NOT_EMAIL'),
            'email.unique'=> __('chathub::message.index.UNIQUE_EMAIL'),
            'phone.regex'=>__('chathub::message.index.NOT_PHONE'),
            'phone.numeric'=>__('chathub::message.index.NUMERIC_PHONE')       
        ];
    }
}

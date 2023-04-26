<?php

namespace Modules\ChatHub\Http\Requests\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
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
             'attribute_name' => 'required',
            'entities' => 'required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'attribute_name.required' => __('chathub::validation.attribute.create.NAME_REQUIRED'),
            'entities.required' => __('chathub::validation.attribute.create.STATUS_REQUIRED'),
        ];
    }
}

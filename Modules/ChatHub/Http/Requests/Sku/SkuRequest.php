<?php

namespace Modules\ChatHub\Http\Requests\Sku;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class SkuRequest extends FormRequest
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
             'sku_name' => 'required',
            'sku_status' => 'required',
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
            'sku_name.required' => __('chathub::validation.sku.create.NAME_REQUIRED'),
            'sku_status.required' => __('chathub::validation.sku.create.STATUS_REQUIRED'),
        ];
    }
}

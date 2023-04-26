<?php

namespace Modules\ChatHub\Http\Requests\SubBrand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class SubBrandRequest extends FormRequest
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
             'sub_brand_name' => 'required',
            'sub_brand_status' => 'required',
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
            'sub_brand_name.required' => __('chathub::validation.sub_brand.create.NAME_REQUIRED'),
            'sub_brand_status.required' => __('chathub::validation.sub_brand.create.STATUS_REQUIRED'),
        ];
    }
}

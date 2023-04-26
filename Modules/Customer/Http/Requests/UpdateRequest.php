<?php

namespace Modules\Customer\Http\Requests;

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
        return [
            'customer_info_type_name_vi' => 'required|max:191',
            'customer_info_type_name_en' => 'required|max:191'
        ];
    }

    public function messages()
    {
        return [
            'customer_info_type_name_vi.required' => __('Vui lòng nhập tên loại thông tin kèm theo tiếng Việt.'),
            'customer_info_type_name_en.required' => __('Vui lòng nhập tên loại thông tin kèm theo tiếng Anh.'),
            'customer_info_type_name_vi.max' => __('Tên loại thông tin kèm theo tiếng Việt tối đa 191 ký tự.'),
            'customer_info_type_name_en.max' => __('Tên loại thông tin kèm theo tiếng Anh tối đa 191 ký tự.')
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [

        ];
    }
}
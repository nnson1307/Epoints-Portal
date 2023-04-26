<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/10/2022
 * Time: 09:50
 */

namespace Modules\Commission\Http\Requests\Commission;

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
            'commission_name' => 'required|max:190|unique:commission,commission_name,'.',commission_id,is_deleted,0',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'commission_name.required' => __('Hãy nhập tên hoa hồng'),
            'commission_name.max' => __('Tên hoa hồng tối đa 190 kí tự'),
            'commission_name.unique' => __('Tên hoa hồng đã tồn tại'),
        ];
    }

}
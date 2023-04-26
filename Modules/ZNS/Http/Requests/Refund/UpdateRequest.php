<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 26/03/2018
 * Time: 6:27 CH
 */

namespace Modules\ZNS\Http\Requests\Refund;

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
        $id = $this->input('id');
        return [
            'name' => 'required|max:255|unique:customer_group_filter,name,' .$id. ',id',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên nhóm khách hàng',
            'name.max' => __('Tên nhóm khách hàng tối đa 255 ký tự'),
        ];
    }
}

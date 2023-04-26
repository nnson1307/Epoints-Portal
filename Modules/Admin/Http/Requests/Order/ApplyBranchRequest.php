<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-23
 * Time: 2:24 PM
 * @author SonDepTrai
 */

namespace Modules\Admin\Http\Requests\Order;


use Illuminate\Foundation\Http\FormRequest;

class ApplyBranchRequest extends FormRequest
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
            'branch_id' => 'required|integer',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'branch_id.integer' => __('Chi nhánh không hợp lệ.'),
            'branch_id.required' => __('Hãy chọn chi nhánh.'),
        ];
    }
}
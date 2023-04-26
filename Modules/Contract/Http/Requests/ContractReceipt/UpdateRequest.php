<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/09/2021
 * Time: 15:58
 */

namespace Modules\Contract\Http\Requests\ContractReceipt;

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
            'invoice_no' => 'nullable|max:190',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'invoice_no.max' => __('Số hoá đơn tối đa 190 kí tự'),
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
            'invoice_no' => 'strip_tags|trim',
            'note' => 'strip_tags|trim',
        ];
    }
}
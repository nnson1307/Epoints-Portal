<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/09/2021
 * Time: 17:23
 */

namespace Modules\Contract\Http\Requests\ContractReceipt;

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
            'content' => 'required',
            'collection_date' => 'required',
            'collection_by' => 'required',
            'payment_method_id' => 'required',
            'invoice_no' => 'nullable|max:190',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'content.required' => __('Nội dung không được trống'),
            'collection_date.required' => __('Hãy chọn ngày thu'),
            'collection_by.required' => __('Hãy chọn người thu'),
            'amount_receipt.required' => __('Giá trị thanh toán không được trống'),
            'payment_method_id.required' => __('Hãy chọn phương thức thanh toán'),
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
            'content' => 'strip_tags|trim',
            'collection_date' => 'strip_tags|trim',
            'collection_by' => 'strip_tags|trim',
            'payment_method_id' => 'strip_tags|trim',
            'invoice_no' => 'strip_tags|trim',
            'note' => 'strip_tags|trim',
        ];
    }
}
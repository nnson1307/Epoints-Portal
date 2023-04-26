<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/11/2020
 * Time: 4:35 PM
 */

namespace Modules\Booking\Http\Requests\Upload;


use Illuminate\Foundation\Http\FormRequest;

class UploadImageDropRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image_drop' => 'required',
            'delivery_history_id' => 'integer|required',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'image_drop.required' => __('Hãy chọn ảnh giao hàng'),
            'delivery_history_id.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'delivery_history_id.required' => __('Hãy nhập mã giao hàng.')
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
            'image_drop' => 'strip_tags|trim',
            'delivery_history_id' => 'strip_tags|trim'
        ];
    }
}
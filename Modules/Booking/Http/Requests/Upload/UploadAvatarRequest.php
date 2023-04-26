<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 3:43 PM
 */

namespace Modules\Booking\Http\Requests\Upload;


use Illuminate\Foundation\Http\FormRequest;

class UploadAvatarRequest extends FormRequest
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
        $param = request()->all();

        return [
            'customer_avatar' => 'required',
            'customer_id' => 'integer|required',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'customer_avatar.required' => __('Hãy chọn ảnh đại diện'),
            'customer_id.integer' => __('Kiểu dữ liệu không hợp lệ.'),
            'customer_id.required' => __('Hãy nhập mã khách hàng.')
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
            'customer_avatar' => 'strip_tags|trim',
            'customer_id' => 'strip_tags|trim'
        ];
    }
}
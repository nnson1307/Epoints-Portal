<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 1:46 PM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Http\Requests\Delivery;


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
//        return [
//            'contact_name' => 'required|max:250',
//            'contact_phone' => 'required|digits:10',
//            'contact_address' => 'required|max:250',
//            'transport_code' => 'nullable|max:250',
////            'amount' => 'required',
//            'note' => 'nullable|max:250',
//            'pick_up' => 'required|max:250',
////            'delivery_staff' => 'required'
//        ];

        $shipping_unit = $this->input('shipping_unit');
        if ($shipping_unit == 'delivery_unit'){
            return [
                'contact_name' => 'required|max:191',
                'contact_phone' => 'required|digits:10',
                'contact_address' => 'required|max:191',
                'transport_code' => 'nullable|max:191',
//            'amount' => 'required',
                'note' => 'nullable|max:250',
                'pick_up' => 'required|max:250',
//            'delivery_staff' => 'required',
                'province_id' => 'required',
                'district_id' => 'required',
                'ward_id' => 'required',
                'weight' => 'required',
                'length' => 'required',
                'width' => 'required',
                'height' => 'required',
            ];
        } else {
            return [
                'contact_name' => 'required|max:191',
                'contact_phone' => 'required|digits:10',
                'contact_address' => 'required|max:191',
                'transport_code' => 'nullable|max:191',
//            'amount' => 'required',
                'note' => 'nullable|max:250',
                'pick_up' => 'required|max:250',
//            'delivery_staff' => 'required',
                'province_id' => 'required',
                'district_id' => 'required',
                'ward_id' => 'required',
                'delivery_staff' => 'required',
            ];
        }

    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'contact_name.required' => __('Hãy nhập người nhận'),
            'contact_name.max' => __('Người nhận tối đa 250 kí tự'),
            'contact_phone.required' => __('Hãy nhập số điện thoại người nhận'),
            'contact_phone.max' => __('Số điện thoại người nhận tối đa 10 kí tự'),
            'contact_phone.digits' => __('Số điện thoại người nhận không hợp lệ'),
            'contact_address.required' => __('Hãy nhập địa chỉ người nhận'),
            'contact_address.max' => __('Địa chỉ người nhận tối đa 250 kí tự'),
            'transport_code.max' => __('Hình thức giao tối đa 250 kí tư'),
            'amount.required' => __('Hãy nhập số tiền cần thu'),
            'note.max' => __('Ghi chú tối đa 250 kí tư'),
            'pick_up.required' => __('Hãy nhập nơi lấy hàng'),
            'pick_up.max' => __('Nơi lấy hàng tối đa 250 kí tự'),
            'delivery_staff.required' => __('Hãy chọn nhân viên giao hàng'),
            'province_id.required' => __('Hãy chọn Tỉnh/thành phố'),
            'district_id.required' => __('Hãy chọn Quận/huyện'),
            'ward_id.required' => __('Hãy chọn Phường/xã'),
            'weight.required' => __('Hãy nhập trọng lượng'),
            'length.required' => __('Hãy nhập chiều dài'),
            'width.required' => __('Hãy nhập chiều rộng'),
            'height.required' => __('Hãy nhập chiều cao'),
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
            'contact_name' => 'strip_tags|trim',
            'contact_phone' => 'strip_tags|trim',
            'contact_address' => 'strip_tags|trim',
            'transport_code' => 'strip_tags|trim',
            'note' => 'strip_tags|trim',
            'amount' => 'strip_tags|trim',
            'pick_up' => 'strip_tags|trim',
            'delivery_staff' => 'strip_tags|trim',
        ];
    }
}
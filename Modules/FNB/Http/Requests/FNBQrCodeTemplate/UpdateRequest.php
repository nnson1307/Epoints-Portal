<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/13/2020
 * Time: 10:54 AM
 */

namespace Modules\FNB\Http\Requests\FNBQrCodeTemplate;


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
        $param = request()->all();
        $data = [];

        if (isset($param['is_request_location'])){
            $data['location_lat'] = 'required|max:191';
            $data['location_lng'] = 'required|max:191';
            $data['location_radius'] = 'required|max:191';
        }

        if (isset($param['is_request_wifi'])){
            $data['wifi_name'] = 'required|max:191';
            $data['wifi_ip'] = 'required|max:191';
        }

        return $data;
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'location_lat.required' => __('Vui lòng nhập kinh độ'),
            'location_lng.required' => __('Vui lòng nhập vĩ độ'),
            'location_radius.required' => __('Vui lòng nhập bán kính cho phép'),
            'wifi_name.required' => __('Vui lòng nhập tên wifi'),
            'wifi_ip.required' => __('Vui lòng nhập địa chỉ IP'),
            'location_lat.max' => __('Kinh độ vượt quá 191 ký tự'),
            'location_lng.max' => __('Vĩ độ vượt quá 191 ký tự'),
            'location_radius.max' => __('Bán kính cho phép vượt quá 191 ký tự'),
            'wifi_name.max' => __('Tên wifi vượt quá 191 ký tự'),
            'wifi_ip.max' => __('Địa chỉ IP vượt quá 191 ký tự'),
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
            'location_lat' => 'strip_tags|trim',
            'location_lng' => 'strip_tags|trim',
            'location_radius' => 'strip_tags|trim',
            'wifi_name' => 'strip_tags|trim',
            'wifi_ip' => 'strip_tags|trim',
        ];
    }
}
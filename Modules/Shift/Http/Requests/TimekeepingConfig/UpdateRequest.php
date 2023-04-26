<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\Shift\Http\Requests\TimekeepingConfig;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $params = request()->all();

        return [
            'wifi_name' => [
                'required_if:timekeeping_type,==,wifi',
                'max:190',
                'unique:sf_timekeeping_config,wifi_name,'.$params['timekeeping_config_id'].',timekeeping_config_id,is_deleted,0,timekeeping_type,wifi'
            ],
            'wifi_ip' => 'required_if:timekeeping_type,==,wifi|max:190',
            'branch_id' => 'required',
            'timekeeping_type' => [
                'required',
                Rule::in(['wifi', 'gps']),
            ],
            'latitude' => 'required_if:timekeeping_type,==,gps',
            'longitude' => 'required_if:list_type,==,gps',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'wifi_name.required' => __('Hãy nhập tên wifi'),
            'wifi_name.max' => __('Tên wifi tối đa 190 kí tự'),
            'wifi_name.unique' => __('Tên wifi đã tồn tại'),
            'wifi_ip.required' => __('Hãy nhập địa chỉ ip'),
            'wifi_ip.max' => __('Địa chỉ ip tối đa 190 kí tự'),
            'branch_id.required' => __('Hãy chọn chi nhánh'),
            'timekeeping_type.in' => __('Hình thức chấm công không hợp lệ'),
            'latitude.required_if' => __('Hãy nhập kinh độ'),
            'longitude.required_if' => __('Hãy nhập vĩ độ')
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
            'wifi_name' => 'strip_tags|trim',
            'wifi_ip' => 'strip_tags|trim',
            'branch_id' => 'strip_tags|trim',
            'timekeeping_type' => 'strip_tags|trim',
            'latitude' => 'strip_tags|trim',
            'longitude' => 'strip_tags|trim',
        ];
    }
}
<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\People\Http\Requests\People;


use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class Add extends FormRequest
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
        $arrValidate = [
            'full_name' => 'required|max:50|min:1',
            'code' => 'required|max:15|unique:people,code',
            'birthday' => 'required|date_format:d/m/Y|before:now',
            'gender' => 'required',
            'temporary_address' => 'required|max:191',
            'permanent_address' => 'required|max:191',
            'id_number' => 'nullable|min:9|max:12|regex:/^[0-9]+$/|unique:people,id_number',
            'hometown' => 'required|max:191',
            'birthplace' => 'nullable|max:191',
            'ethnic_id' => 'required',
            'elementary_school' => 'nullable',
            'middle_school' => 'nullable',
            'high_school' => 'nullable',
            'from_18_to_21' => 'nullable',
            'from_21_to_now' => 'nullable',
            'id_license_date' => 'nullable|date_format:d/m/Y|before:now',
            'union_join_date' => 'nullable|date_format:d/m/Y|after:birthday|before:now',
            'group_join_date' => 'nullable|date_format:d/m/Y|after:birthday|before:now',
            'specialized' => 'nullable|max:50',
            'foreign_language' => 'nullable|max:20',
        ];

        if (request()->birthday != null) {
            $toYear = Carbon::now()->format('Y');
            $birthYear = Carbon::createFromFormat('d/m/Y',request()->birthday)->format('Y');

            $arrValidate ['graduation_year'] = "nullable|gt:$birthYear|lt:$toYear";
        }

        return $arrValidate;
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'full_name.required' => __('Tên công dân là trường bắt buộc phải nhập'),
            'full_name.max' => __('Tên công dân chỉ cho phép tối đa 50 kí tự'),
            'code.required' => __('Mã hồ sơ là trường bắt buộc phải chọn'),
            'code.max' => __('Mã hồ sơ có tối đa 15 kí tự'),
            'gender.required' => __('Giới tính là trường bắt buộc phải chọn'),
            'birthday.required' => __('Ngày tháng năm sinh là trường bắt buộc phải chọn'),
            'birthday.before' => __('Ngày tháng năm sinh phải trước ngày hôm nay'),
            'id_license_date.before' => __('Ngày cấp CMND/CCCD phải trước ngày hôm nay'),
            'temporary_address.required' => __('Địa chỉ tạm trú là trường bắt buộc phải nhập'),
            'permanent_address.required' => __('Địa chỉ thường trú là trường bắt buộc phải nhập'),
            'hometown.required' => __('Quê quán là trường bắt buộc phải nhập.'),
            'hometown.max' => __('Quê quán có tối đa 191 kí tự.'),
            'birthplace.max' => __('Đăng ký khai sinh có tối đa 191 kí tự.'),
            'ethnic_id.required' => __(' Dân tộc là trường bắt buộc phải chọn'),
            'id_number.required' => __('CMND/CCCD là trường bắt buộc phải nhập'),
            'id_number.unique' => __('CMND/CCCD đã tồn tại'),
            'elementary_school.min' => __('Tên trường cấp 1 có tối thiểu 5 kí tự'),
            'elementary_school.max' => __('Tên trường cấp 1 có tối đa 50 kí tự'),
            'middle_school.min' => __('Tên trường cấp 2 có tối thiểu 5 kí tự'),
            'middle_school.max' => __('Tên trường cấp 2 có tối đa 50 kí tự'),
            'high_school.min' => __('Tên trường cấp 3 có tối thiểu 5 kí tự'),
            'high_school.max' => __('Tên trường cấp 3 có tối đa 50 kí tự'),
            'from_18_to_21.min' => __('Từ 18-21 tuổi có tối thiểu 5 kí tự'),
            'from_18_to_21.max' => __('Từ 18-21 tuổi có tối đa 50 kí tự'),
            'from_21_to_now.min' => __('Từ 21 tuổi đến nay có tối thiểu 5 kí tự'),
            'from_21_to_now.max' => __('Từ 21 tuổi đến nay có tối đa 50 kí tự'),
            'id_license_date.max' => __('Từ 21 tuổi đến nay có tối đa 50 kí tự'),
            'id_number.min' => __('CMND CCCD có tối thiểu 9 kí tự'),
            'id_number.max' => __('CMND CCCD có tối đa 12 kí tự'),
            'union_join_date.date_format' => __('Ngày vào Đảng phải có định dạng d/m/Y'),
            'union_join_date.after' => __('Ngày vào Đảng phải sau ngày sinh'),
            'union_join_date.before' => __('Ngày vào Đảng phải trước ngày hiện tại'),
            'group_join_date.date_format' => __('Ngày vào Đoàn phải có định dạng d/m/Y'),
            'group_join_date.after' => __('Ngày vào Đoàn phải sau ngày sinh'),
            'group_join_date.before' => __('Ngày vào Đoàn phải trước ngày hiện tại'),
            'graduation_year.date_format' => __('Năm tốt nghiệp phải có định dạng Y'),
            'graduation_year.gt' => __('Năm tốt nghiệp phải sau năm sinh'),
            'graduation_year.lt' => __('Năm tốt nghiệp phải trước năm hiện tại'),
            'specialized.max' => __('Chuyên ngành đào tạo có tối đa 50 kí tự'),
            'foreign_language.max' => __('Ngoại ngữ có tối đa 20 kí tự'),
            'code.unique' => __('Mã hồ sơ đã tồn tại'),
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
            'full_name' => 'strip_tags|trim',
            'code' => 'strip_tags|trim',
            'family_member' => 'trim',
        ];
    }
}
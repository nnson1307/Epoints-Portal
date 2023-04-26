<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 4:50 PM
 */

namespace Modules\People\Http\Requests\PeopleVerify;


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
        $year = Carbon::now()->year;
        return [
            'people_id' => 'required',
            'people_verification_year' =>'nullable|lte:'.$year,
            'people_object_id' => 'required',
            'content' => 'nullable|max:250',
            'note' => 'nullable|max:250',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'people_id.required' => __('Hãy chọn người phúc tra'),
            'people_object_id.required' => __('Hãy chọn đối tượng'),
            'content.max' => __('Lý do có tối đa 250 kí tự'),
            'note.max' => __('Ghi chú có tối đa 250 kí tự'),
            'people_verification_year.lte' => __('Năm phúc tra phải nhỏ hơn hoặc bằng năm hiện tại'),
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
            'note' => 'strip_tags|trim',
        ];
    }
}
<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/13/2020
 * Time: 10:54 AM
 */

namespace Modules\FNB\Http\Requests\Table;


use Illuminate\Foundation\Http\FormRequest;

class AddTableRequest extends FormRequest
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

        return [
            'area_id' => 'required',
//            'code' => 'required|max:100|unique:fnb_table,code',
            'name' => 'required|max:255',
            'seats' => 'required',
            'description' => 'nullable|max:400',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'area_id.required' => __('Hãy chọn khu vực'),
//            'code.max' => __('Mã bàn không quá 100 kí tự'),
//            'code.required' => __('Hãy nhập mã bàn'),
//            'code.unique' => __('Mã bàn đã tồn tại'),
            'name.required' => __('Hãy nhập tên khu vực'),
            'name.max' => __('Tên bàn không quá 255 kí tự'),
            'seats.required' => __('Hãy nhập số ghế'),
            'description.max' => __('Ghi chú không quá 400 kí tự'),

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

        ];
    }
}
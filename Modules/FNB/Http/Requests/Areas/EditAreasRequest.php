<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/13/2020
 * Time: 10:54 AM
 */

namespace Modules\FNB\Http\Requests\Areas;


use Illuminate\Foundation\Http\FormRequest;

class EditAreasRequest extends FormRequest
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
//            'area_code' => 'required|max:100|unique:fnb_areas,area_code,'.$param['area_id'].',area_id',
            'name' => 'required|max:255',
            'branch_id' => 'required|integer',
            'note' => 'nullable|max:400',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
//            'area_code.required' => __('Hãy nhập Mã khu vực'),
//            'area_code.unique' => __('Mã khu vực đã tồn tại'),
//            'area_code.max' => __('Mã khu vực không quá 100 kí tự'),
            'name.required' => __('Hãy Nhập tên khu vực'),
            'name.max' => __('Tên khu vực không quá 255 kí tự'),
            'branch_id.required' => __('Hãy chọn chi nhánh'),
            'note.max' => __('Ghi chú không quá 400 kí tự'),
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
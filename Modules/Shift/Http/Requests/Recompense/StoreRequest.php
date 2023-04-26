<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/10/2022
 * Time: 17:44
 */

namespace Modules\Shift\Http\Requests\Recompense;

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
            'recompense_name' => 'required|max:190|unique:sf_recompense,recompense_name,'.',recompense_id,is_deleted,0',

        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'recompense_name.required' => __('Hãy nhập tên nội dung'),
            'recompense_name.max' => __('Tên nội dung tối đa 190 kí tự'),
            'recompense_name.unique' => __('Tên nội dung đã tồn tại'),
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
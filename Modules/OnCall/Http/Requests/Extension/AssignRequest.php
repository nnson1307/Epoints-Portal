<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/07/2021
 * Time: 11:19
 */

namespace Modules\OnCall\Http\Requests\Extension;

use Illuminate\Foundation\Http\FormRequest;

class AssignRequest extends FormRequest
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
            'staff_id' => 'required|unique:oc_extensions,staff_id,'. $param['extension_id'] .',extension_id,is_deleted,0',
        ];
    }

    /*
     * function custom messages
     */
    public function messages()
    {
        return [
            'staff_id.required' => __('Hãy chọn nhân viên'),
            'staff_id.unique' => __('Nhân viên được phân bổ đã tồn tại ở extension khác')
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
            'staff_id' => 'strip_tags|trim',
        ];
    }
}
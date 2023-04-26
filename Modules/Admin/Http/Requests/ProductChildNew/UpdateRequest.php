<?php


namespace Modules\Admin\Http\Requests\ProductChildNew;


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

        return [
            'product_child_name' => 'required|max:250',
            'cost' => 'required',
            'price' => 'required',
            'barcode' => 'nullable|max:190|unique:product_childs,barcode,'.$param['product_child_id'].',product_child_id,is_deleted,0'
        ];
    }

    public function messages()
    {
        return [
            'product_child_name.required' => __('Vui lòng nhập tên sản phẩm con.'),
            'product_child_name.max' => __('Tên sản phẩm con tối đa 250 ký tự.'),
            'cost.required' => __('Vui lòng nhập giá nhập.'),
            'price.required' => __('Vui lòng nhập giá bán.'),
            'barcode.max' => __('Mã vạch tối đa 190 ký tự.'),
            'barcode.unique' => __('Mã vạch đã tồn tại.'),
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
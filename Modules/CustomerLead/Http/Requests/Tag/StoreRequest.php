<?php

namespace Modules\CustomerLead\Http\Requests\Tag;

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
            'tag_name' => 'required|max:250|unique:cpo_tag,name,'.',tag_id,is_deleted,0',
        ];
    }

    public function messages()
    {
        return [
            'tag_name.required' => __('Hãy nhập tên tag'),
            'tag_name.max' => __('Tên tag tối đa 250 kí tự'),
            'tag_name.unique' => __('Tên tag đã tồn tại')
        ];
    }
}

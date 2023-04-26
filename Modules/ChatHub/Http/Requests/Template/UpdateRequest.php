<?php

namespace Modules\ChatHub\Http\Requests\Template;

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
            return [
                'title' => 'required|max:80',
                'subtitle'=>'max:80| required',
            ];
    }
    public function messages()
    {
        return [
            'title.required'=>'Title không được để trống',
            'title.max'=>'Title tối đa 80 ký tự',
            'subtitle.max'=>'Title tối đa 80 ký tự',
            'subtitle.required' => 'Subtitle không được để trống'
        ];
    }
}

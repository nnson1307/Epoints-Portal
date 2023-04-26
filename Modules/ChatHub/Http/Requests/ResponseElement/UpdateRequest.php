<?php

namespace Modules\ChatHub\Http\Requests\ResponseElement;

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
                'title' => 'required|max:255',
                'subtitle'=>'max:255',
            ];
    }
    public function messages()
    {
        return [
            'title.required'=>__('chathub::response_element.index.TITLE_REQUIRED'),
            'title.max'=>__('chathub::response_element.index.TITLE_MAX'),
            'subtitle.max'=>__('chathub::response_element.index.SUBTITLE_MAX')
        ];
    }
}

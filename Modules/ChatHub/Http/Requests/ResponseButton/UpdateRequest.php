<?php

namespace Modules\ChatHub\Http\Requests\ResponseButton;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

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
        if(request()->type=='web_url'){
            return [
                'url'=>'required',
                'type'=>'required',
                'title' => 'required|max:80|unique:chathub_response_button,title,'.request()->response_button_id.',response_button_id',  
            ];
        }
        else{
            return [
                'payload'=>'required',
                'type'=>'required',
                'title' => 'required|max:80|unique:chathub_response_button,title,'.request()->response_button_id.',response_button_id',
            ];
        }
    }
    public function messages()
    {
        return [
            'url.required'=>__('chathub::response_button.index.URL_REQUIRED'),
            'type.required'=>__('chathub::response_button.index.TYPE_REQUIRED'),
            'title.required'=>__('chathub::response_button.index.TITLE_REQUIRED'),
            'title.max'=>__('chathub::response_button.index.TITLE_MAX'),
            'title.unique'=>__('chathub::response_button.index.TITLE_UNIQUE'),
            'payload.required'=>__('chathub::response_button.index.PAYLOAD_REQUIRED'),
        ];
    }
}

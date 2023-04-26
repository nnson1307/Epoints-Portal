<?php

namespace Modules\ChatHub\Http\Requests\Button;

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
                'title' => 'required|max:80',
            ];
        }
        else{
            return [
                'payload'=>'required',
                'type'=>'required',
                'title' => 'required|max:80',
            ];
        }
    }
    public function messages()
    {
        return [
            'url.required'=>'Url không được để trống',
            'type.required'=>'Type không được để trống',
            'title.required'=>'Title không được để trống',
            'title.max'=>'Title tối đa 80 ký tự',
            'payload.required'=>'Payload không được để trống'
        ];
    }
}

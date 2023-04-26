<?php

namespace Modules\SyncDataGoogleSheet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataGoogleSheetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'idGoogleSheet'  => 'required|exists:cpo_customer_lead_config_source,id_google_sheet',
            'rowLast'        => 'required|integer'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


}

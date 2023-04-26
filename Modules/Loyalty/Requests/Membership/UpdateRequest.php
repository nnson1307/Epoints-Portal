<?php

namespace Modules\Loyalty\Requests\Membership;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Loyalty\Models\LoyaltyMembershipTable;
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
        if(request()->point_maintain && isset(request()->change_point_value)){
            return [
                'program_id' => 'required|integer',
                'is_active'=> 'required|integer',
                'rank_name'=>'required|unique:loy_rank,rank_name,'.request()->rank_id.',rank_id,is_deleted,0,is_active,1',
                'percent_point'=>'integer|min:0',
                'point_maintain'=>'integer|min:0',
                'point'=>'required|integer|min:0|greater_than_field:point_maintain|unique:loy_rank,point,'.request()->rank_id.',rank_id,is_deleted,0,is_active,1',
                'is_review_periodically'=>'required|integer',
                'change_point_value'=>'required|integer|min:0',
                
            ];
        }
        else{
            return [
                'program_id' => 'required|integer',
                'is_active'=> 'required|integer',
                'rank_name'=>'required|unique:loy_rank,rank_name,'.request()->rank_id.',rank_id,is_deleted,0,is_active,1',
                'percent_point'=>'integer|min:0',
                'point'=>'required|integer|min:0|unique:loy_rank,point,'.request()->rank_id.',rank_id,is_deleted,0,is_active,1',
                'is_review_periodically'=>'required|integer',
            ];
        }

    }

    public function messages()
    {
        $select=LoyaltyMembershipTable::select('rank_name')->where('point','=',request()->point)->first();
        if($select){            
            $select=$select->getName();
        }
        else{
            $select=null;
        }
        return [
            'rank_name.unique'=> __('loyalty::membership.update.NAME_UNIQUE'),
            'point.after'=>__('loyalty::membership.update.MAIN_AFTER'),
            'point.unique'=>__('loyalty::membership.update.POINT_UNIQUE').' '.$select
        ];
    }
}

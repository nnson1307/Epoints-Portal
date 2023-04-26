<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ReferralTable extends Model
{
    protected $table = "referral_criteria";
    protected $primaryKey = "referral_criteria_id";

    public function getSelectInfo(){
            $mSelect = $this
                ->select(
                    'referral_criteria_id',
                    'referral_criteria_code',
                    'referral_criteria_name'
                );
            return $mSelect->get()->toArray();
    }
}

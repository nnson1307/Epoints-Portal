<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ReferralProgramRateTable extends Model
{
    protected $table = "referral_program_rate";
    protected $primaryKey = "referral_program_rate_id";
    public $timestamps = false;

    public function getAllActiveByProgram($id){
        return $this->where('referral_program_id', $id)->where('is_actived',1)->get();
    }
}

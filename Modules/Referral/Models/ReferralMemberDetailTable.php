<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use PHPUnit\Framework\Constraint\Count;


class ReferralMemberDetailTable extends Model
{
    use ListTableTrait;
    protected $table = "referral_member_detail";
    protected $primaryKey = "referral_member_detail_id";
    protected $fillable = [
        'referral_member_detail_id',
        'referral_member_id',
        'referral_multi_level_id',
        'referral_from',
        'action',
        'type',
        'obj_id',
        'total_money',
        'total_commission',
        'Note',
        'created_at',
        'is_run'
    ];

    /**
     * Thêm nhiều data cùng lúc
     * @param $data
     */
    public function insertData($data){
        return $this
            ->insert($data);
    }
}

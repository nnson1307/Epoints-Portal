<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class MultiLevelConfigTable extends Model
{
    protected $table = "referral_multi_level";
    protected $primaryKey = "referral_multi_level_id";
    public $timestamps = false;

    public function getOldInfo()
    {
        $mSelect = $this
            ->select(
                "{$this->table}.referral_multi_level_id",
                "{$this->table}.level",
                "{$this->table}.percent"
                )
            ->orderBy("{$this->table}.referral_multi_level_id", "desc");
        return $mSelect->first()->toArray();
    }

    //luu chinh sua cau hinh nhieu cap
    public function saveMultilevelConfig($input)
    {
        return $this
            ->insertGetId($input);
    }
}

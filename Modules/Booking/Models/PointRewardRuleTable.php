<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 10:19 AM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class PointRewardRuleTable extends Model
{
    protected $table = "point_reward_rule";
    protected $primaryKey = "point_reward_rule_id";
    protected $fillable
        = [
            'point_reward_rule_id', 'rule_name', 'rule_code', 'point_maths',
            'point_value', 'rule_type', 'hagtag_id', 'description',
            'is_actived', 'modified_by', 'modified_at', 'updated_at'
        ];

    /**
     * get all active = 1.
     * @return mixed
     */
    public function getAllActive()
    {
        $select = $this->select(
            'point_reward_rule_id',
            'rule_code',
            'rule_name',
            'point_maths',
            'point_value',
            'rule_type',
            'hagtag_id'
        )
            ->where('is_actived', 1)
            ->get();
        return $select;
    }

    /**
     * get all active = 1.
     * @return mixed
     */
    public function getItemByCode($code)
    {
        $select = $this->select(
            'point_reward_rule_id',
            'rule_code',
            'rule_name',
            'point_maths',
            'point_value',
            'rule_type',
            'hagtag_id',
            'is_actived'
        )
            ->where('is_actived', 1)
            ->where('rule_code', $code)
            ->first();
        return $select;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 10:19 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class PointRewardRuleTable extends Model
{
    public $timestamps = false;
    protected $table = "point_reward_rule";
    protected $primaryKey = "point_reward_rule_id";
    protected $fillable
        = [
            'point_reward_rule_id', 'rule_name', 'rule_code', 'point_maths',
            'point_value', 'rule_type', 'hagtag_id', 'description',
            'is_actived', 'modified_by', 'modified_at'
        ];

    /**
     * get all
     * @return mixed
     */
    public function getAll()
    {
        return $this->select('*')->get();
    }

    /**
     * Edit
     * @param array $data
     * @param       $id
     *
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('point_reward_rule_id', $id)->update($data);
    }

    /**
     * @param $rule_code
     * @return mixed
     */
    public function getRuleByCode($rule_code)
    {
        $ds = $this
            ->select(
                'point_reward_rule_id',
                'rule_name',
                'rule_code',
                'point_maths',
                'point_value',
                'rule_type',
                'is_actived'
            )
            ->where('rule_code', $rule_code)->first();
        return $ds;
    }

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
}
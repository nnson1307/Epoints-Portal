<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 5:21 PM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class PointHistoryDetailTable extends Model
{
    public $timestamps = false;
    protected $table = "point_history_detail";
    protected $primaryKey = "point_history_detail_id";
    protected $fillable
        = [
            'point_history_detail_id', 'point_history_id',
            'point_reward_rule_id', 'created_at', 'created_by'
        ];

    /**
     * Add
     *
     * @param array $data
     *
     * @return mixed
     */
    public function add(array $data)
    {
        $oCustom = $this->create($data);
        return $oCustom->point_history_detail_id;
    }
}
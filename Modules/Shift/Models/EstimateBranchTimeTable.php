<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/7/22
 * Time: 5:48 PM
 */

namespace Modules\Shift\Models;

use Illuminate\Database\Eloquent\Model;
// use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;

class EstimateBranchTimeTable extends Model
{
    // use ListTableTrait;
    protected $table = "estimate_branch_time";
    protected $primaryKey = "estimate_branch_time_id";
    protected $fillable = [
        "estimate_branch_time_id",
        "branch_id",
        "estimate_time",
        "estimate_money",
        "months",
        "days",
        "years",
        "weeks",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Thêm mới checkin
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->check_in_log_id;
    }
    /**
     * Chỉnh sửa vào ca
     *
     * @param array $data
     * @param $checkInLogId
     * @return mixed
     */
    public function edit(array $data, $checkInLogId)
    {
        return $this->where("check_in_log_id", $checkInLogId)->update($data);
    }
}
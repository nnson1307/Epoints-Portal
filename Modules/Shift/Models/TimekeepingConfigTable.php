<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\Shift\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class TimekeepingConfigTable extends Model
{
    use ListTableTrait;
    protected $table = "sf_timekeeping_config";
    protected $primaryKey = "timekeeping_config_id";
    protected $fillable = [
        "timekeeping_config_id",
        "branch_id",
        "wifi_name",
        "wifi_ip",
        "timekeeping_type",
        "latitude",
        "longitude",
        "allowable_radius",
        "note",
        "is_actived",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Danh sách
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.{$this->primaryKey}",
                "{$this->table}.wifi_name",
                "{$this->table}.wifi_ip",
                "{$this->table}.note",
                "{$this->table}.timekeeping_type",
                "{$this->table}.latitude",
                "{$this->table}.longitude",
                "{$this->table}.is_actived",
                "{$this->table}.created_at",
                "br.branch_name"
            )
            ->leftJoin("branches as br", "br.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.{$this->primaryKey}", "desc");

        // filter tên
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.shift_name", 'like', '%' . $search . '%');
            });
        }

        //filter is_actived
        if (isset($filter['is_actived']) && !empty($filter['is_actived'])) {
            $ds->where("{$this->table}.is_actived", $filter['is_actived']);
            unset($filter['is_actived']);
        }

        return $ds;
    }

    /**
     * Thêm
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Lấy thông tin
     *
     * @param $timeKeepingId
     * @return mixed
     */
    public function getInfo($timeKeepingId)
    {
        return $this
            ->select(
                "{$this->table}.{$this->primaryKey}",
                "{$this->table}.wifi_name",
                "{$this->table}.wifi_ip",
                "{$this->table}.note",
                "{$this->table}.timekeeping_type",
                "{$this->table}.latitude",
                "{$this->table}.longitude",
                "{$this->table}.allowable_radius",
                "{$this->table}.is_actived",
                "{$this->table}.branch_id"
            )
            ->where("{$this->primaryKey}", $timeKeepingId)
            ->first();
    }

    /**
     * Chỉnh sửa
     *
     * @param array $data
     * @param $timeKeepingId
     * @return mixed
     */
    public function edit(array $data, $timeKeepingId)
    {
        return $this->where("{$this->primaryKey}", $timeKeepingId)->update($data);
    }



}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/10/2022
 * Time: 10:22
 */

namespace Modules\Shift\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class RecompenseTable extends Model
{
    use ListTableTrait;
    protected $table = "sf_recompense";
    protected $primaryKey = "recompense_id";
    protected $fillable = [
        "recompense_id",
        "recompense_name",
        "type",
        "is_system",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * DS thưởng phạt
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.recompense_id",
                "{$this->table}.recompense_name",
                "{$this->table}.type",
                "{$this->table}.is_system",
                "{$this->table}.is_actived",
                "s.full_name as staff_name",
                "{$this->table}.created_at"
            )
            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.created_by")
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.recompense_id", "desc");

        // filter tên
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.recompense_name", 'like', '%' . $search . '%');
            });
        }

        return $ds;
    }

    /**
     * Lấy option loại thưởng - phạt
     *
     * @param $type
     * @return mixed
     */
    public function getRecompense($type)
    {
        return $this
            ->select(
                "recompense_id",
                "recompense_name"
            )
            ->where("type", $type)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Thêm thưởng phạt
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->recompense_id;
    }

    /**
     * Chỉnh sửa thưởng phạt
     *
     * @param array $data
     * @param $recompenseId
     * @return mixed
     */
    public function edit(array $data, $recompenseId)
    {
        return $this->where("recompense_id", $recompenseId)->update($data);
    }

    /**
     * Lấy thông tin thưởng phạt
     *
     * @param $recompenseId
     * @return mixed
     */
    public function getInfo($recompenseId)
    {
        return $this
            ->select(
                "recompense_id",
                "recompense_name",
                "type",
                "is_system",
                "is_actived"
            )
            ->where("recompense_id", $recompenseId)
            ->first();
    }
}
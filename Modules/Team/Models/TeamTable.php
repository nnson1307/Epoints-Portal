<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/07/2022
 * Time: 13:54
 */

namespace Modules\Team\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TeamTable extends Model
{
    use ListTableTrait;
    protected $table = "team";
    protected $primaryKey = "team_id";
    protected $fillable = [
        "team_id",
        "team_name",
        "team_code",
        "department_id",
        "staff_title_id",
        "staff_id",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Danh sách nhóm
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.team_id",
                "{$this->table}.team_name",
                "{$this->table}.team_code",
                "{$this->table}.department_id",
                "{$this->table}.is_actived",
                "dp.department_name",
                "tt.staff_title_name",
                "sf.full_name as staff_name",
                "{$this->table}.created_at"
            )
            ->join("departments as dp", "dp.department_id", "=", "{$this->table}.department_id")
            ->join("staff_title as tt", "tt.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("staffs as sf", function ($join) {
                $join->on("sf.staff_id", "=", "{$this->table}.staff_id")
                    ->on("sf.staff_title_id", "=", "{$this->table}.staff_title_id");
            })
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.team_id", "desc");

        // filter tên CT, mã CT
        if (isset($filter["search"]) && $filter["search"] != "") {
            $search = $filter["search"];

            $ds->where(function ($query) use ($search) {
                $query->where("team_name", "like", "%" . $search . "%")
                    ->orWhere("team_code", "like", "%" . $search . "%");
            });
        }


        return $ds;
    }

    /**
     * Thêm nhóm
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->team_id;
    }

    /**
     * Lấy thông tin nhóm
     *
     * @param $teamId
     * @return mixed
     */
    public function getInfo($teamId)
    {
        return $this
            ->select(
                "team_id",
                "team_name",
                "team_code",
                "department_id",
                "staff_title_id",
                "staff_id",
                "is_actived"
            )
            ->where("team_id", $teamId)
            ->first();
    }

    /**
     * Chỉnh sửa nhóm
     *
     * @param array $data
     * @param $teamId
     * @return mixed
     */
    public function edit(array $data, $teamId)
    {
        return $this->where("team_id", $teamId)->update($data);
    }
}
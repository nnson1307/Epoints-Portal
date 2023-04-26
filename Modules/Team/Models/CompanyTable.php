<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/07/2022
 * Time: 13:53
 */

namespace Modules\Team\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CompanyTable extends Model
{
    use ListTableTrait;
    protected $table = "company";
    protected $primaryKey = "company_id";
    protected $fillable = [
        "company_id",
        "company_name",
        "company_code",
        "description",
        "is_actived",
        "is_deleted",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by"
    ];

    const NOT_DELETED = 0;

    /**
     * Danh sách công ty
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "company_id",
                "company_name",
                "company_code",
                "is_actived",
                "created_at",
                "description"
            )
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.company_id", "desc");

        // filter tên CT, mã CT
        if (isset($filter["search"]) && $filter["search"] != "") {
            $search = $filter["search"];

            $ds->where(function ($query) use ($search) {
                $query->where("company_name", "like", "%" . $search . "%")
                    ->orWhere("company_code", "like", "%" . $search . "%");
            });
        }

        return $ds;
    }

    /**
     * Thêm công ty
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->company_id;
    }

    /**
     * Lấy thông tin công ty
     *
     * @param $companyId
     * @return mixed
     */
    public function getInfo($companyId)
    {
        return $this
            ->select(
                "company_id",
                "company_name",
                "company_code",
                "is_actived",
                "description"
            )
            ->where("company_id", $companyId)
            ->first();
    }

    /**
     * Chỉnh sửa công ty
     *
     * @param array $data
     * @param $companyId
     * @return mixed
     */
    public function edit(array $data, $companyId)
    {
        return $this->where("company_id", $companyId)->update($data);
    }
}
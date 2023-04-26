<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/23/2020
 * Time: 10:07 AM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class PipelineCategoryTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_pipeline_categories";
    protected $primaryKey = "pipeline_category_id";
    protected $fillable = [
        "pipeline_category_id",
        "pipeline_category_code",
        "pipeline_category_name",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;

    /**
     * Danh sách pipeline category
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select(
                "pipeline_category_id",
                "pipeline_category_code",
                "pipeline_category_name",
                "is_actived"
            )
            ->where("is_deleted", self::NOT_DELETE)
            ->orderBy("pipeline_category_id", "desc");

        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where('pipeline_category_name', 'like', '%' . $search . '%')
                    ->orWhere('pipeline_category_code', 'like', '%' . $search . '%');
            });

        }



        return $ds;
    }

    /**
     * Thêm pipeline category
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->pipeline_category_id;
    }

    /**
     * Lấy thông tin pipeline category
     *
     * @param $categoryId
     * @return mixed
     */
    public function getInfo($categoryId)
    {
        return $this
            ->select(
                "pipeline_category_id",
                "pipeline_category_code",
                "pipeline_category_name",
                "is_actived"
            )
            ->where("pipeline_category_id", $categoryId)
            ->first();
    }

    /**
     * Chỉnh sửa pipeline category
     *
     * @param array $data
     * @param $categoryId
     * @return mixed
     */
    public function edit(array $data, $categoryId)
    {
        return $this->where("pipeline_category_id", $categoryId)->update($data);
    }

    /**
     * Lấy option pipeline category
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "pipeline_category_id",
                "pipeline_category_code",
                "pipeline_category_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }
}
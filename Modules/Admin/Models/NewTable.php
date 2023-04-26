<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/28/2020
 * Time: 4:03 PM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class NewTable extends Model
{
    use ListTableTrait;
    protected $table = "news";
    protected $primaryKey = "new_id";
    protected $fillable = [
        "new_id",
        "title_vi",
        "title_en",
        "image",
        "image_app",
        "description_vi",
        "description_en",
        "description_detail_vi",
        "description_detail_en",
        "product",
        "service",
        "is_actived",
        "created_at",
        "updated_at",
        "is_deleted",
        "created_by",
        "updated_by"
    ];

    const NOT_DELETE = 0;

    /**
     * Lấy danh sách bài viết
     *
     * @param array $filters
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "new_id",
                "title_vi",
                "title_en",
                "image",
                "image_app",
                "description_vi",
                "description_en",
                "description_detail_vi",
                "description_detail_en",
                "is_actived"
            )
            ->where("is_deleted", self::NOT_DELETE)
            ->orderBy("new_id", "desc");

        //Filter tiêu đề
        if (isset($filter["search"]) != "") {
            $search = $filter["search"];
            $ds->where(function ($query) use ($search) {
                $query->where("title_vi", "like", "%" . $search . "%")
                    ->orWhere("title_en", "%" . $search . "%");
            });
        }

        return $ds;
    }

    /**
     * Thêm bài viết
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->new_id;
    }

    /**
     * lấy thông tin bài viết
     *
     * @param $newId
     * @return mixed
     */
    public function getItem($newId)
    {
        return $this
            ->select(
                "new_id",
                "title_vi",
                "title_en",
                "image",
                "image_app",
                "description_vi",
                "description_en",
                "description_detail_vi",
                "description_detail_en",
                "product",
                "service",
                "is_actived"
            )
            ->where("new_id", $newId)
            ->first();
    }

    /**
     * Chỉnh sửa bài viết
     *
     * @param array $data
     * @param $newId
     * @return mixed
     */
    public function edit(array $data, $newId)
    {
        return $this->where("new_id", $newId)->update($data);
    }
}
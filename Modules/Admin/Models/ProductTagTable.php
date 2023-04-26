<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 09:51
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductTagTable extends Model
{
    use ListTableTrait;
    protected $table = "product_tags";
    protected $primaryKey = "product_tag_id";
    protected $fillable = [
        "product_tag_id",
        "keyword",
        "name",
        "is_deleted",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETE = 0;

    /**
     * Danh sách tag
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select(
                "product_tag_id",
                "keyword",
                "name",
                "created_at"
            )
            ->where("is_deleted", self::NOT_DELETE);
        // filter name
        if (isset($filter['search']) && $filter['search'] != '') {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });

        }
        return $ds;
    }

    /**
     * Thêm tag
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->product_tag_id;
    }

    /**
     * Lấy thông tin tag
     *
     * @param $tagId
     * @return mixed
     */
    public function getInfo($tagId)
    {
        return $this->where("product_tag_id", $tagId)->first();
    }

    /**
     * Chỉnh sửa tag
     *
     * @param array $data
     * @param $tagId
     * @return mixed
     */
    public function edit(array $data, $tagId)
    {
        return $this->where("product_tag_id", $tagId)->update($data);
    }

    /**
     * Lấy option tag
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "product_tag_id",
                "name"
            )
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Xoa tag
     *
     * @param $tagId
     * @return mixed
     */
    public function deleteTag($tagId)
    {
        return $this->where('product_tag_id', $tagId)->delete();
    }

    /**
     * lấy id theo name tag
     *
     * @param $name
     * @return mixed
     */
    public function getIdByTagName($name)
    {
        return $this
            ->select(
                "product_tag_id",
                "name"
            )
            ->where("name", $name)
            ->first();
    }

}
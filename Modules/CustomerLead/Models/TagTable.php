<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 3:11 PM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TagTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_tag";
    protected $primaryKey = "tag_id";
    protected $fillable = [
        "tag_id",
        "type",
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
                "tag_id",
                "type",
                "keyword",
                "name"
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
        return $this->create($data)->tag_id;
    }

    /**
     * Lấy thông tin tag
     *
     * @param $tagId
     * @return mixed
     */
    public function getInfo($tagId)
    {
        return $this->where("tag_id", $tagId)->first();
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
        return $this->where("tag_id", $tagId)->update($data);
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
                "tag_id",
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
        return $this->where('tag_id', $tagId)->delete();
    }

    /**
     * lấy id theo name tag
     *
     * @param $name
     * @return mixed
     */
    public function getIdByTagName($name)
    {
        return $this->select(
            "tag_id",
            "name"
        )->where("name", $name)->first();
    }

    /**
     * Lấy tag theo list ids
     *
     * @param $name
     * @return mixed
     */
    public function getTagByIds($ids){
        return $this->select(
            "tag_id",
            "name"
        )->whereIn("tag_id", $ids)->get();
    }
}
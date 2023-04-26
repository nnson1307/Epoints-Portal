<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/08/2021
 * Time: 10:52
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractTagTable extends Model
{
    protected $table = "contract_tags";
    protected $primaryKey = "contract_tag_id";
    protected $fillable = [
        "contract_tag_id",
        "keyword",
        "name",
        "is_deleted",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Lấy option tag
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "contract_tag_id",
                "keyword",
                "name"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    public function getInfo($tagId)
    {
        return $this
            ->select(
                "contract_tag_id",
                "keyword",
                "name"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->where("contract_tag_id", $tagId)
            ->first();
    }
    /**
     * Thêm tag
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_tag_id;
    }
}
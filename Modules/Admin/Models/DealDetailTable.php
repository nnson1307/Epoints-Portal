<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/07/2021
 * Time: 15:16
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class DealDetailTable extends Model
{
    protected $table = "cpo_deal_details";
    protected $primaryKey = "deal_detail_id";
    protected $fillable = [
        "deal_detail_id",
        "deal_code",
        "object_id",
        "object_name",
        "object_type",
        "object_code",
        "price",
        "quantity",
        "discount",
        "amount",
        "voucher_code",
        "updated_at",
        "created_at",
        "is_deleted",
        "created_by",
        "updated_by"
    ];

    const NOT_DELETE = 0;

    /**
     * Thêm chi tiết deal
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->deal_detail_id;
    }
}
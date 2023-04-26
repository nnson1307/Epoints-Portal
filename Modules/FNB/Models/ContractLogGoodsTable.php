<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 16:11
 */

namespace Modules\FNB\Models;


use Illuminate\Database\Eloquent\Model;

class ContractLogGoodsTable extends Model
{
    protected $table = "contract_log_goods";
    protected $primaryKey = "contract_log_goods_id";
    protected $fillable = [
        "contract_log_goods_id",
        "contract_log_id",
        "contract_godds_id",
        "object_type",
        "object_name",
        "object_id",
        "object_code",
        "price",
        "quantity",
        "discount",
        "tax",
        "amount",
        "note",
        "object_type_new",
        "object_name_new",
        "object_id_new",
        "object_code_new",
        "price_new",
        "quantity_new",
        "discount_new",
        "tax_new",
        "amount_new",
        "note_new",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm log hàng hoá
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}
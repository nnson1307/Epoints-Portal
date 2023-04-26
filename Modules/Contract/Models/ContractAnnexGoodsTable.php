<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 15:50
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractAnnexGoodsTable extends Model
{
    protected $table = "contract_annex_goods";
    protected $primaryKey = "contract_annex_goods_id";
    protected $fillable = [
        "contract_annex_goods_id",
        "contract_annex_id",
        "object_type",
        "object_name",
        "object_id",
        "object_code",
        "unit_id",
        "price",
        "quantity",
        "discount",
        "tax",
        "amount",
        "note",
        "order_code",
        "staff_id",
        "is_applied_kpi",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Lấy danh sách hàng hoá
     *
     * @param array $filter
     * @return mixed
     */
    public function getList($filter = [])
    {
        $ds = $this
            ->select(
                "contract_goods_id",
                "contract_annex_id",
                "object_type",
                "object_name",
                "object_id",
                "object_code",
                "unit_id",
                "price",
                "quantity",
                "discount",
                "tax",
                "amount",
                "note",
                "order_code",
                "staff_id",
                "is_applied_kpi"
            )
            ->where("is_deleted", self::NOT_DELETED);

        //Filter theo HĐ
        if (isset($filter['contract_annex_id'])) {
            $ds->where("{$this->table}.contract_annex_id", $filter['contract_annex_id']);
            unset($filter['contract_annex_id']);
        }

        return $ds->get();
    }

    /**
     * Thêm hàng hoá
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_annex_goods_id;
    }

    /**
     * thêm nhiều hàng hoá
     *
     * @param array $data
     * @return mixed
     */
    public function insertList(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Chỉnh sửa hàng hoá
     *
     * @param array $data
     * @param $goodsId
     * @return mixed
     */
    public function edit(array $data, $goodsId)
    {
        return $this->where("contract_annex_goods_id", $goodsId)->update($data);
    }
    public function deleteAnnexGoods($contractAnnexId)
    {
        return $this->where("contract_annex_id", $contractAnnexId)->delete();
    }
    /**
     * Lấy thông tin hàng hoá
     *
     * @param $goodsId
     * @return mixed
     */
    public function getInfo($goodsId)
    {
        return $this
            ->select(
                "contract_annex_goods_id",
                "contract_annex_id",
                "object_type",
                "object_name",
                "object_id",
                "object_code",
                "unit_id",
                "price",
                "quantity",
                "discount",
                "tax",
                "amount",
                "note",
                "order_code",
                "staff_id",
                "is_applied_kpi"
            )
            ->where("contract_annex_goods_id", $goodsId)
            ->first();
    }
}
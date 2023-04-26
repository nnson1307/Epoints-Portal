<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 15:50
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractAnnexLogGoodsTable extends Model
{
    protected $table = "contract_annex_log_goods";
    protected $primaryKey = "contract_annex_log_good_id";
    protected $fillable = [
        "contract_annex_log_good_id",
        "contract_annex_id",
        "version",
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
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    public function getLogGoodsContractAnnex($contractAnnexId, $version = '')
    {
        $ds = $this->where("contract_annex_id", $contractAnnexId);
        if($version != ''){
            $ds->where("version", $version);
        }
        return $ds->get()->toArray();
    }
    /**
     * Thêm log hàng hoá
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_annex_log_good_id;
    }

    /**
     * thêm nhiều log hàng hoá
     *
     * @param array $data
     * @return mixed
     */
    public function insertList(array $data)
    {
        return $this->insert($data);
    }
    public function deleteAnnexLogGoods($contractAnnexId)
    {
        return $this->where("contract_annex_id", $contractAnnexId)->delete();
    }
}
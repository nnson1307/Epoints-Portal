<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CommissionLogTable extends Model
{
    use ListTableTrait;
    protected $table  = "commission_log";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "customer_id",
        "branch_id",
        "money",
        "type",
        "note",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by"
    ];

    /**
     * Lịch sử nhận hoa hồng
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList($filter = [])
    {
        $ds = $this
            ->select(
                "br.branch_name",
                "{$this->table}.money",
                "{$this->table}.note",
                "{$this->table}.created_at",
                "{$this->table}.type"
            )
            ->leftJoin("branches as br", "br.branch_id", "=", "{$this->table}.branch_id");

        return $ds;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    /**
     * @param $customer_id
     * @return mixed
     */
    public function getLogByCustomer($customer_id)
    {
        $ds = $this
            ->leftJoin("branches", "branches.branch_id", "=", "commission_log.branch_id")
            ->select(
                "branches.branch_name",
                "commission_log.money",
                "commission_log.note",
                "commission_log.created_at",
                "commission_log.type"
            )
            ->where("commission_log.customer_id", $customer_id)->get();
        return $ds;
    }
}
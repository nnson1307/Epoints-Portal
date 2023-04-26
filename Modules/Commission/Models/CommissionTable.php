<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

/**
 * Class CommissionTable
 * @author HaoNMN
 * @since Jun 2022
 */
class CommissionTable extends Model
{
    use ListTableTrait;
    protected $table = 'commission';
    protected $primaryKey = 'commission_id';
    protected $fillable = [
        'commission_id',
        'commission_name',
        'commission_type',
        'apply_time',
        'calc_apply_time',
        'start_effect_time',
        'end_effect_time',
        'status',
        'description',
        'commission_calc_by',
        'commission_scope',
        'order_commission_type',
        'order_commission_group_type',
        'order_commission_object_type',
        'order_commission_calc_by',
        'kpi_commission_calc_by',
        'contract_commission_calc_by',
        'contract_commission_type',
        'contract_commission_condition',
        'contract_commission_time',
        'contract_commission_operation',
        'contract_commission_apply',
        'is_deleted',
        'created_by',
        'created_at',
        'updated_at'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Danh sách hoa hồng
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.commission_id",
                "{$this->table}.commission_name",
                "{$this->table}.commission_type",
                "{$this->table}.start_effect_time",
                "{$this->table}.end_effect_time",
                "{$this->table}.apply_time",
                "{$this->table}.calc_apply_time"
            )
            ->orderBy("{$this->table}.commission_id", "desc");

        if(!empty( $filter['search'] )) {
            $ds->where("{$this->table}.commission_name", 'LIKE', '%' . $filter['search'] . '%');
        }

        //Filter theo tag
        if(!empty( $filter['tags_id'] )) {
            $ds->join('commission_tags as ct', 'ct.commission_id', '=', "{$this->table}.commission_id")
                ->where("ct.tags_id", $filter['tags_id']);
        }

        return $ds;
    }


    /**
     * Lấy danh sách hoa hồng
     */
    public function listCommission(array $filter = [])
    {
        $oSelect = $this->select(
            "{$this->table}.commission_id",
            "{$this->table}.commission_name",
            "{$this->table}.commission_type",
            "{$this->table}.status",
            "{$this->table}.start_effect_time",
            "{$this->table}.apply_time",
            "{$this->table}.calc_apply_time",
            "{$this->table}.created_by",
            "{$this->table}.created_at"
        )
        ->where("{$this->table}.is_deleted", self::NOT_DELETED)
        ->orderBy("{$this->table}.commission_id", "desc");

        if(!empty( $filter['commission_name'] )) {
            $oSelect->where("{$this->table}.commission_name", 'LIKE', '%' . $filter['commission_name'] . '%');
        }

        if(isset( $filter['status'] )) {
            $oSelect->where("{$this->table}.status", $filter['status']);
        }

        if(!empty( $filter['tags_id'] )) {
            $oSelect->join('commission_tags as ct', 'ct.commission_id', '=', "{$this->table}.commission_id")
                    ->where("ct.tags_id", $filter['tags_id']);
        }

        if(!empty( $filter['commission_type'] )) {
            $oSelect->where("{$this->table}.commission_type", $filter['commission_type']);
        }

        return $oSelect->paginate(10);
    }

    /** 
     * Thêm hoa hồng 
    */
    public function add($data)
    {
        return $this->create($data)->commission_id;
    }

    /**
     * Soft delete hoa hồng
     */
    public function removeCommission($id)
    {
        return $this->where($this->primaryKey, $id)
                    ->update(['is_deleted' => 1]);
    }

    /** 
     * Lấy danh sách loại hoa hồng
    */
    public function getListTypeCommission()
    {
        return $this->select("{$this->table}.commission_type")->distinct('commission_type')->get();
    }

    /**
     * Lấy chi tiết hoa hồng
     */
    public function getDetailCommision($id)
    {
        return $this->where("{$this->table}.commission_id", $id)
                    ->first()->toArray();
    }

    /**
     * Đếm số lượng nhân viên trong 1 hoa hồng
     */
    public function countStaffCommission($id)
    {
        return $this->where("{$this->table}.commission_id", $id)
                    ->leftJoin('commission_allocation as ca', 'ca.commission_id', '=', "{$this->table}.commission_id")
                    ->count('ca.staff_id');
    }

    /**
     * Lấy thông tin hoa hồng
     *
     * @param $idCommission
     * @return mixed
     */
    public function getInfo($idCommission)
    {
        return $this
            ->select(
                "{$this->table}.commission_id",
                "{$this->table}.commission_name",
            )
            ->where("{$this->table}.commission_id", $idCommission)
            ->first();
    }

    /**
     * Cập nhật trạng thái hoa hồng
     *
     * @param array $data
     * @param $idCommission
     * @return mixed
     */
    public function edit(array $data, $idCommission)
    {
        return $this->where("commission_id", $idCommission)->update($data);
    }
}

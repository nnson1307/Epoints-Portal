<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/3/2021
 * Time: 10:35 AM
 */

namespace Modules\Warranty\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use MyCore\Models\Traits\ListTableTrait;

class MaintenanceCostTypeTable extends Model
{
    use ListTableTrait;
    protected $table = "maintenance_cost_type";
    protected $primaryKey = "maintenance_cost_type_id";
    protected $fillable = [
        'maintenance_cost_type_id', 'maintenance_cost_type_name_vi', 'maintenance_cost_type_name_en','is_active','is_delete',
        'created_by', 'updated_by','created_at', 'updated_at'
    ];
    const IS_ACTIVE = 1;
    const IS_DELETE = 0;

    /**
     * Danh sách phiếu bảo hành điện tử
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this->select(
            "{$this->table}.maintenance_cost_type_id",
            "{$this->table}.maintenance_cost_type_name_vi",
            "{$this->table}.maintenance_cost_type_name_en",
            "{$this->table}.is_active")
            ->where("is_delete", self::IS_DELETE);
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.maintenance_cost_type_name_vi", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.maintenance_cost_type_name_en", 'like', '%' . $search . '%');
            });
        }
        return $ds->orderBy('maintenance_cost_type_id','desc');
    }

    /**
     * Thêm loại phí phát sinh
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->maintenance_cost_type_id;
    }

    /**
     * Lấy thông tin 1 loại phí phát sinh
     *
     * @param $maintenanceCostTypeId
     * @return mixed
     */
    public function getInfo($maintenanceCostTypeId)
    {
        return $this
            ->select(
                "{$this->table}.maintenance_cost_type_id",
                "{$this->table}.maintenance_cost_type_name_vi",
                "{$this->table}.maintenance_cost_type_name_en",
                "{$this->table}.is_active"
            )
            ->where("{$this->table}.maintenance_cost_type_id", $maintenanceCostTypeId)
            ->first();
    }

    /**
     * Lấy thông tin 1 loại chi phí phát sinh
     *
     * @param $maintenanceCostTypeNameVi
     * @return mixed
     */
    public function getTypeByNameVi($maintenanceCostTypeNameVi)
    {
        return $this
            ->select(
                "{$this->table}.maintenance_cost_type_id",
                "{$this->table}.maintenance_cost_type_name_vi",
                "{$this->table}.maintenance_cost_type_name_en",
                "{$this->table}.is_active"
            )
            ->where("{$this->table}.maintenance_cost_type_name_vi", $maintenanceCostTypeNameVi)
            ->first();
    }
    /**
     * Lấy thông tin 1 loại chi phí phát sinh
     *
     * @param $maintenanceCostTypeNameEn
     * @return mixed
     */
    public function getTypeByNameEn($maintenanceCostTypeNameEn)
    {
        return $this
            ->select(
                "{$this->table}.maintenance_cost_type_id",
                "{$this->table}.maintenance_cost_type_name_vi",
                "{$this->table}.maintenance_cost_type_name_en",
                "{$this->table}.is_active"
            )
            ->where("{$this->table}.maintenance_cost_type_name_en", $maintenanceCostTypeNameEn)
            ->first();
    }

    /**
     * Chỉnh sửa loại phí phát sinh
     *
     * @param array $data
     * @param $maintenanceCostTypeId
     * @return mixed
     */
    public function edit(array $data, $maintenanceCostTypeId)
    {
        return $this->where("maintenance_cost_type_id", $maintenanceCostTypeId)->update($data);
    }
    /**
     * Lấy option chi phí phát sinh
     *
     * @return mixed
     */
    public function getCostType()
    {
        $lang = Config::get('app.locale');

        return $this
            ->select(
                "maintenance_cost_type_id",
                "maintenance_cost_type_name_$lang as maintenance_cost_type_name"
            )
            ->where("is_active", self::IS_ACTIVE)
            ->where("is_delete", self::IS_DELETE)
            ->get();
    }

    /**
     * Xoá chi phí phát sinh
     *
     * @param $maintenanceCostTypeId
     * @return mixed
     */
    public function deleteType($maintenanceCostTypeId)
    {
        return $this->where($this->primaryKey, $maintenanceCostTypeId)->update(['is_delete' => 1]);
    }
}
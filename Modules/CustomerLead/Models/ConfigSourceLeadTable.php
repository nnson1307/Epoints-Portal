<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/27/2020
 * Time: 4:26 PM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ConfigSourceLeadTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_customer_lead_config_source";
    protected $primaryKey = "cpo_customer_lead_config_source_id";
    protected $fillable = [
        "cpo_customer_lead_config_source_id",
        "team_marketing_id",
        "link",
        "is_rotational_allocation",
        'id_google_sheet',
        "is_active",
        "is_deleted",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    public function _getList(&$filters = [])
    {
        $oSelect = $this
            ->select(
                $this->table . '.*',
                'team.team_name'
            )
            ->join('team', 'team.team_id', $this->table . '.team_marketing_id')
            ->where($this->table . '.is_deleted', 0);

        if (isset($filters['department_id'])) {

            $oSelect->join(
                'cpo_customer_lead_config_source_map',
                'cpo_customer_lead_config_source_map.cpo_customer_lead_config_source_id',
                $this->table . '.cpo_customer_lead_config_source_id'
            )
                ->where('cpo_customer_lead_config_source_map.department_id', $filters['department_id']);
            unset($filters['department_id']);
        }

        if (isset($filters['team_marketing_id'])) {
            $oSelect = $oSelect->where($this->table . '.team_marketing_id', $filters['team_marketing_id']);
            unset($filters['team_marketing_id']);
        }

        return $oSelect->orderBy($this->table . '.cpo_customer_lead_config_source_id', 'DESC');
    }

    //    Lấy chi tiết
    public function getItem($cpo_customer_lead_config_source_id)
    {
        return $this
            ->where('cpo_customer_lead_config_source_id', $cpo_customer_lead_config_source_id)
            ->first();
    }

    /**
     * Thêm
     */
    public function addItem($data)
    {
        return $this
            ->insertGetId($data);
    }

    /**
     * Cập nhật Item
     * @param $data
     * @param $id
     */
    public function updateItem($data, $id)
    {
        return $this
            ->where('cpo_customer_lead_config_source_id', $id)
            ->update($data);
    }
    /**
     * lấy item theo id google sheet
     * @param [string] $idGoogleSheet
     * @return mixed
     */
    public function getItemByIdGoogleSheet($idGoogleSheet)
    {
        return $this->where('id_google_sheet', $idGoogleSheet)->first();
    }

    /**
     * lấy tất cả nhân viên thuộc cấu hình phân bổ theo phòng ban 
     * @param [int] $idGoogleSheet
     * @param [array] $filters
     * @param [string] $select
     * @return mixed
     */
    public function getAllStaff($idGooogleSheet, $filters = [])
    {
        $result = $this
            ->select(
                "cpo_customer_lead_config_source.cpo_customer_lead_config_source_id",
                "cpo_customer_lead_config_source.id_google_sheet",
                "cpo_customer_lead_config_source_map.cpo_customer_lead_config_source_id",
                "cpo_customer_lead_config_source_map.department_id",
                'staffs.staff_id',
                'staffs.user_name',
                'staffs.is_allotment'
            )
            ->where('cpo_customer_lead_config_source.id_google_sheet', '=', $idGooogleSheet)
            ->join(
                'cpo_customer_lead_config_source_map',
                'cpo_customer_lead_config_source_map.cpo_customer_lead_config_source_id',
                $this->table . '.cpo_customer_lead_config_source_id'
            )
            ->join(
                'departments',
                'cpo_customer_lead_config_source_map.department_id',
                'departments.department_id'
            )
            ->join('staffs', function ($query) use ($filters) {
                $query->on('departments.department_id', '=', 'staffs.department_id');
                $query->where('staffs.is_deleted', 0);
                $query->where('staffs.is_actived', 1);
                if (!isset($filters['is_allotment'])) {
                    $query->where('staffs.is_allotment', null);
                }
            })
            ->orderBy('staffs.user_name');

        return $result->get();
    }

    /**
     * Xóa cấu hình
     * @param $data
     * @return mixed
     */
    public function removeConfig($data)
    {
        return $this->where($data)->delete();
    }
}

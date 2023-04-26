<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/22/2019
 * Time: 4:02 PM
 */

namespace Modules\ManagerWork\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MapRoleGroupStaffTable extends Model
{
    protected $table = 'map_role_group_staff';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'role_group_id', 'staff_id', 'is_actived', 'created_at', 'updated_at'
    ];

    const PORTAL = "portal";

    public function add(array $data)
    {
        $oAdd = $this->create($data);
        return $oAdd->id;
    }

    public function edit(array $data, $roleGroupId, $staffId)
    {
        return $this->where('role_group_id', $roleGroupId)
            ->where('staff_id', $staffId)
            ->update($data);
    }

    public function checkIssetMap($roleGroupId, $staffId)
    {
        return $this->select('id', 'role_group_id', 'staff_id', 'is_actived')
            ->where('role_group_id', $roleGroupId)
            ->where('staff_id', $staffId)->first();
    }

    public function getRoleGroupByStaffId($staffId)
    {
        return $this->select('id', 'role_group_id', 'staff_id', 'is_actived')
            ->where('staff_id', $staffId)
            ->where('is_actived', 1)
            ->get();
    }
    public function getRoleDataContractByStaffId($staffId)
    {
        $data = $this->select(
            "{$this->table}.role_group_id",
            "contract_role_data_config.role_data_type"
        )
            ->join("contract_role_data_config", "contract_role_data_config.role_group_id", "{$this->table}.role_group_id")
            ->where("{$this->table}.staff_id", $staffId);
        return $data->get();
    }
    public function editById(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)
            ->update($data);
    }

    public function removeByUser($id)
    {
        return $this->where('staff_id', $id)
            ->delete();
    }

    /**
     * Lấy quyền role page
     *
     * @param $staff
     * @param array $arrFeature
     * @return array
     */
    public function getRolePageByStaff($staff, $arrFeature = [])
    {
        $select = $this
            ->select(
                'pages.route as route'
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', "{$this->table}.staff_id")
            ->leftJoin('role_group', 'role_group.id', '=', "{$this->table}.role_group_id")
            ->leftJoin('role_pages', 'role_pages.group_id', '=', 'role_group.id')
            ->leftJoin('pages', 'pages.id', '=', 'role_pages.page_id')
            ->join('action_group as ag', 'ag.action_group_id', '=', 'pages.action_group_id')
            ->where('ag.is_actived', 1)
            ->where("{$this->table}.is_actived", 1)
            ->where('role_group.is_actived', 1)
            ->where('role_pages.is_actived', 1)
            ->where('pages.is_actived', 1)
            ->where("ag.platform", self::PORTAL)
            ->where("{$this->table}.staff_id", $staff)
            ->whereIn("pages.route", $arrFeature)
            ->get();
        $data = [];
        if ($select != null) {
            foreach ($select as $item) {
                $data[] = $item['route'];
            }
        }

        return $data;
    }

    /**
     * Lấy quyền role action
     *
     * @param $staff
     * @param array $arrFeature
     * @return array
     */
    public function getRoleActionByStaff($staff, $arrFeature = [])
    {
        $select = $this
            ->select(
                'actions.name as route'
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'map_role_group_staff.staff_id')
            ->leftJoin('role_group', 'role_group.id', '=', 'map_role_group_staff.role_group_id')
            ->leftJoin('role_actions', 'role_actions.group_id', '=', 'role_group.id')
            ->leftJoin('actions', 'actions.id', '=', 'role_actions.action_id')
            ->join('action_group as ag', 'ag.action_group_id', '=', 'actions.action_group_id')
            ->where('ag.is_actived', 1)
            ->where('map_role_group_staff.is_actived', 1)
            ->where('role_group.is_actived', 1)
            ->where('role_actions.is_actived', 1)
            ->where('actions.is_actived', 1)
            ->where('map_role_group_staff.staff_id', $staff)
            ->where("ag.platform", self::PORTAL)
            ->whereIn("actions.name", $arrFeature)
            ->get();
        $data = [];
        if ($select != null) {
            foreach ($select as $item) {
                $data[] = $item['route'];
            }
        }

        return $data;
    }

//    Lấy danh sách nhân viên duyệt phiếu yêu cầu vật tư
    public function getListStaffApproveTicket(){
        return $this
            ->select($this->table.'.*')
            ->join('role_group','role_group.id',$this->table.'.role_group_id')
            ->join('ticket_role','ticket_role.role_group_id',$this->table.'.role_group_id')
            ->where($this->table.'.is_actived',1)
            ->where('role_group.is_actived',1)
            ->where('ticket_role.is_approve_refund',1)
            ->get();
    }

    public function checkRoleWork($userId){
        return $this
            ->select('manage_role.role_group_id', 'is_all', 'is_branch', 'is_department', 'is_own')
            ->join('manage_role', 'manage_role.role_group_id', 'map_role_group_staff.role_group_id')
            ->where($this->table.'.staff_id', $userId)
            ->get()->toArray();
    }

}
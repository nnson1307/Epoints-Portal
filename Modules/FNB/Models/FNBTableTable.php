<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:24 AM
 */

namespace Modules\FNB\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class FNBTableTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_table';
    protected $primaryKey = 'table_id';
    protected $fillable
        = [
            'table_id',
            'area_id',
            'code',
            'name',
            'description',
            'seats',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];

    const ID_NOT_SHOW = 1;

    public function _getList(&$filter = []){
        $oSelect = $this
        ->select(
            "{$this->table}.table_id",
            "{$this->table}.area_id",
            "fnb_areas.name as area_name",
//            "{$this->table}.code",
//            "{$this->table}.qr_code_id",
//            "{$this->table}.qr_code_table",
//            "{$this->table}.qr_image",
            "{$this->table}.name as table_name",
            "{$this->table}.description",
            "{$this->table}.seats",
            "{$this->table}.seats",
            "{$this->table}.created_at",
            "{$this->table}.created_by",
            "{$this->table}.updated_at",
            "{$this->table}.updated_by",
            "{$this->table}.is_active",
            'createdBy.full_name as created_name',
            'updatedBy.full_name as updated_name'
        )
            ->leftJoin("fnb_areas","{$this->table}.area_id","fnb_areas.area_id")
            ->leftJoin('staffs as createdBy','createdBy.staff_id',$this->table.'.created_by')
            ->leftJoin('staffs as updatedBy','updatedBy.staff_id',$this->table.'.updated_by');
        if (isset($filter['area_id'])){
            $oSelect = $oSelect->where($this->table.'.area_id',$filter['area_id']);
            unset($filter['area_id']);
        }
        if (isset($filter['search'])){
            $oSelect = $oSelect->where($this->table.'.name','like','%'.$filter['search'].'%');
            unset($filter['search']);
        }
        if (isset($filter['is_active'])){
            $oSelect = $oSelect->where($this->table.'.is_active',$filter['is_active']);
            unset($filter['is_active']);
        }
        if (isset($filter['created_at'])){
            $time = explode(' - ',$filter['created_at']);
            $start = Carbon::createFromFormat('d/m/Y',$time[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$time[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.created_at',[$start,$end]);
            unset($filter['created_at']);
        }

        if (isset($filter['created_by'])){
            $oSelect = $oSelect->where($this->table.'.created_by',$filter['created_by']);
            unset($filter['created_by']);
        }
        if (isset($filter['created_name'])){
            $oSelect = $oSelect->where($this->table.'.created_by',$filter['created_name']);
            unset($filter['created_name']);
        }

        if (isset($filter['updated_at'])){
            $time1 = explode(' - ',$filter['updated_at']);
            $start1 = Carbon::createFromFormat('d/m/Y',$time1[0])->format('Y-m-d 00:00:00');
            $end1 = Carbon::createFromFormat('d/m/Y',$time1[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.updated_at',[$start1,$end1]);
            unset($filter['updated_at']);
        }

        if (isset($filter['updated_by'])){
            $oSelect = $oSelect->where($this->table.'.updated_by',$filter['updated_by']);
            unset($filter['updated_by']);
        }
        if (isset($filter['updated_name'])){
            $oSelect = $oSelect->where($this->table.'.created_by',$filter['updated_name']);
            unset($filter['updated_name']);
        }

        return $oSelect
            ->where($this->table.'.table_id','<>',self::ID_NOT_SHOW)
            ->orderBy($this->table.'.table_id','DESC');
    }

    public function getListPagination(&$filter = []){

        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.*',
                DB::raw("(Select COUNT(*) FROM orders where orders.fnb_table_id = {$this->table}.table_id and orders.is_deleted = 0 and orders.fnb_table_id is not null and orders.process_status IN ('paysuccess','ordercancle')) as empty_table"),
                DB::raw("(Select COUNT(*) FROM orders where orders.fnb_table_id = {$this->table}.table_id and orders.is_deleted = 0 and orders.fnb_table_id is not null and orders.process_status NOT IN ('paysuccess','ordercancle')) as using_table"),
                DB::raw("(Select SUM(orders.amount) FROM orders where orders.fnb_table_id = {$this->table}.table_id and orders.is_deleted = 0 and orders.fnb_table_id is not null and orders.process_status NOT IN ('paysuccess','ordercancle')) as using_money_table"),
                DB::raw("(Select orders.order_id FROM orders where orders.fnb_table_id = {$this->table}.table_id and orders.is_deleted = 0 and orders.fnb_table_id is not null and orders.process_status NOT IN ('paysuccess','ordercancle') ORDER BY orders.created_at DESC LIMIT 1) as using_order_id_table"),
                DB::raw("(Select COUNT(*) FROM fnb_customer_request where fnb_customer_request.table_id = {$this->table}.table_id and fnb_customer_request.status NOT IN ('done')) as customer_request")
            );
        if ($filter['area_id'] != -1){
            $oSelect = $oSelect
                ->where($this->table.'.area_id',$filter['area_id']);
        }

        if (isset($filter['search'])){
            $oSelect = $oSelect->where($this->table.'.name','like','%'.$filter['search'].'%');
            unset($filter['search']);
        }

        return $oSelect
            ->where($this->table.'.table_id','<>',self::ID_NOT_SHOW)
            ->orderBy($this->table.'.table_id','DESC')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Lấy danh sách bàn hiện có
     * @param array $filter
     */
    public function getAll($filter = []){
        $oSelect = $this;

        if (isset($filter['apply_arear_id']) && $filter['apply_arear_id'] != -1){
            $oSelect = $oSelect->where('area_id',$filter['apply_arear_id']);
        }

        if (isset($filter['apply_table_id']) && $filter['apply_table_id'] != -1){
            $oSelect = $oSelect->where('table_id',$filter['apply_table_id']);
        }

        return $oSelect
            ->where($this->table.'.table_id','<>',self::ID_NOT_SHOW)
            ->get();
    }

    public function createTable($dataTable){
        return $this ->insertGetId($dataTable);
    }
    public function editTable($tableId,$dataEditTable){
        return $this
            ->where("{$this->table}.table_id",$tableId)
            ->update($dataEditTable);
    }
    public function deleteTable($tableId){
        return $this
            ->where("{$this->table}.table_id",$tableId)
            ->delete();
    }
    public function getListNoPage($filter = []){
        $oSelect = $this
            ->select(
                "{$this->table}.table_id",
                "{$this->table}.area_id",
                "fnb_areas.name as area_name",
//                "{$this->table}.code",
//                "{$this->table}.qr_code_id",
//                "{$this->table}.qr_code_table",
//                "{$this->table}.qr_image",
                "{$this->table}.name as table_name",
                "{$this->table}.description",
                "{$this->table}.seats",
                "{$this->table}.seats",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "staffs.full_name as created_name",
                "{$this->table}.updated_at",
                "{$this->table}.updated_by",
                "{$this->table}.is_active"
            )
            ->leftJoin("fnb_areas","{$this->table}.area_id","fnb_areas.area_id")
            ->leftJoin("staffs","{$this->table}.created_by","staffs.staff_id");
        if (isset($filter['area_id'])){
            $oSelect = $oSelect->where($this->table.'.area_id',$filter['area_id']);
            unset($filter['area_id']);
        }

        if (isset($filter['search'])){
            $oSelect = $oSelect->where($this->table.'.name','like','%'.$filter['search'].'%');
            unset($filter['search']);
        }
        return $oSelect
            ->where($this->table.'.table_id','<>',self::ID_NOT_SHOW)
            ->orderBy($this->table.'.table_id','DESC')->get();
    }

    public function getListTableByArea($filter = []){
        $oSelect = $this
            ->select(
                "{$this->table}.table_id",
                "{$this->table}.area_id",
                "fnb_areas.name as area_name",
//                "{$this->table}.code",
//                "{$this->table}.qr_code_id",
//                "{$this->table}.qr_code_table",
//                "{$this->table}.qr_image",
                "{$this->table}.name as table_name",
                "{$this->table}.description",
                "{$this->table}.seats",
                "{$this->table}.seats",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "{$this->table}.updated_at",
                "{$this->table}.updated_by",
                "{$this->table}.is_active"
            )
            ->leftJoin("fnb_areas","{$this->table}.area_id","fnb_areas.area_id");
        if (isset($filter['area_id']) && $filter['area_id'] != 'all'){
            $oSelect = $oSelect->where($this->table.'.area_id',$filter['area_id']);
            unset($filter['area_id']);
        }

        if (isset($filter['un_table_id'])){
            $oSelect = $oSelect->where($this->table.'.table_id','<>',$filter['un_table_id']);
            unset($filter['un_table_id']);
        }

        return $oSelect
            ->where($this->table.'.table_id','<>',self::ID_NOT_SHOW)
            ->orderBy($this->table.'.table_id','DESC')->get();
    }

    public function getListTableByAreaPagination($filter = []){
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                "{$this->table}.table_id",
                "{$this->table}.area_id",
                "fnb_areas.name as area_name",
//                "{$this->table}.code",
//                "{$this->table}.qr_code_id",
//                "{$this->table}.qr_code_table",
//                "{$this->table}.qr_image",
                "{$this->table}.name as table_name",
                "{$this->table}.description",
                "{$this->table}.seats",
                "{$this->table}.seats",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "{$this->table}.updated_at",
                "{$this->table}.updated_by",
                "{$this->table}.is_active"
            )
            ->leftJoin("fnb_areas","{$this->table}.area_id","fnb_areas.area_id");
        if (isset($filter['area_id']) && $filter['area_id'] != 'all'){
            $oSelect = $oSelect->where($this->table.'.area_id',$filter['area_id']);
            unset($filter['area_id']);
        }

        if (isset($filter['un_table_id'])){
            $oSelect = $oSelect->where($this->table.'.table_id','<>',$filter['un_table_id']);
            unset($filter['un_table_id']);
        }

        return $oSelect
            ->where($this->table.'.table_id','<>',self::ID_NOT_SHOW)
            ->orderBy($this->table.'.table_id','DESC')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}
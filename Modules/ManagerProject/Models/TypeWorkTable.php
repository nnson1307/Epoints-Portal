<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerProject\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class TypeWorkTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_type_work';
    protected $primaryKey = 'manage_type_work_id';

    protected $fillable = ['manage_type_work_id', 'manage_type_work_name','manage_type_work_key', 'manage_type_work_icon','created_by','manage_type_work_default',
        'updated_by', 'created_at', 'updated_at','is_active'];

    
    public function staff_created()
    {
        return $this->belongsTo('Modules\ManagerWork\Models\StaffTable','created_by','staff_id');
    }
    
    protected function _getList($filters = [])
    {
         
        $query = $this->select('manage_type_work_id', 'manage_type_work_name', 'manage_type_work_icon','created_by',
        'updated_by', 'created_at', 'updated_at','is_active')
            ->orderBy($this->primaryKey, 'desc');

        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("manage_type_work_name", "like", "%" . $search . "%");
        }
        // filters nguoi tao
         if (isset($filters["created_by"]) && $filters["created_by"] != "") {
            $query->where("created_by", $filters["created_by"]);
        }

        // filter ngày tạo
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("created_at", ">=", $startTime);
            $query->whereDate("created_at", "<=", $endTime);
        }
        return $query;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName(){
        $oSelect= self::select("manage_type_work_id","manage_type_work_name")->where($this->table.'.is_active',1)->orderBy('manage_type_work_name', 'asc')->get();
        return ($oSelect->pluck("manage_type_work_name","manage_type_work_id")->toArray());
    }

    public function testCode($code, $id)
    {
        return $this->where('manage_type_work_name', $code)->where('manage_type_work_id', '<>', $id)->first();
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->manage_type_work_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
    * check exist
    */
    public function checkExist($name = '', $id = '')
    {
        $select = $this->where('manage_type_work_name', $name)
        ->where('manage_type_work_id','<>', $id)
        ->first();
        return $select;
    }

    public function getListTypeWork($is_default = null){
        $oSelect =  $this
            ->select(
                'manage_type_work_id',
                'manage_type_work_name',
                'manage_type_work_icon',
                'manage_type_work_key',
                'manage_type_work_default'
            )
            ->where('is_active',1);

        if($is_default != null){
            $oSelect = $oSelect->where('manage_type_work_default',$is_default);
        }

        return $oSelect->orderBy('manage_type_work_id','ASC')->get();
    }

    /**
     * Lấy danh sách loại công việc mặc định
     */
    public function getListDefault($sort = 'DESC'){
        return $this
            ->select(
                $this->table.'.manage_type_work_id',
                $this->table.'.manage_type_work_key',
                $this->table.'.manage_type_work_name',
                $this->table.'.manage_type_work_icon'
            )
            ->where('manage_type_work_default',1)
            ->where('is_active',1)
            ->orderBy('manage_type_work_id',$sort)
            ->get();
    }

    /**
     * Lấy tổng công việc theo theo loại công việc
     */
    public function getTotalTypeWorkByLead($listCustomer,$manage_work_customer_type){
        return $this
            ->select(
                'manage_type_work.manage_type_work_id',
                'manage_type_work.manage_type_work_key',
                'manage_type_work.manage_type_work_name',
                'manage_type_work.manage_type_work_icon',
//                DB::raw("COUNT(*) as total_work")
                DB::raw("SUM(IF(manage_work.customer_id IN ({$listCustomer}) AND manage_work.manage_work_customer_type = '{$manage_work_customer_type}' AND manage_work.manage_status_id NOT IN (6,7),1,0)) as total_work")

            )
            ->leftJoin('manage_work','manage_work.manage_type_work_id',$this->table.'.manage_type_work_id')
            ->where('manage_type_work.manage_type_work_default',1)
            ->where('manage_type_work.is_active',1)
//            ->where('manage_work.manage_work_customer_type',$manage_work_customer_type)
//            ->whereIn('manage_work.customer_id',$listCustomer)
//            ->whereNotIn('manage_work.manage_status_id',[6,7])
            ->groupBy('manage_type_work.manage_type_work_id')
            ->orderBy('manage_type_work.manage_type_work_id','DESC')
            ->get();

    }
}
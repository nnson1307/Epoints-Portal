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
use MyCore\Models\Traits\ListTableTrait;

class ManageWorkSupportTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_work_support';
    protected $primaryKey = 'manage_work_support_id';

    protected $fillable = ['manage_work_support_id', 'manage_work_id', 'staff_id','created_by',
        'updated_by', 'created_at', 'updated_at'];

    protected function _getList($filters = [])
    {
        $query = $this->select('manage_work_support_id', 'manage_work_id', 'staff_id','created_by',
        'updated_by', 'created_at', 'updated_at')
            ->orderBy($this->primaryKey, 'desc');

        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["created_by"] != "") {
            $search = $filters["search"];
            $query->where("manage_work_id", "like", "%" . $search . "%");
        }
        // filters nguoi tao
         if (isset($filters["created_by"]) != "") {
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

    public function getName(){
        $oSelect= self::select("manage_work_support_id","manage_work_id")->get();
        return ($oSelect->pluck("manage_work_id","manage_work_support_id")->toArray());
    }

    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->manage_work_support_id;
    }

    public function remove($manageWorkId)
    {
        return $this->where('manage_work_id', $manageWorkId)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /**
     * Lấy danh sách nhân viên hỗ trợ
     * @param $manageWorkId
     */
    public function getListSupport($manageWorkId){
        $oSelect = $this
            ->select($this->table.'.staff_id','staffs.full_name as staff_name', 'manage_work_id')
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id');
        if(is_array($manageWorkId)){
            $oSelect->whereIn($this->table.'.manage_work_id',$manageWorkId);
        } else {
            $oSelect->where($this->table.'.manage_work_id',$manageWorkId);
        }

        return$oSelect->get();
    }

    public function insertArrSupport($data){
        return $this->insert($data);
    }

}
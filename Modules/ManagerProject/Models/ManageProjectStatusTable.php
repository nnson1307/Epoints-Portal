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

class ManageProjectStatusTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_project_status';
    protected $primaryKey = 'manage_project_status_id';

    protected $fillable = [
        'manage_project_status_id',
        'manage_project_status_value',
        'manage_project_status_name',
        'manage_project_status_color',
        'is_default',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    const IS_ACTIVE = 1;

    protected function _getList($filters = [])
    {

        $query = $this->select('manage_project_status_id', 'manage_project_status_name','manage_project_status_value','created_by',
        'updated_by', 'created_at', 'updated_at','is_active')
            ->orderBy($this->primaryKey, 'desc');

        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("manage_project_status_name", "like", "%" . $search . "%");
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

    public function getName(){
        $oSelect= self::select("manage_project_status_id","manage_project_status_name")->get();
        return ($oSelect->pluck("manage_project_status_name","manage_project_status_id")->toArray());
    }

    public function getColorList(){
        $oSelect= self::select("manage_project_status_id","manage_project_status_color")->get();
        return ($oSelect->pluck("manage_project_status_color","manage_project_status_id")->toArray());
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->manage_project_status_id;
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
        $select = $this->where('manage_project_status_name', $name)
            ->where('manage_project_status_id', '<>', $id)
            ->first();
        return $select;
    }

    /**
     * Lấy danh sách trạng thái
     */
    public function getAll($arrId = []){
        $oSelect = $this
            ->select(
                'manage_project_status_id',
                'manage_project_status_name',
                'manage_project_status_color'
            )
            ->where('is_active',self::IS_ACTIVE);

        if (count($arrId) != 0){
            $oSelect->whereIn('manage_project_status_id',$arrId);
        }

        return $oSelect->get();
    }

    /**
     * Lấy danh sách trạng thái đang hoạt động
     */
    public function getAllActive($arrId = []){
        $oSelect = $this
            ->select(
                $this->table.'.manage_project_status_id',
                $this->table.'.manage_project_status_name',
                $this->table.'.manage_project_status_color'
            )
            ->join('manage_project_status_config','manage_project_status_config.manage_project_status_id',$this->table.'.manage_project_status_id')
            ->where('manage_project_status_config.is_active',1)
            ->where($this->table.'.is_active',self::IS_ACTIVE);

        if (count($arrId) != 0){
            $oSelect->whereIn($this->table.'.manage_project_status_id',$arrId);
        }

        return $oSelect->get();
    }

    /**
     * Kiểm tra theo tên trạng thái
     * @param $name
     * @return mixed
     */
    public function getItemByName($name){
        return $this->where('manage_project_status_name',$name)->get();
    }

    /**
     * Lấy danh sách trạng thái trừ trạng thài hoàn thành và hủy
     */
    public function getAllStatusNotOverdue(){
        return $this
            ->whereNotIn('manage_project_status_id',[6,7])
            ->where('is_active',1)
            ->get();
    }

    public function getDetail($manage_project_status_id){
        return $this
            ->where('manage_project_status_id',$manage_project_status_id)
            ->first();
    }

}
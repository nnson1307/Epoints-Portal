<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class ManageRedmindTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_remind';
    protected $primaryKey = 'manage_remind_id';

    protected $fillable = ['manage_remind_id', 'staff_id','title' ,'date_remind','time','time_type','description','is_sent','manage_work_id','is_active',
        'created_by','updated_by', 'created_at', 'updated_at'];

    protected function _getList($filters = [])
    {
        $query = $this->select('manage_remind_id', 'staff_id', 'date_remind','time','title','time_type','description','is_sent','manage_work_id','is_active',
        'created_by','updated_by', 'created_at', 'updated_at')
            ->orderBy($this->primaryKey, 'desc');

        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("manage_remind_name", "like", "%" . $search . "%");
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
        $oSelect= self::select("manage_remind_id","date_remind")->get();
        return ($oSelect->pluck("date_remind","manage_remind_id")->toArray());
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->manage_remind_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function removeByWorkId($id)
    {
        return $this->where('manage_work_id', $id)->delete();
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /**
     * Thêm nhiều nhắc nhở
     * @param $data
     */
    public function insertArrayRemind($data){
        return $this->insert($data);
    }

    /**
     * Thêm nhiều nhắc nhở
     * @param $data
     */
    public function insertRemind($data){
        return $this->insertGetId($data);
    }

    /**
     * lấy danh sách nhắc nhở
     * @param $manage_work_id
     * @return mixed
     */
    public function getByWork($manage_work_id){
        return $this
            ->select(
                $this->table.'.manage_remind_id',
                $this->table.'.staff_id',
                $this->table.'.title',
                $this->table.'.date_remind',
                $this->table.'.time',
                $this->table.'.time_type',
                $this->table.'.description',
                $this->table.'.is_sent',
                $this->table.'.manage_work_id',
                $this->table.'.is_active',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'created.full_name as created_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->join('staffs as created','created.staff_id',$this->table.'.created_by')
            ->where('manage_work_id', $manage_work_id)
            ->orderBy($this->table.'.date_remind','DESC')
            ->get();
    }

    public function getDetailRemind($manage_remind_id){
        return $this
            ->select(
                $this->table.'.manage_remind_id',
                $this->table.'.staff_id',
                $this->table.'.title',
                $this->table.'.date_remind',
                $this->table.'.time',
                $this->table.'.time_type',
                $this->table.'.description',
                $this->table.'.is_sent',
                $this->table.'.manage_work_id',
                $this->table.'.is_active',
                $this->table.'.created_at'
            )
            ->where('manage_remind_id',$manage_remind_id)
            ->first();
    }

    /**
     * Cập nhật nhắc nhở
     * @param $data
     * @param $id
     */
    public function updateRemind($data,$id){
        return $this->where('manage_remind_id',$id)->update($data);
    }

    /**
     * lấy danh sách nhắc nhở
     * @param $manage_work_id
     * @return mixed
     */
    public function getListRemind($data){
        $oSelect = $this
            ->select(
                $this->table.'.manage_remind_id',
                $this->table.'.staff_id',
                $this->table.'.title',
                $this->table.'.date_remind',
                $this->table.'.time',
                $this->table.'.time_type',
                $this->table.'.description',
                $this->table.'.is_sent',
                $this->table.'.manage_work_id',
                $this->table.'.is_active',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'created.full_name as created_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->join('staffs as created','created.staff_id',$this->table.'.created_by');

        if (isset($data['manage_work_id'])){
            $oSelect = $oSelect->where($this->table.'.manage_work_id', $data['manage_work_id']);
        }

        if (isset($data['staff_id'])){
            $oSelect = $oSelect->where($this->table.'.staff_id', $data['staff_id']);
        }

        if (isset($data['description'])){
            $oSelect = $oSelect->where($this->table.'.description', 'like','%'.$data['description'].'%');
        }

        if (isset($data['date_remind'])){
            $date = explode(' - ',$data['date_remind']);
            $start = Carbon::createFromFormat('d/m/Y',$date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$date[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.date_remind', [$start,$end]);
        }

        if (isset($data['sort_date_remind'])){
            $oSelect = $oSelect->orderBy($this->table.'.date_remind',$data['sort_date_remind']);
        }

        $oSelect = $oSelect
            ->get();

        return $oSelect;
    }

    /**
     * lấy danh sách nhắc nhở
     * @param $manage_work_id
     * @return mixed
     */
    public function getListRemindMyWork($data){
        $oSelect = $this
            ->select(
                $this->table.'.manage_remind_id',
                $this->table.'.staff_id',
                $this->table.'.title',
                $this->table.'.date_remind',
                $this->table.'.time',
                $this->table.'.time_type',
                $this->table.'.description',
                $this->table.'.is_sent',
                $this->table.'.manage_work_id',
                $this->table.'.is_active',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'created.full_name as created_name',
                'manage_work.manage_work_title'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->join('staffs as created','created.staff_id',$this->table.'.created_by')
            ->leftJoin('manage_work','manage_work.manage_work_id',$this->table.'.manage_work_id')
            ->where($this->table.'.staff_id',Auth::id());

        if (isset($data['manage_work_id'])){
            $oSelect = $oSelect->where($this->table.'.manage_work_id', $data['manage_work_id']);
        }

        if (isset($data['staff_id'])){
            $oSelect = $oSelect->where($this->table.'.staff_id', $data['staff_id']);
        }

        if (isset($data['description'])){
            $oSelect = $oSelect->where($this->table.'.description', 'like','%'.$data['description'].'%');
        }

        if (isset($data['date_remind'])){
            $date = explode(' - ',$data['date_remind']);
            $start = Carbon::createFromFormat('d/m/Y',$date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$date[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.date_remind', [$start,$end]);
        }

        if (isset($data['sort_date_remind'])){
            $oSelect = $oSelect->orderBy($this->table.'.date_remind',$data['sort_date_remind']);
        }

        $oSelect = $oSelect
            ->get();

        return $oSelect;
    }

    public function removeArrRemind($arrRemind){
        return $this->whereIn('manage_remind_id',$arrRemind)->delete();
    }

    /**
     * Xóa nhắc nhở theo parent id task
     */
    public function removeListByParentTask($parentTask){
        return $this
            ->join('manage_work','manage_work.manage_work_id',$this->table.'.manage_work_id')
            ->where('manage_work.parent_id',$parentTask)
            ->delete();
    }

    /**
     * Xóa nhắc nhở theo công việc
     * @param $workId
     */
    public function removeByWork($workId){
        return $this
            ->where($this->table.'.manage_work_id',$workId)
            ->delete();
    }

}
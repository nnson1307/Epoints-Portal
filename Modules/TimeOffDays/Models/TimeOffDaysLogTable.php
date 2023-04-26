<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;

class TimeOffDaysLogTable extends Model
{
    protected $table = "time_off_days_log";
    protected $primaryKey = "time_off_days_log_id";
    protected $fillable = [
        "time_off_days_log_id",
        "time_off_days_id",
        "time_off_days_action",
        "time_off_days_title",
        "time_off_days_content",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
    ];
    public $timestamps = false;
    
    /**
     * Get danh sách ngày phép
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = []){
        $oSelect = $this
            ->select(
                $this->table.'.time_off_days_log_id',
                $this->table.'.time_off_days_id',
                $this->table.'.time_off_days_action',
                $this->table.'.time_off_days_title',
                $this->table.'.time_off_days_content',
                $this->table.'.created_at',
            );

            if (isset($data['time_off_days_id'])) {
                $id = $data['time_off_days_id'];
                $oSelect->where($this->table.".time_off_days_id", "=",  $id);
            }
        return $oSelect->get();
        // get số trang
        // $page = (int)($data['page'] ?? 1);
        // return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Thêm đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        
        $add = $this->create($data);
        return $add->time_off_days_log_id;
    }


    /**
     * Xóa tất cả file
     *
     * @param $daysId
     * @return mixed
     */
    public function remove($daysId)
    {
        return $this->where("time_off_days_id", $daysId)->delete();
    }
}
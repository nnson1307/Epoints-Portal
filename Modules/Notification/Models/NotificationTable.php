<?php


namespace Modules\Notification\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class NotificationTable extends Model
{
    use ListTableTrait;
    protected $table = 'notification';
    protected $primaryKey = 'notification_id';
    protected $fillable=[
        'notification_detail_id', 'tenant_id','brand_id','user_id', 'notification_avatar', 'notification_title',
        'notification_message', 'notification_type_id', 'is_read', 'is_brand', 'created_at', 'updated_at'
    ];

    /**
     * Danh sách đã click thông báo
     *
     * @return mixed
     */
    public function getListIsRead($filter)
    {
        $oSelect = $this->selectRaw('count(*) as total_is_read, notification_detail_id')
            ->where('is_read', 1)
            ->groupBy('notification_detail_id');

        if (isset($filter['tenant_id'])) {
            $oSelect->where('tenant_id', $filter['tenant_id']);
        }
        return $oSelect->get();
    }

    /**
     * Danh sách đã gửi
     *
     * @return mixed
     */
    public function getListIsSend($filter)
    {
        $oSelect = $this->selectRaw('count(*) as total_is_send, notification_detail_id')
            ->groupBy('notification_detail_id');

        if (isset($filter['tenant_id'])) {
            $oSelect->where('tenant_id', $filter['tenant_id']);
        }
        return $oSelect->get();
    }

    public function getFirst($detail_id)
    {
        return $this->where('notification_detail_id', $detail_id)->first();
    }

    public function getAllByDetailTemplate($arrId)
    {
        $result = $this
            ->select('notification_detail_id')
            ->selectRaw('SUM(CASE WHEN is_read=1 THEN 1 ELSE 0 END) as is_read')
            ->selectRaw('SUM(CASE WHEN is_read=0 THEN 1 ELSE 0 END) as is_not_read')
            ->selectRaw('COUNT(*) as total')
            ->whereIn('notification_detail_id', $arrId)
            ->groupBy('notification_detail_id')->get();
        if($result->count()){
            return $result->keyBy('notification_detail_id')->toArray();
        }
        return [];
    }

    public function getCustomerApproach($filter)
    {
        $data = $this->select(
            DB::raw("COUNT(*) as sum_customer")
        )
            ->leftJoin("notification_template", "notification_template.notification_detail_id", "{$this->table}.notification_detail_id");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween('created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if(isset($filter['option_notify']) != ''){
            $data->where("notification_template.notification_template_id", $filter['option_notify']);
        }
        return $data->get()->toArray();
    }
    public function getCustomerApproachPerformance($filter)
    {
        $data = $this->select(
            DB::raw("COUNT(*) as sum_customer")
        )
            ->leftJoin("notification_template", "notification_template.notification_detail_id", "{$this->table}.notification_detail_id")
            ->leftJoin("notification_detail", "notification_detail.notification_detail_id", "{$this->table}.notification_detail_id")
            ->leftJoin("staffs", "staffs.staff_id", "notification_detail.created_by")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != ""){
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != ""){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != ""){
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->first();
    }
}

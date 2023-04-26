<?php


namespace Modules\Notification\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class NotificationTemplateTable extends Model
{
    use ListTableTrait;
    public $timestamps = false;
    protected $table = "notification_template";
    protected $primaryKey = 'notification_template_id';
    protected $fillable = [
        "notification_template_id",
        "notification_detail_id",
        "notification_type_id",
        "cost",
        "title",
        "title_short",
        "description",
        "action_group",
        "action_name",
        "from_type",
        "from_type_object",
        "send_type",
        "send_at",
        "schedule_option",
        "schedule_value",
        "schedule_value_type",
        "send_status",
        "is_actived",
        "is_deal_created"
    ];

    /**
     * 1-1 với template
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detail()
    {
        return $this->belongsTo(
            'Modules\Admin\Models\NotificationDetailTable',
            'notification_detail_id',
            'notification_detail_id'
        );
    }

    /**
     * 1-1 với queue
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function queue()
    {
        return $this->belongsTo(
            'Modules\Notification\Models\NotificationQueueTable',
            'notification_detail_id',
            'notification_detail_id'
        );
    }

    /**
     * Insert thông báo
     *
     * @param $data
     * @return mixed
     */
    public function createNotiTemplate($data)
    {
        return $this->create($data)->notification_template_id;
    }

    /**
     * Cập nhật thông báo
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateNotiTemplate($id, $data)
    {
        return $this->where('notification_detail_id', $id)->update($data);
    }

    /**
     * Xóa
     *
     * @param $id
     * @return mixed
     */
    public function deleteNotiTemplate($id)
    {
        return $this->where('notification_detail_id', $id)->delete();
    }

    /**
     * Lấy chi tiết theo notification detail id
     *
     * @param $detail_id
     * @return mixed
     */
    public function getOneByDetailId($detail_id)
    {
        return $this->where('notification_detail_id', $detail_id)->first();
    }

    public function getListCore(&$filter = [])
    {
        $oSelect = $this->select('notification_template.*','notification_queue.id as queue_id',
            'notification_queue.send_at as queue_send_at')
            ->join('notification_detail',
                'notification_detail.notification_detail_id','=','notification_template.notification_detail_id')
            ->leftJoin('notification_queue',
                'notification_queue.notification_detail_id','=','notification_template.notification_detail_id');
//            ->where('notification_detail.is_brand', $filter['is_brand']);

        //Filter tiêu đề
        if (isset($filter['search_title']) && $filter['search_title'] != null) {
            $title = $filter['search_title'];
            $oSelect->where('title', 'like', '%'.$title.'%');
            unset($filter['search_title']);
        }
//
//        if (isset($filter['send_time']) && $filter['send_time'] != null) {
//            $arrTime = explode(" / ", $filter['send_time']);
//            $from = Carbon::parse($arrTime[0])->format('Y-m-d H:i:s');
//            $to = Carbon::parse($arrTime[1])->format('Y-m-d H:i:s');
//            $oSelect->whereBetween('send_at', [$from, $to]);
//            unset($filter['send_time']);
//        }
//
//        if (isset($filter['is_send']) && $filter['is_send'] != null && $filter['is_send'] != -1) {
//            $isSend = $filter['is_send'];
//            if ($isSend == 'sent') { // đã gửi
//                $oSelect->where('notification_template.is_actived', 1);
//            } elseif ($isSend == 'pending') { // chờ gửi
//                $oSelect->where('notification_template.is_actived', 1);
//                $oSelect->whereHas('queue', function ($query) {
//                    $query->where('id', '!=', 0);
//                });
//            } else { // chưa gửi
//                $oSelect->where('notification_template.is_actived', 0);
//            }
//            unset($filter['is_send']);
//        }
//        if (isset($filter['tenant_id'])) {
//            $oSelect->where('notification_detail.tenant_id', $filter['tenant_id']);
//        }


        unset($filter['is_brand'], $filter["display"]);

        return $oSelect->orderBy('notification_detail.updated_at', 'DESC');
    }

    /**
     * Các noti trong khoảng time
     *
     * @param $filter
     * @return mixed
     */
    public function getOptionNotify($filter)
    {
        $data = $this->select(
            "{$this->table}.notification_template_id",
            "{$this->table}.title"
        )
        ->leftJoin("notification_detail", "notification_detail.notification_detail_id", "{$this->table}.notification_detail_id")
            ->where("{$this->table}.send_status", "=", "sent");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween('notification_detail.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $data->get()->toArray();
    }

    /**
     * Chi phí của các chiến dịch noti trong khoảng time filter
     *
     * @param $filter
     * @return mixed
     */
    public function getCostReport($filter)
    {
        $data = $this->select(
            DB::raw("DATE_FORMAT(notification_template.send_at,'%d/%m/%Y') as created_group"),
            DB::raw("SUM({$this->table}.cost) as cost")
        )
            ->leftJoin("notification_detail", "notification_detail.notification_detail_id", "{$this->table}.notification_detail_id")
            ->whereNotNull("{$this->table}.cost")
            ->where("{$this->table}.send_status", "=", "sent")
            ->groupBy(DB::raw("DATE_FORMAT(notification_template.send_at,'%d/%m/%Y')"));
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween('notification_template.send_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $data->get()->toArray();
    }
}
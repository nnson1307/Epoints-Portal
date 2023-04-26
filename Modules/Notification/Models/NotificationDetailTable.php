<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;

class NotificationDetailTable extends Model
{
    use ListTableTrait;
    protected $table = 'notification_detail';
    protected $primaryKey = 'notification_detail_id';
    protected $fillable=[
        'tenant_id', 'background', 'content', 'action', 'action_params', 'is_brand', 'action_name',
        'created_at', 'created_by', 'updated_at', 'updated_by', 'notification_detail_id'
    ];

    /**
     * Relationship
     */

    /**
     * 1-1 với template
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(
            'Modules\Notification\Models\NotificationTemplateTable',
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
     * End relationship
     */

    /**
     * Insert thông báo
     *
     * @param $data
     * @return mixed
     */
    public function createNotiDetailGetId($data)
    {
        return $this->insertGetId($data);
    }

    /**
     * Insert thông báo
     *
     * @param $data
     * @return mixed
     */
    public function createNotiDetail($data)
    {
        return $this->create($data);
    }

    /**
     * Lấy chi tiết
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|Model|NotificationDetailTable|object|null
     */
    public function getOne($id)
    {
        return $this->with('template', 'queue')
                    ->where('notification_detail_id', $id)
                    ->first();
    }

    /**
     * Cập nhật thông báo
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateNotiDetail($id, $data)
    {
        return $this->where('notification_detail_id', $id)->update($data);
    }

    /**
     * Lấy danh sách
     *
     * @param array $filter
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getList($filter = [])
    {
        $list = $this->with('template', 'queue');
        if (isset($filter['title']) && $filter['title'] != null) {
            $title = $filter['title'];
            $list->whereHas('template', function ($query) use ($title) {
                $query->where('title', 'like', '%'.$title.'%');
            });
            unset($filter['title']);
        }
        if (isset($filter['is_send']) && $filter['is_send'] != null && $filter['is_send'] != -1) {
            $isSend = $filter['is_send'];
            if ($isSend == 'sent') { // đã gửi
                $list->doesntHave('queue')->whereHas('template', function ($query) {
                    $query->where('is_actived', 1);
                });
            } elseif ($isSend == 'pending') { // chờ gửi
                $list->whereHas('template', function ($query) {
                    $query->where('is_actived', 1);
                });
                $list->whereHas('queue', function ($query) {
                    $query->where('id', '!=', 0);
                });
            } else { // chưa gửi
                $list->whereHas('template', function ($query) {
                    $query->where('is_actived', 0);
                });
            }
            unset($filter['is_send']);
        }
        if (isset($filter['is_actived']) && $filter['is_actived'] != null && $filter['is_actived'] != -1) {
            $isActived = $filter['is_actived'];
            $list->whereHas('template', function ($query) use ($isActived) {
                $query->where('is_actived', $isActived);
            });
            unset($filter['is_actived']);
        }
        if (isset($filter['send_time']) && $filter['send_time'] != null) {
            $arrTime = explode(" / ", $filter['send_time']);
            $from = Carbon::parse($arrTime[0])->format('Y-m-d H:i:s');
            $to = Carbon::parse($arrTime[1])->format('Y-m-d H:i:s');
            $list->whereHas('template', function ($query) use ($from, $to) {
                $query->whereBetween('send_at', [$from, $to]);
            });
            unset($filter['send_time']);
        }
        $display = (isset($filter['perpage'])) ? $filter['perpage'] : NOTIFICATION_PAGING;
        $page = (isset($filter['page'])) ? $filter['page'] : 1;
        unset($filter['perpage'], $filter['page']);
//
//        if ($filter) {
//            foreach ($filter as $column => $value) {
//                $list->where($column, $value);
//            }
//        }
        if (isset($filter['tenant_id'])) {
            $list->where('tenant_id', $filter['tenant_id']);
        }

        return $list->where('is_brand', $filter['is_brand'])
            ->orderBy('updated_at', 'DESC')->paginate(
            $display,
            ['*'],
            'page',
            $page
        );
    }

    /**
     * Xóa
     *
     * @param $id
     * @return mixed
     */
    public function deleteNotiDetail($id)
    {
        $this->where('notification_detail_id', $id)->delete();

        return 1;
    }
}

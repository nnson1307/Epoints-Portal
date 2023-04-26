<?php

namespace Modules\Notification\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class StaffNotificationTable extends Model
{
    use ListTableTrait;
    protected $table = "staff_notification";
    protected $primaryKey = "staff_notification_id";
    protected $fillable = [
        'staff_notification_id',
        'staff_notification_detail_id',
        'user_id',
        'notification_avatar',
        'notification_title',
        'notification_message',
        'is_read',
        'is_new',
        'created_at',
        'updated_at',
    ];

    const IS_READ = 1;
    const IS_NEW = 0;
    const IS_OLD = 1;

    public function _getList($filter = [])
    {
        $select = $this->select(
            "{$this->table}.staff_notification_id",
            "{$this->table}.staff_notification_detail_id",
            "{$this->table}.user_id",
            "{$this->table}.notification_avatar",
            "{$this->table}.notification_title",
            "{$this->table}.notification_message",
            "{$this->table}.is_read",
            "{$this->table}.is_new",
            "{$this->table}.created_at",
            "snd.action",
            "snd.action_params",
            "snd.action_name",
            "snd.content"
        )
            ->join("staff_notification_detail as snd", "snd.staff_notification_detail_id", "=", "{$this->table}.staff_notification_detail_id")
            ->where("{$this->table}.staff_id", Auth()->user()->staff_id)
            ->orderBy("{$this->table}.staff_notification_id", "desc");
        // filter tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where('packed_code', 'like', '%' . $search . '%')
                    ->orWhere('packed_name', 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        if (Auth::user()->is_admin != 1) {
            $select->where(function($sql){
                $sql->where("{$this->table}.branch_id", Auth()->user()->branch_id)
                    ->orWhereNull("{$this->table}.branch_id");
            });
        }

        return $select;
    }

    /**
     * Lấy tất cả thông báo
     *
     * @return mixed
     */
    public function getAllNotification()
    {
        $ds = $this->select(
            "{$this->table}.staff_notification_id",
            "{$this->table}.staff_notification_detail_id",
            "{$this->table}.user_id",
            "{$this->table}.notification_avatar",
            "{$this->table}.notification_title",
            "{$this->table}.notification_message",
            "{$this->table}.is_read",
            "{$this->table}.is_new",
            "{$this->table}.created_at",
            "snd.action",
            "snd.action_params",
            "snd.action_name",
            "snd.content"
        )
            ->join("staff_notification_detail as snd", "snd.staff_notification_detail_id", "=", "{$this->table}.staff_notification_detail_id");

        if (Auth::user()->is_admin != 1) {
            $ds->where(function($sql){
                $sql->where("{$this->table}.branch_id", Auth()->user()->branch_id)
                    ->orWhereNull("{$this->table}.branch_id");
            });
        }

        return $ds->get();
    }

    /**
     * lấy thông báo mới
     *
     * @return mixed
     */
    public function getNotificationNew()
    {
        $select = $this->select(
            "{$this->table}.staff_notification_id",
            "{$this->table}.staff_notification_detail_id",
            "{$this->table}.user_id",
            "{$this->table}.notification_avatar",
            "{$this->table}.notification_title",
            "{$this->table}.notification_message",
            "{$this->table}.is_read",
            "{$this->table}.is_new",
            "{$this->table}.created_at",
            "snd.action",
            "snd.action_params",
            "snd.action_name",
            "snd.content"
        )
            ->join("staff_notification_detail as snd", "snd.staff_notification_detail_id", "=", "{$this->table}.staff_notification_detail_id")
            ->where("is_new", self::IS_NEW)
            ->where("{$this->table}.staff_id", Auth()->user()->staff_id);

        if (Auth::user()->is_admin != 1) {
            $select->where(function($sql){
                $sql->where("{$this->table}.branch_id", Auth()->user()->branch_id)
                    ->orWhereNull("{$this->table}.branch_id");
            });
        }

        return $select->get();
    }

    /**
     * Cập nhật trạng thái
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where("{$this->table}.staff_notification_id", $id)->update($data);
    }

    /**
     * Lấy số lượng thông báo mới
     *
     * @return mixed
     */
    public function countNotificationNew()
    {
        $ds = $this
            ->select(
                DB::raw('COUNT(staff_notification_id) as number_of_notification')
            )
            ->join("staff_notification_detail as snd", "snd.staff_notification_detail_id", "=", "{$this->table}.staff_notification_detail_id")
            ->where('is_new', self::IS_NEW)
            ->where("staff_id", Auth()->user()->staff_id);

        if (Auth::user()->is_admin != 1) {
            $ds->where(function($sql){
                $sql->where("{$this->table}.branch_id", Auth()->user()->branch_id)
                    ->orWhereNull("{$this->table}.branch_id");
            });
        }

        return $ds->first();
    }

    /**
     * Thông tin
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $ds = $this->select(
            "{$this->table}.staff_notification_id",
            "{$this->table}.staff_notification_detail_id",
            "{$this->table}.user_id",
            "{$this->table}.notification_avatar",
            "{$this->table}.notification_title",
            "{$this->table}.notification_message",
            "{$this->table}.is_read",
            "{$this->table}.is_new",
            "{$this->table}.created_at",
            "snd.action",
            "snd.action_params",
            "snd.action_name",
            "snd.content"
        )
            ->join("staff_notification_detail as snd", "snd.staff_notification_detail_id", "=", "{$this->table}.staff_notification_detail_id")
            ->where("{$this->table}.staff_id", Auth()->user()->staff_id)
            ->where("{$this->table}.staff_notification_id", $id);

        if (Auth::user()->is_admin != 1) {
            $ds->where(function($sql){
                $sql->where("{$this->table}.branch_id", Auth()->user()->branch_id)
                    ->orWhereNull("{$this->table}.branch_id");
            });
        }

        return $ds->first();
    }

    /**
     * Clear những thông báo mới khi click vào chuông
     *
     * @return mixed
     */
    public function clearNotifyNew()
    {
        $ds = $this
            ->where("is_new", self::IS_NEW)
            ->where("{$this->table}.staff_id", Auth()->user()->staff_id);

        if (Auth::user()->is_admin != 1) {
            $ds->where(function($sql){
                $sql->where("{$this->table}.branch_id", Auth()->user()->branch_id)
                    ->orWhereNull("{$this->table}.branch_id");
            });
        }

        return $ds->update([
            'is_new' => self::IS_OLD
        ]);
    }
}
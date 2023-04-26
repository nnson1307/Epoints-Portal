<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2021
 * Time: 13:28
 */

namespace Modules\Customer\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerRemindUseTable extends Model
{
    use ListTableTrait;
    protected $table = "customer_remind_use";
    protected $primaryKey = "customer_remind_use_id";
    protected $fillable = [
        "customer_remind_use_id",
        "customer_id",
        "order_id",
        "object_type",
        "object_id",
        "object_code",
        "object_name",
        "sent_at",
        "is_finish",
        "is_queue",
        "note",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const NOT_CANCEL = "ordercancle";

    /**
     * Danh sách dự kiến nhắc sử dụng
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_remind_use_id",
                "cs.full_name",
                "{$this->table}.object_type",
                "{$this->table}.object_name",
                "{$this->table}.sent_at",
                "{$this->table}.is_finish",
                "ord.order_code"
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->join("orders as ord", "ord.order_id", "=", "{$this->table}.order_id")
            ->where("cs.is_actived", self::IS_ACTIVE)
            ->where("cs.is_deleted", self::NOT_DELETED)
            ->where("ord.is_deleted", self::NOT_DELETED)
            ->where("ord.process_status", "<>", self::NOT_CANCEL)
            ->orderBy("{$this->table}.customer_remind_use_id", "desc");

        // filter tên KH + tên SP
        if (!empty($filter['search'])) {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("cs.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.object_name", 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (!empty($filter["sent_at"])) {
            $arr_filter = explode(" - ", $filter["sent_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.sent_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        unset($filter["sent_at"]);

        return $ds;
    }

    /**
     * Lấy thông tin dự kiến nhắc sử dụng
     *
     * @param $remindUseId
     * @return mixed
     */
    public function getInfo($remindUseId)
    {
        return $this
            ->select(
                "{$this->table}.customer_remind_use_id",
                "cs.full_name",
                "{$this->table}.object_type",
                "{$this->table}.object_name",
                "{$this->table}.sent_at",
                "{$this->table}.is_finish",
                "ord.order_code",
                "{$this->table}.note"
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->join("orders as ord", "ord.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.customer_remind_use_id", $remindUseId)
            ->first();
    }

    /**
     * Chỉnh sửa dự kiến nhắc sử dụng
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("customer_remind_use_id", $id)->update($data);
    }
}
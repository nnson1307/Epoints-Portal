<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 29/07/2021
 * Time: 10:57
 */

namespace Modules\OnCall\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class HistoryTable extends Model
{
    use ListTableTrait;
    protected $table = "oc_histories";
    protected $primaryKey = "history_id";

    const CALL = "call";

    /**
     * Lấy ds lịch sử cuộc gọi
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $lang = app()->getLocale();

        $ds = $this
            ->select(
                "{$this->table}.history_id",
                "{$this->table}.uid",
                "sf.full_name as staff_name",
                "{$this->table}.object_id_call",
                "{$this->table}.object_id",
                "{$this->table}.object_name",
                "{$this->table}.object_phone",
                "{$this->table}.extension_number",
                "{$this->table}.start_time",
                "{$this->table}.end_time",
                "{$this->table}.total_reply_time",
                "{$this->table}.status",
                "{$this->table}.history_type",
                "sc.source_name_{$lang} as source_name",
                "{$this->table}.link_record",
                "cpo_customer_care.content as content_care_lead",
                "cpo_deal_care.content as content_care_deal",
                "{$this->table}.source_code"
            )
            ->leftJoin("oc_sources as sc", "sc.source_code", "=", "{$this->table}.source_code")
            ->leftJoin("staffs as sf", "sf.staff_id", "=", "{$this->table}.object_id_call")
            ->leftJoin("cpo_customer_care", function ($join) {
                $join->on("cpo_customer_care.object_id", "=", "{$this->table}.history_id")
                    ->where("cpo_customer_care.care_type", self::CALL);
            })
            ->leftJoin("cpo_deal_care", function ($join) {
                $join->on("cpo_deal_care.object_id", "=", "{$this->table}.history_id")
                    ->where("cpo_deal_care.care_type", self::CALL);
            })
            ->orderBy("{$this->table}.history_id", "desc")
            ->groupBy("{$this->table}.history_id");

        // filter extension, sđt người nhận
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.extension_number", 'like', '%' . $search . '%')
                    ->orWhere("sf.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.object_phone", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.object_name", 'like', '%' . $search . '%');
            });
        }

        // filter người gọi
        if (isset($filter['object_id_call']) && $filter['object_id_call'] != "") {
            $ds->where("{$this->table}.object_id_call", $filter['object_id_call']);

            unset($filter['object_id_call']);
        }

        // filter nguồn cuộc gọi
        if (isset($filter['source_code']) && $filter['source_code'] != "") {
            $ds->where("{$this->table}.source_code", $filter['source_code']);

            unset($filter['source_code']);
        }

        // filter loại cuộc gọi
        if (isset($filter['history_type']) && $filter['history_type'] != "") {
            $ds->where("{$this->table}.history_type", $filter['history_type']);

            unset($filter['history_type']);
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) &&  $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where("history_id", $id)->update($data);
    }
    /**
     * Chi tiết cuộc gọi
     *
     * @param $historyId
     * @return mixed
     */
    public function getInfo($historyId)
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "{$this->table}.history_id",
                "{$this->table}.uid",
                "sf.full_name as staff_name",
                "{$this->table}.object_name",
                "{$this->table}.object_phone",
                "{$this->table}.extension_number",
                "{$this->table}.start_time",
                "{$this->table}.end_time",
                "{$this->table}.total_reply_time",
                "{$this->table}.status",
                "{$this->table}.history_type",
                "sc.source_name_{$lang} as source_name",
                "{$this->table}.ring_time",
                "{$this->table}.reply_time",
                "{$this->table}.total_ring_time",
                "{$this->table}.postage",
                "{$this->table}.error_text",
                "{$this->table}.link_record"
            )
            ->leftJoin("oc_sources as sc", "sc.source_code", "=", "{$this->table}.source_code")
            ->leftJoin("staffs as sf", "sf.staff_id", "=", "{$this->table}.object_id_call")
            ->where("{$this->table}.history_id", $historyId)
            ->first();
    }

    /**
     * Lấy lịch sử cuộc gọi (báo cáo nhân viên)
     *
     * @param $startTime
     * @param $endTime
     * @param $status
     * @param $historyType
     * @param $staffId
     * @return mixed
     */
    public function getHistoryReportStaff($startTime, $endTime, $status, $historyType, $staffId)
    {
        $ds = $this
            ->select(
                "{$this->table}.history_id",
                "{$this->table}.object_id_call",
                "staffs.full_name as object_name_call",
                "{$this->table}.object_name",
                "{$this->table}.object_phone",
                "{$this->table}.start_time",
                "{$this->table}.end_time",
                "{$this->table}.total_reply_time",
                "{$this->table}.history_type",
                "{$this->table}.status",
                "{$this->table}.created_at",
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%d/%m') as created_at_format"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%H') as time_at_format"),
                DB::raw("DATE_FORMAT({$this->table}.created_at, '%m/%Y') as month_at_format")
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.object_id_call")
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

        //Filter theo trạng thái cuộc gọi
        if (isset($status) && $status != null) {
            $ds->where("{$this->table}.status", intval($status));
        }

        //Filter theo loại cuộc gọi
        if (isset($historyType) && $historyType != null) {
            $ds->where("{$this->table}.history_type", $historyType);
        }

        //Filter theo nhân viên
        if (isset($staffId) && $staffId != null) {
            $ds->where("{$this->table}.object_id_call", $staffId);
        }

        return $ds->get();
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/07/2022
 * Time: 14:14
 */

namespace Modules\Kpi\Models;


use Illuminate\Database\Eloquent\Model;

class OcHistoryTable extends Model
{
    protected $table = "oc_histories";
    protected $primaryKey = "history_id";

    const STATUS_SUCCESS = 1;
    const LEAD = "lead";

    /**
     * Lấy lịch sử cuộc gọi thành công
     *
     * @param $historyId
     * @return mixed
     */
    public function getHistoryCall($historyId)
    {
        return $this
            ->where("history_id", $historyId)
            ->where("status", self::STATUS_SUCCESS)
            ->first();
    }

    /**
     * Lấy lịch sử chăm sóc lead trong khoảng thời gian áp dụng kpi
     *
     * @param $saleId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getHistoryCallByLead($saleId, $startDate, $endDate)
    {
        return $this
            ->select(
                "{$this->table}.history_id",
                "{$this->table}.total_reply_time"
            )
            ->join("cpo_customer_lead as lead", "lead.customer_lead_id", "=", "{$this->table}.object_id")
            ->where("{$this->table}.source_code", self::LEAD)
            ->where("lead.sale_id", $saleId)
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where("status", self::STATUS_SUCCESS)
            ->get();
    }

    /**
     * Lấy lịch sử chăm sóc lead trong ngày
     *
     * @param $saleId
     * @param $day
     * @return mixed
     */
    public function getHistoryCallByLeadInDay($saleId, $day)
    {
        return $this
            ->select(
                "{$this->table}.history_id",
                "{$this->table}.total_reply_time"
            )
            ->join("cpo_customer_lead as lead", "lead.customer_lead_id", "=", "{$this->table}.object_id")
            ->where("{$this->table}.source_code", self::LEAD)
            ->where("lead.sale_id", $saleId)
            ->whereDate("{$this->table}.created_at", $day)
            ->where("status", self::STATUS_SUCCESS)
            ->get();
    }
}
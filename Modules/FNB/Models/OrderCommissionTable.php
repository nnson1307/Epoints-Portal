<?php

namespace Modules\FNB\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class OrderCommissionTable extends Model
{
    use ListTableTrait;
    protected $table = 'order_commission';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'order_detail_id',
        'refer_id',
        'staff_id',
        'refer_money',
        'staff_money',
        'status',
        'staff_commission_rate',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'note'
    ];


    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    /**
     * @param $order_detail_id
     * @return mixed
     */
    public function getItemByOrderDetail($order_detail_id)
    {
        return $this->where('order_detail_id', $order_detail_id)->first();
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    /**
     * @param $customer_id
     * @return mixed
     */
    public function getCommissionByCustomer($customer_id)
    {
        $ds = $this
            ->leftJoin('order_details', 'order_details.order_detail_id', '=', 'order_commission.order_detail_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'order_commission.created_by')
            ->select(
                'orders.order_code',
                'staffs.full_name',
                'order_commission.refer_money',
                'order_commission.status',
                'order_commission.created_at'
            )
            ->where('order_commission.refer_id', $customer_id)->get();
        return $ds;
    }

    /**
     * @param $time
     * @return mixed
     */
    public function reportStaffCommission($time)
    {
        $ds = $this->select(
            'staff_id',
            'staff_money'
        )
            ->whereNotNull('staff_id')
            ->where('status', 'approve');
        if (isset($time)) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    /**
     * Danh sách hoa hồng nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.id",
                "{$this->table}.order_detail_id",
                "{$this->table}.refer_id",
                "{$this->table}.staff_id",
                "{$this->table}.refer_money",
                "{$this->table}.staff_money",
                "{$this->table}.status",
                "{$this->table}.staff_commission_rate",
                "{$this->table}.created_at",
                "{$this->table}.note",
                "staffs.full_name as staff_name",
                "orders.order_code",
                "orders.order_id"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("order_details", "order_details.order_detail_id", "=", "{$this->table}.order_detail_id")
            ->leftJoin("orders", "orders.order_id", "=", "order_details.order_id")
            ->whereNotNull("{$this->table}.staff_money")
            ->where("{$this->table}.staff_money", ">", 0)
            ->orderBy("{$this->table}.id", "desc");

        // filter tên, mã nhân viên
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("staffs.full_name", 'like', '%' . $search . '%')
                    ->orWhere("staffs.staff_code", 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $ds;
    }

    /**
     * Chi tiết order commission
     *
     * @param $id
     * @return mixed
     */
    public function getDetail($id)
    {
        return $this->select(
            "{$this->table}.id",
            "{$this->table}.order_detail_id",
            "{$this->table}.refer_id",
            "{$this->table}.staff_id",
            "{$this->table}.refer_money",
            "{$this->table}.staff_money",
            "{$this->table}.status",
            "{$this->table}.staff_commission_rate",
            "{$this->table}.created_at",
            "{$this->table}.note"
        )
            ->where("{$this->table}.{$this->primaryKey}", $id)
            ->first();
    }

    /**
     * Xoá
     *
     * @param $id
     * @return mixed
     */
    public function deleteCommission($id)
    {
        return $this->where("{$this->table}.{$this->primaryKey}", $id)->delete();
    }

    /**
     * Lấy tất cả hoa hồng theo nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getListStaffCommissionByStaffId($staffId)
    {
        return $this->select(
            "{$this->table}.id",
            "{$this->table}.order_detail_id",
            "{$this->table}.refer_id",
            "{$this->table}.staff_id",
            "{$this->table}.refer_money",
            "{$this->table}.staff_money",
            "{$this->table}.status",
            "{$this->table}.staff_commission_rate",
            "{$this->table}.created_at",
            "orders.order_code",
            "orders.order_id",
            "order_details.object_name"
        )
            ->leftJoin("order_details", "order_details.order_detail_id", "=", "{$this->table}.order_detail_id")
            ->leftJoin("orders", "orders.order_id", "=", "order_details.order_id")
            ->whereNotNull("{$this->table}.staff_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("{$this->table}.staff_money", "<>", 0)
            ->orderBy("{$this->table}.id", "desc")
            ->get();
    }
}
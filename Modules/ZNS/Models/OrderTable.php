<?php


namespace Modules\ZNS\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = [
        "order_id",
        "order_code",
        "customer_id",
        "branch_id",
        "refer_id",
        "total",
        "discount",
        "amount",
        "tranport_charge",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "process_status",
        "order_description",
        "customer_description",
        "payment_method_id",
        "order_source_id",
        "transport_id",
        "voucher_code",
        "discount_member",
        "is_deleted",
        "customer_contact_code",
        "receive_at_counter",
        "delivery_request_date",
        "blessing"
    ];

//    protected $casts = [
//        'total' => 'float',
//        'discount' => 'float',
//        'amount' => 'float',
//        'discount_member' => 'float'
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "updated_at"
    ];

    /**
     * Lấy danh sách đơn hàng
     *
     * @param $filter
     * @param $customerId
     * @return mixed
     */
    public function getOrders($filter, $customerId)
    {
        $ds = $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "branches.branch_name",
                "refer.full_name as refer_name",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.process_status",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.created_at as order_date",
                "deliveries.contact_address",
                DB::raw("(CASE
                    WHEN  deliveries.is_actived = 0 THEN NULL
                    WHEN  deliveries.is_actived = 1 THEN deliveries.delivery_status
                    END
                ) as delivery_status"),
                "deliveries.is_actived as delivery_active",
                "{$this->table}.created_at"
            )
            ->leftJoin("branches", function ($join) {
                $join->on("branches.branch_id", "=", "{$this->table}.branch_id")
                    ->where("branches.is_deleted", 0);
            })
            ->leftJoin("customers as refer", "refer.customer_id", "=", "{$this->table}.refer_id")
            ->leftJoin("deliveries", "deliveries.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", 0)
            ->where("branches.is_deleted", 0)
            ->orderBy("{$this->table}.created_at", "desc");
        // get số trang
        $page = (int)($filter["page"] ?? 1);

        // filter type
//        if ($filter["type"] == "current") {
//            $ds->where(function ($query) {
//                $query->whereNotNull("deliveries.delivery_status")
//                    ->whereIn("deliveries.delivery_status", ["packing", "preparing", "delivering"]);
//            });
//        } else if ($filter["type"] == "older") {
//            $ds->where(function ($query) {
//                $query->whereNull("deliveries.delivery_status")
//                    ->orWhere("deliveries.delivery_status", "delivered");
//            });
//        }

        // filter branch
        if (isset($filter["branch_id"]) && $filter["branch_id"] > 0) {
            $ds->where("{$this->table}.branch_id", $filter["branch_id"]);
        }

        // filter created at
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d");
            $ds->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        }

        // filter status
        if (isset($filter["status"]) && $filter["status"] != null) {
            switch ($filter["status"]) {
                case 'new':
                    $ds->where("{$this->table}.process_status", "new");
                    break;
                case 'packing':
                    $ds->where(function ($query) {
                        $query->whereRaw("deliveries.delivery_status IS NULL and {$this->table}.process_status = 'confirmed' ")
                            ->orWhereRaw("deliveries.delivery_status IS NOT NULL and deliveries.delivery_status = 'packing' and deliveries.is_actived = 1 and {$this->table}.process_status <> 'new' ");
                    });
                    break;
                case 'delivering':
                    $ds->whereRaw("deliveries.delivery_status IS NOT NULL and deliveries.delivery_status = 'delivering' ");
                    break;
                case 'ordercomplete':
                    $ds->where(function ($query) {
                        $query->whereRaw("deliveries.delivery_status IS NULL and {$this->table}.process_status = 'pay-half' ")
                            ->orWhereRaw("deliveries.delivery_status IS NULL and {$this->table}.process_status = 'paysuccess' ")
                            ->orWhereRaw("deliveries.delivery_status IS NOT NULL and deliveries.delivery_status = 'delivered' ");
                    });
                    break;
                case 'ordercancle':
                    $ds->where("{$this->table}.process_status", 'ordercancle');
                    break;
            }
        }

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

    /**
     * Thông tin đơn hàng
     *
     * @param $orderId
     * @param $customerId
     * @return mixed
     */
    public function orderInfo($orderId, $customerId)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "branches.branch_name",
                "refer.full_name as refer_name",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.tranport_charge",
                "{$this->table}.amount",
                "{$this->table}.process_status",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.created_at as order_date",
                "deliveries.contact_address",
//                "deliveries.delivery_status",
                DB::raw("(CASE
                    WHEN  deliveries.is_actived = 0 THEN ''
                    WHEN  deliveries.is_actived = 1 THEN deliveries.delivery_status
                    END
                ) as delivery_status"),
                "deliveries.is_actived as delivery_active",
                "customer_contacts.contact_name",
                "customer_contacts.contact_phone",
                "customer_contacts.contact_email",
                "customer_contacts.full_address",
                "payment_method.payment_method_name_vi as payment_method_name",
                "payment_method.payment_method_code",
                "{$this->table}.order_description",
                "{$this->table}.payment_method_id",
                "customer_contacts.postcode",
                "province.name as province_name",
                "district.name as district_name",
                "{$this->table}.created_at",
                "{$this->table}.delivery_request_date",
                "{$this->table}.blessing"
            )
            ->leftJoin("branches", function ($join) {
                $join->on("branches.branch_id", "=", "{$this->table}.branch_id")
                    ->where("branches.is_deleted", 0);
            })
            ->leftJoin("customers as refer", "refer.customer_id", "=", "{$this->table}.refer_id")
            ->leftJoin("deliveries", "deliveries.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customer_contacts", "customer_contacts.customer_contact_code", "=", "{$this->table}.customer_contact_code")
            ->leftJoin("payment_method", "payment_method.payment_method_id", "=", "{$this->table}.payment_method_id")
            ->leftJoin("province", "province.provinceid", "=", "customer_contacts.province_id")
            ->leftJoin("district", "district.districtid", "=", "customer_contacts.district_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.order_id", $orderId)
            ->first();
    }

    /**
     * Lấy thông tin đơn hàng bằng mã đơn hàng
     *
     * @param $orderId
     * @param $customerId
     * @return mixed
     */
    public function getInfoById($orderId)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "{$this->table}.branch_id",
                "branches.branch_name",
                "refer.full_name as refer_name",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.tranport_charge",
                "{$this->table}.amount",
                "{$this->table}.process_status",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.created_at as order_date",
                "deliveries.contact_address",
//                "deliveries.delivery_status",
                DB::raw("(CASE
                    WHEN  deliveries.is_actived = 0 THEN ''
                    WHEN  deliveries.is_actived = 1 THEN deliveries.delivery_status
                    END
                ) as delivery_status"),
                "deliveries.is_actived as delivery_active",
                "customer_contacts.contact_name",
                "customer_contacts.contact_phone",
                "customer_contacts.contact_email",
                "customer_contacts.full_address",
                "payment_method.payment_method_name_vi as payment_method_name",
                "{$this->table}.order_description",
                "{$this->table}.payment_method_id",
                "customer_contacts.postcode",
                "province.name as province_name",
                "district.name as district_name",
                "{$this->table}.created_at",
                "{$this->table}.delivery_request_date",
                "{$this->table}.blessing",
                "customers.customer_code",
                "customers.full_name",
                "customers.phone1 as phone",
                "{$this->table}.customer_id"
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("branches", function ($join) {
                $join->on("branches.branch_id", "=", "{$this->table}.branch_id")
                    ->where("branches.is_deleted", 0);
            })
            ->leftJoin("customers as refer", "refer.customer_id", "=", "{$this->table}.refer_id")
            ->leftJoin("deliveries", "deliveries.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customer_contacts", "customer_contacts.customer_contact_code", "=", "{$this->table}.customer_contact_code")
            ->leftJoin("payment_method", "payment_method.payment_method_id", "=", "{$this->table}.payment_method_id")
            ->leftJoin("province", "province.provinceid", "=", "customer_contacts.province_id")
            ->leftJoin("district", "district.districtid", "=", "customer_contacts.district_id")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.order_id", $orderId)
            ->first();
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
        return $add->order_id;
    }

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param array $data
     * @param $orderId
     * @return mixed
     */
    public function edit(array $data, $orderId)
    {
        return $this->where("order_id", $orderId)->update($data);
    }

    /**
     * Đêm số đơn hàng đã xóa trong ngày
     *
     * @param $date
     * @return mixed
     */
    public function numberOrderCancel($date)
    {
        return $this
            ->select(
                "order_id",
                "order_code",
                "process_status"
            )
            ->whereDate("created_at", $date)
            ->where("process_status", "ordercancle")
            ->get()
            ->count();
    }

    /**
     * Lấy thông tin đơn hàng bằng order_code
     *
     * @param $orderCode
     * @return mixed
     */
    public function getOrderByCode($orderCode)
    {
        return $this
            ->select(
                "order_id",
                "order_code",
                "total",
                "discount",
                "amount"
            )
            ->where("order_code", $orderCode)
            ->first();
    }

    /**
     * Thông tin đơn hàng ngoài middleware
     *
     * @param $orderId
     * @param $customerId
     * @return mixed
     */
    public function orderItem($orderId)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "{$this->table}.customer_id",
                "branches.branch_name",
                "refer.full_name as refer_name",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.process_status",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.created_at as order_date",
                "deliveries.contact_address",
                "deliveries.delivery_status",
                "deliveries.is_actived as delivery_active",
                "customer_contacts.contact_name",
                "customer_contacts.contact_phone",
                "customer_contacts.contact_email",
                "customer_contacts.full_address",
                "payment_method.payment_method_name_vi as payment_method_name",
                "{$this->table}.order_description",
                "{$this->table}.receive_at_counter"
            )
            ->join("branches", function ($join) {
                $join->on("branches.branch_id", "=", "{$this->table}.branch_id")
                    ->where("branches.is_deleted", 0);
            })
            ->leftJoin("customers as refer", "refer.customer_id", "=", "{$this->table}.refer_id")
            ->leftJoin("deliveries", "deliveries.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customer_contacts", "customer_contacts.customer_contact_code", "=", "{$this->table}.customer_contact_code")
            ->leftJoin("payment_method", "payment_method.payment_method_id", "=", "{$this->table}.payment_method_id")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.order_id", $orderId)
            ->first();
    }
}
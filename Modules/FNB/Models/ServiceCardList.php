<?php

namespace Modules\FNB\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ServiceCardList extends Model
{
    use ListTableTrait;

    protected $table = "service_card_list";
    protected $primaryKey = "service_card_list_id";
    protected $fillable = [
        'service_card_list_id',
        "created_by",
        'service_card_id',
        'code',
        'is_actived',
        'created_at',
        'actived_at',
        'order_code',
        'price',
        "refer_commission",
        "staff_commission",
        'updated_by',
        'branch_id'
    ];

    const UPDATED_AT = null;
    const IS_ACTIVE = 1;

    public function setUpdatedAt($value)
    {
        // Do nothing.
    }

    public function _getList()
    {
        $oSelect = $this
            ->leftJoin("service_cards", "service_cards.service_card_id", "=", "service_card_list.service_card_id")
            ->leftJoin("branches", "branches.branch_id", "=", "service_card_list.branch_id")
            ->select('branches.branch_name',
                'service_card_list.service_card_list_id',
                "service_card_list.service_card_id",
                "service_cards.name",
                "service_cards.service_card_type",
                "service_cards.price",
                "service_card_list.branch_id as branchId",
                DB::raw("COUNT(service_card_list.service_card_id) as card_count"))
            ->groupBy("service_card_list.service_card_id");
        return $oSelect;
    }

    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->service_card_list_id;
    }

    public function edit(array $data, $id)
    {
        return $this->where('service_card_list_id', $id)->update($data);
    }

    public function getServiceCardListDetail($service_card_list_id)
    {
        $oSelect = self::leftJoin("service_cards", "service_cards.service_card_id", "=", "service_card_list.service_card_id")
            ->leftJoin("branches", "branches.branch_id", "=", "service_card_list.branch_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "service_card_list.created_by")
            ->select('branches.branch_name',
                'service_card_list.service_card_list_id',
                "service_card_list.service_card_id",
                "service_card_list.branch_id",
                "service_cards.name",
                "service_cards.code",
                "service_cards.service_card_type",
                "service_cards.price",
                "service_cards.money",
                "staffs.full_name as staff_name")
            ->where("service_card_list.service_card_list_id", $service_card_list_id);
        return $oSelect->first();
    }

    public function getUnuseCard($service_card_id, $branch_id)
    {
        $oSelect = self::leftJoin("customer_service_cards", "service_card_list.service_card_list_id", "=", "customer_service_cards.service_card_list_id")
            ->select(
                "service_card_list.code",
                "service_card_list.created_at")
            ->where("service_card_list.is_actived", 1)
            ->where("service_card_list.branch_id", $branch_id)
            ->where("service_card_list.service_card_id", $service_card_id);
//                        ->whereNull("customer_service_cards.customer_service_card_id");

        return $oSelect->get();
    }

    public function getInuseCard($filter)
    {
        $oSelect = self::leftJoin("customer_service_cards", "customer_service_cards.service_card_list_id", "=", "service_card_list.service_card_list_id")
            ->leftJoin("customers", "customers.customer_id", "=", "customer_service_cards.customer_id")
            ->select(
                "service_card_list.code",
                "service_card_list.created_at",
                "customers.full_name as customer_name",
                "customer_service_cards.actived_date",
                "customer_service_cards.expired_date")
            ->where("service_card_list.is_actived", 1);
        if (isset($filter['branch_id']) && isset($filter["service_card_id"])) {
            $oSelect->where("service_card_list.branch_id", $filter['branch_id'])
                ->where("service_card_list.service_card_id", $filter["service_card_id"]);
        }
        if (isset($filter["is_actived"])) {
            $oSelect->where("service_card_list.is_actived", $filter["is_actived"]);
        }

        if (isset($filter["actived_date"]) && $filter["actived_date"] != "") {

            $arr_filter = explode(" - ", $filter["actived_date"]);
//            dd($arr_filter);
            $from = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $oSelect->whereBetween('customer_service_cards.actived_date', [$from, $to]);
        }

        if (isset($filter["created_at"]) && $filter["created_at"] != "") {

            $arr_filter = explode(" - ", $filter["created_at"]);
//            dd($arr_filter);
            $from = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $oSelect->whereBetween('service_card_list.created_at', [$from, $to]);
        }

        if (isset($filter["search_keyword"])) {
            $oSelect->where("service_card_list.code", "LIKE", "%" . $filter["search_keyword"] . "%");
        }

        $oSelect->whereNotNull("customer_service_cards.customer_service_card_id");

        return $oSelect->get();
    }

    public function getAllByServiceCard($service_card_id, $filter)
    {

        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        $oSelect = self::leftJoin("branches", "branches.branch_id", "=", "service_card_list.branch_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "service_card_list.created_by")
            ->leftJoin("customer_service_cards", "customer_service_cards.service_card_list_id", "=", "service_card_list.service_card_list_id")
            ->leftJoin("customers", "customers.customer_id", "=", "customer_service_cards.customer_id")
            ->select(
                "branch_name",
                "service_card_list.code",
                "customers.full_name as customer_name",
                "service_card_list.created_at",
                "customer_service_cards.actived_date",
//                            'service_card_list.is_actived',
                "staffs.full_name as staff_name",
                "customer_service_cards.customer_service_card_id"
            )
            ->where("service_card_list.is_actived", 1)
            ->where("service_card_id", $service_card_id);

        if (isset($filter["is_actived"])) {
            $oSelect->where("service_card_list.is_actived", $filter["is_actived"]);
        }

        if (isset($filter["search_keyword"])) {
            $oSelect->where("service_card_list.code", "LIKE", "%" . $filter["search_keyword"] . "%");
        }

        if (isset($filter["staff"]) && $filter["staff"] != "") {
            $oSelect->where("service_card_list.created_by", $filter["staff"]);
        }

        if (isset($filter["customer"]) && $filter["customer"] != "") {
            $oSelect->where("customer_service_cards.customer_id", $filter["customer"]);
        }
        if (isset($filter["branch"]) && $filter["branch"] != "") {
            $oSelect->where("service_card_list.branch_id", $filter["branch"]);
        }

        if (isset($filter["actived_date"]) && $filter["actived_date"] != "") {

            $arr_filter = explode(" - ", $filter["actived_date"]);
//            dd($arr_filter);
            $from = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $oSelect->whereBetween('customer_service_cards.actived_date', [$from, $to]);
        }

        if (isset($filter["created_at"]) && $filter["created_at"] != "") {

            $arr_filter = explode(" - ", $filter["created_at"]);
//            dd($arr_filter);
            $from = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $oSelect->whereBetween('service_card_list.created_at', [$from, $to]);
        }

        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getCodeOrder($branch_id, $id)
    {
        $ds = $this->select('branch_id', 'code')
            ->where('branch_id', $branch_id)
            ->where('service_card_id', $id)->get();
        return $ds;
    }

    //get all table service_card_list.
    public function getAllServiceCardList()
    {
        $select = $this->where("service_card_list.is_actived", 1)->get();
        return $select;
    }

    public function getAll()
    {
        $oSelect = $this
            ->leftJoin("service_cards", "service_cards.service_card_id", "=", "service_card_list.service_card_id")
            ->leftJoin("branches", "branches.branch_id", "=", "service_card_list.branch_id")
            ->select('branches.branch_name',
                'service_card_list.service_card_list_id',
                "service_card_list.service_card_id",
                "service_cards.name",
                "service_cards.service_card_type",
                "service_cards.price",
                "service_card_list.branch_id as branchId",
                DB::raw("COUNT(service_card_list.service_card_id) as card_count"))
            ->groupBy("service_card_list.service_card_id")->get();
        return $oSelect;
    }

    public function getByNameType($name, $type)
    {
        if ($name != null && $type != null) {
            $select = $this
//                ->leftJoin("service_cards", "service_cards.service_card_id", "=", "service_card_list.service_card_id")
                ->select(
                    'service_card_list.service_card_list_id',
                    "service_card_list.service_card_id",
                    "service_cards.name",
                    "service_cards.service_card_type",
                    "service_cards.price",
                    "service_card_list.branch_id as branchId",
                    DB::raw("COUNT(service_card_list.service_card_id) as card_count")
                )
                ->where('service_cards.name', 'like', '%' . $name . '%')
                ->where('service_cards.service_card_type', $type)
                ->get()->toArray();
            return $select;
        }
        if ($name != null) {
            $select = $this
                ->leftJoin("service_cards", "service_cards.service_card_id", "=", "service_card_list.service_card_id")
                ->leftJoin("branches", "branches.branch_id", "=", "service_card_list.branch_id")
                ->select('branches.branch_name',
                    'service_card_list.service_card_list_id',
                    "service_card_list.service_card_id",
                    "service_cards.name",
                    "service_cards.service_card_type",
                    "service_cards.price",
                    "service_card_list.branch_id as branchId",
                    DB::raw("COUNT(service_card_list.service_card_id) as card_count"))
                ->where('service_cards.name', 'like', '%' . $name . '%')->get()->toArray();
            return $select;
        }
        if ($type != null) {
            $select = $this
                ->leftJoin("service_cards", "service_cards.service_card_id", "=", "service_card_list.service_card_id")
                ->leftJoin("branches", "branches.branch_id", "=", "service_card_list.branch_id")
                ->select('branches.branch_name',
                    'service_card_list.service_card_list_id',
                    "service_card_list.service_card_id",
                    "service_cards.name",
                    "service_cards.service_card_type",
                    "service_cards.price",
                    "service_card_list.branch_id as branchId",
                    DB::raw("COUNT(service_card_list.service_card_id) as card_count"))
                ->where('service_cards.service_card_type', $type)->get()->toArray();
            return $select;
        }

    }

    public function searchCard($code)
    {
        $ds = $this->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'service_card_list.service_card_id')
            ->select('service_card_list.service_card_id',
                'service_cards.name as card_name',
                'service_cards.service_card_type as service_card_type',
                'service_cards.service_is_all as service_is_all',
                'service_cards.service_id as service_id',
                'service_card_list.code',
                'service_card_list.is_actived',
                'service_card_list.actived_at',
                'service_cards.date_using',
                'service_cards.number_using',
                'service_cards.money')
            ->where('service_card_list.code', $code)->first();
        return $ds;
    }

    public function searchActiveCard($code, $branch)
    {
        //search
        $ds = $this
            ->select(
                'service_card_list.code as code',
                'service_card_list.service_card_list_id as service_card_list_id',
                'service_cards.name as name_code',
                'service_cards.service_card_type as card_type',
                'service_cards.money as money',
                'services.service_name as name_sv',
                'branches.branch_name as branch_name',
                'service_cards.date_using as date_using',
                'service_cards.number_using as number_using',
                'service_cards.service_card_id as service_card_id'
            )
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'service_card_list.service_card_id')
            ->leftJoin('services', 'services.service_id', '=', 'service_cards.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_card_list.branch_id')
            ->join("orders", "orders.order_code", "=", "{$this->table}.order_code")
//            ->where('customer_service_cards.customer_id','=',1)
            ->where('service_card_list.is_actived', 0)
            ->where('service_card_list.code', $code)
            ->whereNotIn("orders.process_status", ['ordercancle'])
            ->where("orders.is_deleted", 0);

        if ($branch != null) {
            $ds->where('service_card_list.branch_id', $branch);
        }


        return $ds->first();
    }

    public function filterCardSold($cardType, $keyWord, $status, $branch, $staffActived, $startTime, $endTime)
    {
        $select = $this->leftJoin('orders', 'orders.order_code', '=', 'service_card_list.order_code')
            ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.order_id')
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'order_details.object_id')
            ->select(
                'service_card_list.code as card_code',
                'service_card_list.is_actived as is_actived'
            );
        if ($keyWord != null) {
            $select->where('service_card_list.code', 'LIKE', '%' . $keyWord . '%');
        }
        if ($branch != null) {
            $select->where('orders.branch_id', $branch);
        }
        if ($staffActived != null) {
            $select->leftJoin('customer_service_cards', 'customer_service_cards.card_code', '=', 'service_card_list.code');
            $select->where('customer_service_cards.created_by', $staffActived);
        }
        if ($startTime != null && $endTime != null) {
            $select->whereBetween('service_card_list.actived_at', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
            $select->where('service_card_list.is_actived', 1);
        }
        if ($status != null) {
            $select->where('service_card_list.is_actived', $status);
        }
        $select->where('service_cards.service_card_type', $cardType)
            ->where('service_cards.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess');
        return $select->get();
    }

    public function getDetailByCode($code)
    {
        $select = $this->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'service_card_list.service_card_id')
            ->select(
                'service_cards.number_using as number_using',
                'service_cards.money as money'
            )
            ->where('service_card_list.code', $code)
            ->where('service_card_list.is_actived', 0)
            ->where('service_cards.is_deleted', 0)
            ->first();
        return $select;
    }

    public function getItemDetailCustomer($id)
    {
        $ds = $this
            ->leftJoin('orders', 'orders.order_code', '=', 'service_card_list.order_code')
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'service_card_list.service_card_id')
            ->select('service_cards.name',
                'service_card_list.is_actived',
                'service_card_list.code',
                'orders.order_id',
                'service_card_list.price')
            ->where('orders.customer_id', $id)
            ->get();
        return $ds;
    }

    public function getServiceCardListByOrderCode($orderCode)
    {
        $select = $this->select('code')->where('order_code', $orderCode)->get();
        return $select;
    }

    /**
     * Lấy thông tin thẻ dv đã được kích hoạt
     *
     * @param $cardCode
     * @param $orderCode
     * @return mixed
     */
    public function getInfoCardActive($cardCode, $orderCode)
    {
        return $this
            ->select(
                "{$this->table}.service_card_list_id",
                "{$this->table}.service_card_id",
                "{$this->table}.code",
                "{$this->table}.order_code",
                "{$this->table}.is_actived",
                "service_cards.name as card_name"
            )
            ->join("service_cards", "service_cards.service_card_id", "=", "{$this->table}.service_card_id")
            ->where("{$this->table}.code", $cardCode)
            ->where("{$this->table}.order_code", $orderCode)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->first();
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 12/5/2018
 * Time: 9:19 AM
 */
namespace Modules\FNB\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerServiceCardTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_service_cards';
    protected $primaryKey = "customer_service_card_id";
    protected $fillable = [
        'customer_service_card_id',
        'customer_id',
        'card_code',
        'service_card_id',
        'actived_date',
        'expired_date',
        'number_using',
        'count_using',
        'money',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_actived',
        'branch_id',
        'is_deleted',
        'note',
        'is_reserve',
        'date_reserve',
        'number_days_remain_reserve',
        'is_remind',
        'remind_value'
    ];

    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;
    const NOT_RESERVE = 0;
    const SERVICE_CARD = 'service_card';

    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_service_card_id;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $ds = $this->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('services', 'services.service_id', '=', 'service_cards.service_id')
            ->leftJoin('order_details', 'order_details.object_code', '=', 'customer_service_cards.card_code')
            ->select('customer_service_cards.customer_service_card_id as customer_service_card_id',
                'customer_service_cards.customer_id as customer_id',
                'customer_service_cards.card_code as card_code',
                'customer_service_cards.is_actived as is_actived',
                'customer_service_cards.service_card_id as service_card_id',
                'customer_service_cards.actived_date as actived_date',
                'customer_service_cards.expired_date as expired_date',
                'customer_service_cards.number_using as number_using',
                'customer_service_cards.count_using as count_using',
                'customer_service_cards.money as money',
                'customer_service_cards.is_deleted',
                'customer_service_cards.note',
                'service_cards.name as card_name',
                'service_cards.service_card_type as service_card_type',
                'service_cards.date_using as date_using',
                'services.service_id as service_id',
                'order_details.price')
            ->where('customer_service_cards.customer_id', $id)
            ->get();
        return $ds;
    }

    /**
     * @param $code
     * @return mixed
     */
    public function searchCard($code)
    {
        $ds = $this->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->select('customer_service_cards.customer_service_card_id as customer_service_card_id',
                'customer_service_cards.customer_id as customer_id',
                'customer_service_cards.card_code as card_code',
                'customer_service_cards.service_card_id as service_card_id',
                'customer_service_cards.actived_date as actived_date',
                'customer_service_cards.expired_date as expired_date',
                'customer_service_cards.number_using as number_using',
                'customer_service_cards.count_using as count_using',
                'customer_service_cards.money as money',
                'customer_service_cards.is_deleted',
                'customer_service_cards.note',
                'service_cards.name as card_name',
                'service_cards.service_card_type as service_card_type',
                'service_cards.service_is_all as service_is_all',
                'service_cards.service_id as service_id')
            ->where('customer_service_cards.card_code', $code)->first();
        return $ds;
    }

    /**
     * @param $code
     * @param $id
     * @return mixed
     */
    public function searchCardReceipt($code, $id)
    {
        $ds = $this->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('services', 'services.service_id', '=', 'service_cards.service_id')
            ->select('customer_service_cards.customer_service_card_id as customer_service_card_id',
                'customer_service_cards.customer_id as customer_id',
                'customer_service_cards.card_code as card_code',
                'customer_service_cards.service_card_id as service_card_id',
                'customer_service_cards.actived_date as actived_date',
                'customer_service_cards.expired_date as expired_date',
                'customer_service_cards.number_using as number_using',
                'customer_service_cards.count_using as count_using',
                'customer_service_cards.money as money',
                'customer_service_cards.is_actived as is_actived',
                'customer_service_cards.is_deleted',
                'customer_service_cards.note',
                'service_cards.name as card_name',
                'service_cards.service_card_type as service_card_type',
                'service_cards.service_is_all as service_is_all',
                'service_cards.service_id as service_id',
                'services.price_standard as price_standard')
            ->where('customer_service_cards.card_code', $code)
            ->where('customer_service_cards.customer_id', $id)->first();
        return $ds;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCodeOrder($id)
    {
        $ds = $this->select('customer_id', 'card_code', 'service_card_id')
            ->where('service_card_id', $id)->get();
        return $ds;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemCard($id)
    {
        $ds = $this->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('services', 'services.service_id', '=', 'service_cards.service_id')
            ->select('customer_service_cards.card_code as card_code',
                'customer_service_cards.customer_id as customer_id',
                'customer_service_cards.money as money',
                'customer_service_cards.is_actived as is_actived',
                'customer_service_cards.number_using as number_using',
                'customer_service_cards.count_using as count_using',
                'customer_service_cards.actived_date as actived_date',
                'customer_service_cards.expired_date as expired_date',
                'customer_service_cards.is_deleted',
                'customer_service_cards.note',
                'service_cards.name as name',
                'service_cards.service_card_type as service_card_type',
                'service_cards.service_is_all as service_is_all',
                'service_cards.service_id as service_id',
                'service_cards.date_using as date_using',
                'service_cards.number_using as number_using_sv',
                'services.price_standard as price_standard')
            ->where('customer_service_cards.customer_service_card_id', $id)->first();
        return $ds;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('customer_service_card_id', $id)->update($data);
    }

    /**
     * @param $code
     * @param $branch
     * @return mixed
     * Tìm kiếm thẻ dịch vụ active
     */
    public function searchActiveCard($code, $branch)
    {
        $ds = $this
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('services', 'services.service_id', '=', 'service_cards.service_id')
            ->leftJoin('service_card_list', 'service_card_list.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'service_card_list.branch_id')
            ->select('customer_service_cards.card_code as card_code',
                'customer_service_cards.customer_service_card_id as customer_service_card_id',
                'customer_service_cards.is_deleted',
                'customer_service_cards.note',
                'service_cards.name as name_code',
                'service_cards.service_card_type as card_type',
                'service_cards.money as money',
                'services.service_name as name_sv',
                'branches.branch_name as branch_name',
                'service_cards.date_using as date_using')
//            ->where('customer_service_cards.customer_id','=',1)
            ->where('customer_service_cards.is_actived', 0)
            ->where('customer_service_cards.card_code', $code)
            ->where('branches.branch_id', $branch)->first();
        return $ds;
    }

    /**
     * @param array $data
     * @param $code
     * Cập nhật bằng code
     */
    public function editByCode(array $data, $code)
    {
        return $this->where('card_code', $code)->update($data);
    }


    /**
     * @param $id
     * @param $branch
     * @return mixed
     * Load danh sách thẻ dịch vụ theo customer_id
     */
    public function loadCardMember($id, $branch)
    {
        $ds = $this
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('services', 'services.service_id', '=', 'service_cards.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'customer_service_cards.branch_id')
            ->select('customer_service_cards.card_code as card_code',
                'customer_service_cards.customer_service_card_id as customer_service_card_id',
                'service_cards.name as name_code',
                'service_cards.service_card_type as card_type',
                'services.service_name as name_sv',
                'branches.branch_name as branch_name',
                'service_cards.image as image',
                'services.service_id as service_id',
                'customer_service_cards.expired_date as expired_date',
                'customer_service_cards.number_using as number_using',
                'customer_service_cards.count_using as count_using')
            ->where('customer_service_cards.is_actived', 1)
            ->where('customer_service_cards.is_reserve', self::NOT_RESERVE)
            ->where('customer_service_cards.is_deleted', self::NOT_DELETE)
            ->where('service_card_type', 'service')
            ->where('customer_service_cards.customer_id', $id);

        if ($branch != null) {
            $ds->where('branches.branch_id', $branch);
        }

        return $ds->get();
    }

    public function searchCardMember($search, $id, $branch, $page)
    {
        $ds = $this
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('services', 'services.service_id', '=', 'service_cards.service_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'customer_service_cards.branch_id')
            ->select('customer_service_cards.card_code as card_code',
                'customer_service_cards.customer_service_card_id as customer_service_card_id',
                'service_cards.name as name_code',
                'service_cards.service_card_type as card_type',
                'services.service_name as name_sv',
                'branches.branch_name as branch_name',
                'service_cards.image as image',
                'services.service_id as service_id',
                'customer_service_cards.expired_date as expired_date',
                'customer_service_cards.number_using as number_using',
                'customer_service_cards.count_using as count_using')
//            ->where('customer_service_cards.customer_id','=',1)
            ->where('customer_service_cards.is_actived', 1)
            ->where('service_card_type', 'service')
            ->where(function ($query) use ($search) {
                $query->where('customer_service_cards.card_code', $search)
                    ->orWhere('service_cards.name', 'like', '%' . $search . '%');
            })
            
            ->where('customer_service_cards.customer_id', $id)
            ->where('branches.branch_id', $branch);
           
            $page    = (int) ($page ?? 1);
            $display = (int) ($filters['perpage'] ?? 12);
            return $ds->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    //Lấy các thẻ dịch vụ đã sử dụng.
    public function getServiceCardUsed($objectId, array $filter = [])
    {
        $select = $this->leftJoin('order_details', 'order_details.object_code', '=', 'customer_service_cards.card_code')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
            ->select(
                'customer_service_cards.card_code as card_code',
                'customers.full_name as full_name',
                'order_details.created_at as day_use',
                'customer_service_cards.expired_date as expired_date'
            )
            ->where('order_details.object_type', 'member_card')
            ->where('customer_service_cards.service_card_id', $objectId)
            ->groupBy('order_details.object_code');
//        $page = (int)($filter['page'] ?? 1);
//        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
//        // search term
//        if (!empty($filter['search_type']) && !empty($filter['search_keyword'])) {
//            $select->where($filter['search_type'], 'like', '%' . $filter['search_keyword'] . '%');
//        }
//        unset($filter['search_type'], $filter['search_keyword'], $filter['page'], $filter['display']);
//
//        // filter list
//        foreach ($filter as $key => $val) {
//            if (trim($val) == '') {
//                continue;
//            }
//
//            $select->where(str_replace('$', '.', $key), $val);
//        }

        return $select->get();
    }

    //Lấy thẻ đã kích hoạt theo mã thẻ
    public function getCardActiveByCode($code, $keyWord, $branch, $staffActived, $startTime, $endTime)
    {
        if ($code != '') {
            $select = $this
                ->where('card_code', $code)
//                ->where("is_deleted", self::NOT_DELETE)
                ->first();
            return $select;
        } else {
            $select = $this
                ->select('customer_service_card_id', 'customer_id', 'card_code', 'service_card_id', 'actived_date', 'expired_date',
                    'number_using', 'count_using', 'money', 'created_by', 'updated_by', 'created_at', 'updated_at', 'is_actived',
                    'branch_id', 'is_deleted', 'note', 'is_reserve');
//                ->where("is_deleted", self::NOT_DELETE);
            if ($keyWord != null) {
                $select->where('card_code', 'LIKE', '%' . $keyWord . '%');
            }
            if ($branch != null) {
                $select->where('branch_id', $branch);
            }
            if ($staffActived != null) {
                $select->where('updated_at', $staffActived);
            }
            if ($startTime != null && $endTime != null) {
                $select->whereBetween('actived_date', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
            }
            return $select->get()->toArray();
        }
    }

    public function filterCardSold($cardType, $keyWord, $branch, $staffActived, $startTime, $endTime)
    {
        $select = $this->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->select(
                'customer_service_cards.customer_service_card_id',
                'customer_service_cards.customer_id',
                'customer_service_cards.card_code', 'customer_service_cards.service_card_id',
                'customer_service_cards.actived_date',
                'customer_service_cards.expired_date',
                'customer_service_cards.number_using',
                'customer_service_cards.count_using',
                'customer_service_cards.money',
                'customer_service_cards.created_by',
                'customer_service_cards.updated_by',
                'customer_service_cards.created_at',
                'customer_service_cards.updated_at',
                'customer_service_cards.is_actived',
                'customer_service_cards.branch_id',
                'customer_service_cards.is_deleted',
                'customer_service_cards.note'
            );
        if ($keyWord != null) {
            $select->where('customer_service_cards.card_code', 'LIKE', '%' . $keyWord . '%');
        }
        if ($branch != null) {
            $select->where('customer_service_cards.branch_id', $branch);
        }
        if ($staffActived != null) {
            $select->where('customer_service_cards.updated_by', $staffActived);
        }
        if ($startTime != null && $endTime != null) {
            $select->whereBetween('customer_service_cards.actived_date', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        }
        $select->where('service_cards.service_card_type', $cardType);
        return $select->get();

    }

    //Lấy chi tiết của thẻ đã bán
    public function getDetailCardSold($code, array $filter = [])
    {
        $select = $this->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('order_details', 'order_details.object_code', '=', 'customer_service_cards.card_code')
            ->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin("staffs", "staffs.staff_id", "=", "order_details.staff_id")
            ->select(
                'customer_service_cards.card_code as card_code',
                'service_cards.service_card_type as service_card_type',
                'service_cards.number_using as number_using',
                'customer_service_cards.expired_date as expired_date',
                'customer_service_cards.count_using as count_using',
                'customer_service_cards.is_deleted',
                'customer_service_cards.note',
                'customer_service_cards.card_code as card_code',
                'order_details.created_at as day_using',
                'customers.full_name as customer',
                'orders.order_code as order_code',
                'customer_service_cards.is_actived as is_actived',
                DB::raw("SUM(order_details.quantity) as quantity"),
                "staffs.full_name as staff_name",
                "order_details.staff_id"
//                DB::raw('YEAR(order_details.created_at) year, MONTH(order_details.created_at) month, DAY(order_details.created_at) day')
            )
            ->where('customer_service_cards.card_code', $code)
            ->where('orders.process_status', 'paysuccess')
            ->where('order_details.object_type', 'member_card')
            ->where('service_cards.is_deleted', self::NOT_DELETE)
            ->where('customers.is_deleted', self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->groupBy('order_details.created_at');
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['display'] ?? PAGING_ITEM_PER_PAGE);
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    //Lấy chi tiết thẻ theo mã.
    public function getCardByCode($code)
    {
        $select = $this
            ->select(
                "{$this->table}.customer_service_card_id",
                'service_cards.service_card_type',
                'customer_service_cards.expired_date',
                'customer_service_cards.count_using',
                'customer_service_cards.number_using',
                'customer_service_cards.actived_date',
                'customers.full_name as customer_name',
                'service_cards.money as money',
                'customer_service_cards.is_actived',
                'customer_service_cards.is_deleted',
                'customer_service_cards.note',
                'customer_service_cards.is_reserve',
                'customer_service_cards.date_reserve',
                'customer_service_cards.number_days_remain_reserve',
                "service_cards.name as card_name",
                "{$this->table}.card_code",
                "{$this->table}.customer_id",
                "{$this->table}.service_card_id",
                "{$this->table}.branch_id"
            )
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
//            ->where('service_cards.is_deleted', 0)
            ->where('customer_service_cards.card_code', $code);
        return $select->first();
    }

    /**
     * Lấy thẻ dịch vụ của KH
     *
     * @param $id
     * @param $branch
     * @return mixed
     */
    public function memberCardDetail($id, $branch)
    {
        $ds = $this
            ->select(
                "service_cards.name",
                "service_card_list.code",
                "service_card_list.price",
                "service_card_list.is_actived",
                "service_cards.service_card_type",
                "{$this->table}.expired_date",
                "{$this->table}.number_using",
                "{$this->table}.count_using",
                "wr.date_expired as warranty_expired",
                "wr.status as warranty_status"
            )
            ->join('service_card_list', 'service_card_list.code', '=', 'customer_service_cards.card_code')
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin("warranty_card as wr", function ($join) {
                $join->on("{$this->table}.service_card_id", "=", "wr.object_type_id")
                    ->where("wr.object_type", self::SERVICE_CARD)
                    ->whereRaw("wr.customer_code = customers.customer_code")
                    ->where("wr.status", "actived");
            })
            ->where('customer_service_cards.customer_id', $id)
            ->where('customer_service_cards.is_deleted', self::NOT_DELETE)
            ->orderBy("{$this->table}.customer_service_card_id", "desc");

        if ($branch != null) {
            $ds->where('customer_service_cards.branch_id', $branch);
        }

        return $ds->get();
    }

    public function getCustomerCardAll($customer_id)
    {
        $ds = $this->join('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->select(
                'service_cards.name as card_name'
            )->where('customer_id', $customer_id);
        return $ds->get()->toArray();
    }

    /**
     * Lấy list thẻ liệu trình còn hạn sd của khách hàng
     *
     * @param $customerId
     * @param $branchId
     * @return mixed
     */
    public function getMemberCard($customerId, $branchId)
    {
        $dateNow = Carbon::now()->format('Y-m-d');

        $ds = $this
            ->select(
                "{$this->table}.customer_service_card_id",
                "{$this->table}.card_code",
                "service_cards.name as card_name",
                "{$this->table}.is_actived",
                "{$this->table}.actived_date",
                "{$this->table}.expired_date",
                "{$this->table}.number_using",
                "{$this->table}.count_using",
                "{$this->table}.branch_id"
            )
            ->join("service_cards", "service_cards.service_card_id", "=", "{$this->table}.service_card_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where(function ($query) {
                $query->whereNull("service_cards.money")
                    ->orWhere("service_cards.money", 0);
            })
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_reserve", self::NOT_RESERVE)
            ->where(function ($query) {
                $query->where("{$this->table}.number_using", 0)
                    ->orWhereRaw("{$this->table}.number_using > {$this->table}.count_using");
            })
            ->where(function ($query) use ($dateNow) {
                $query->whereNull("{$this->table}.expired_date")
                    ->orWhereDate("{$this->table}.expired_date", ">=", $dateNow);
            })
            ->orderBy("{$this->table}.customer_service_card_id", "desc");

        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }

        return $ds->get();
    }

    /**
     * Cập nhật thẻ liệu trình theo card code và customer id
     *
     * @param $data
     * @param $cardCode
     * @param $customerId
     * @return mixed
     */
    public function editByCardCodeAndCustomerId($data, $cardCode, $customerId)
    {
        return $this->where('card_code', $cardCode)->where('customer_id', $customerId)->update($data);
    }

    /**
     * Lấy thông tin hoa hồng thẻ liệu trình
     *
     * @param $cardCode
     * @return mixed
     */
    public function getCommissionMemberCard($cardCode)
    {
        return $this
            ->select(
                "{$this->table}.card_code",
                "service_cards.name",
                "service_cards.type_refer_commission",
                "service_cards.refer_commission_value",
                "service_cards.type_staff_commission",
                "service_cards.staff_commission_value",
                "service_cards.price"
            )
            ->join("service_cards", "service_cards.service_card_id", "=", "{$this->table}.service_card_id")
            ->where("{$this->table}.card_code", $cardCode)
            ->where("service_cards.is_deleted", 0)
            ->first();
    }

    /**
     * Lấy danh sách thẻ liệu trình có thể cộng dồn
     *
     * @param $getCard
     * @return mixed
     */
    public function getListCardCanAccrual($getCard)
    {
        $now = \Carbon\Carbon::now()->format('Y-m-d');

        $select = $this->select(
            "{$this->table}.customer_service_card_id",
            "{$this->table}.expired_date",
            "{$this->table}.count_using",
            "{$this->table}.number_using",
            "{$this->table}.actived_date",
            "{$this->table}.is_actived",
            "{$this->table}.is_deleted",
            "{$this->table}.note",
            "{$this->table}.is_reserve",
            "{$this->table}.date_reserve",
            "{$this->table}.number_days_remain_reserve",
            "{$this->table}.card_code",
            "{$this->table}.customer_id",
            "{$this->table}.service_card_id",
            "service_cards.name as card_name",
            "service_cards.service_card_type",
            "service_cards.money as money"

        )
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', "{$this->table}.service_card_id")
            ->where("{$this->table}.customer_service_card_id", "<>", $getCard['customer_service_card_id'])
            ->where("{$this->table}.service_card_id", $getCard['service_card_id'])
            ->where("{$this->table}.customer_id", $getCard['customer_id'])
            ->where("{$this->table}.branch_id", $getCard['branch_id'])
            ->where(function ($query) use ($now) {
                $query->where("{$this->table}.expired_date", ">", $now)
                    ->orWhereNull("{$this->table}.expired_date");
            })
            ->where(function ($query) {
                $query->where("{$this->table}.count_using", "<", DB::raw("{$this->table}.number_using"))
                    ->orWhere("{$this->table}.number_using", 0);
            })
            ->where("{$this->table}.is_reserve", self::NOT_RESERVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);

        return $select->get();
    }
}

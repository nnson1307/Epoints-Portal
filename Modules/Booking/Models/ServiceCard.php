<?php

namespace Modules\Booking\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceCard extends Model
{
    use ListTableTrait;

    protected $table = "service_cards";
    protected $primaryKey = "service_card_id";
    protected $fillable = [
        'service_card_id',
        'service_card_group_id',
        'name',
        'code',
        'service_is_all',
        'service_id',
        'service_card_type',
        'date_using',
        'number_using',
        'price',
        'money',
        'image',
        'is_actived',
        'is_deleted',
        'updated_by',
        'created_by',
        'created_at',
        'updated_at',
        'description',
        'slug',
        'type_refer_commission',
        'refer_commission_value',
        'type_staff_commission',
        'staff_commission_value'
    ];

    protected function _getList(&$filter = [])
    {
        $oSelect = $this
            ->leftJoin("service_card_groups as card_groups", "card_groups.service_card_group_id", "=", "service_cards.service_card_group_id")
            ->select('service_card_id',
                'card_groups.name as group_name',
                'service_cards.name as card_name',
                'code',
                'service_card_type',
                'date_using',
                'number_using',
                'price',
                'money',
                'is_actived')->where('service_cards.is_deleted', 0);
//        dd($oSelect->get());
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {

            $arr_filter = explode(" - ", $filter["created_at"]);
//         dd($arr_filter);
            $from = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $oSelect->whereBetween('service_cards.created_at', [$from, $to]);
        }
        unset($filter["created_at"]);
        if (isset($filter["search_keyword"]) && $filter["search_keyword"] != "") {
            $oSelect->where('service_cards.name', 'LIKE', '%' . $filter["search_keyword"] . '%')
                ->orWhere('service_cards.code', 'LIKE', '%' . $filter["search_keyword"] . '%');
        }

        unset($filter["search_keyword"]);
        return $oSelect;
    }

    public function getAll($filter = [])
    {
        $oSelect = $this
            ->leftJoin("service_card_groups as card_groups", "card_groups.service_card_group_id", "=", "service_cards.service_card_group_id")
            ->select('service_card_id',
                'card_groups.name as group_name',
                'service_cards.name as card_name',
                'code',
                'service_card_type',
                'date_using',
                'number_using',
                'price',
                'money',
                'is_actived')->where('service_cards.is_deleted', 0)->where("is_actived", 1);
        if (isset($filter["service_card_type"]) && $filter["service_card_type"] != "") {

            $oSelect->where("service_cards.service_card_type", $filter["service_card_type"]);
//            dd($oSelect->get());
        }
        if (isset($filter["keyword"]) && $filter["keyword"] != "") {
            $oSelect->where("service_cards.name", "LIKE", "%" . $filter["keyword"] . "%");
//                ->orWhere("code","LIKE","%".$filter["keyword"]."%");
        }


        return $oSelect->get();
    }

    public function getServiceCardInId($arr_id)
    {
        $oSelect = $this
            ->leftJoin("service_card_groups as card_groups", "card_groups.service_card_group_id", "=", "service_cards.service_card_group_id")
            ->select('service_card_id',
                'card_groups.name as group_name',
                'service_cards.name as card_name',
                'code',
                'service_card_type',
                'date_using',
                'number_using',
                'price',
                'money',
                'is_actived')
            ->whereIn("service_card_id", $arr_id)
            ->where('service_cards.is_deleted', 0)
            ->where("is_actived", 1);
        return $oSelect->get();
    }

    public function add(array $data)
    {
        return self::create($data);
    }

    public function edit($id, array $data)
    {
        return self::where("service_card_id", $id)->update($data);
    }

    public function getServiceCardDetail($id)
    {
        $oSelect = $this
            ->leftJoin("services", "services.service_id", "=", "service_cards.service_id")
            ->select('service_card_id',
                'service_card_group_id',
                'service_cards.name',
                'code',
                'service_card_type',
                'date_using',
                'service_is_all',
                'services.service_name',
                'number_using',
                "image",
                'price',
                'money',
                'service_cards.is_actived')
            ->where("service_card_id", $id)
            ->where('service_cards.is_deleted', 0);
        return $oSelect->first();
    }


    public function getServiceCardInfo($id)
    {
        $oSelect = $this->select('service_card_id',
            'service_card_group_id',
            'service_cards.name',
            'code',
            'service_card_type',
            'date_using',
            'service_id',
            'number_using',
            "image",
            'price',
            'money',
            'description',
            'is_actived',
            'type_refer_commission',
            'refer_commission_value',
            'type_staff_commission',
            'staff_commission_value'
        )
            ->where("service_card_id", $id)
            ->where('service_cards.is_deleted', 0);
        return $oSelect->first();
    }

    public function remove($id)
    {
        return self::where("service_card_id", $id)->update(['is_deleted' => 1]);
    }

    public function getName()
    {
        $oSelect = self::select("service_card_id", "name")->where('is_deleted', 0)->get();
        return $oSelect->pluck("name", "service_card_id")->toArray();
    }

    public function getServiceCardSearch($data)
    {
        $select = $this->select('service_card_id', 'name', 'price', 'image')->where('name', 'like', '%' . $data . '%')
            ->where('is_deleted', 0)->get();
        return $select;
    }

    public function getListAdd()
    {
        $ds = $this->select('service_card_id', 'name', 'price', 'money', 'image', 'code')->where('is_deleted', 0)->get();
        return $ds;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItemDetail($id)
    {
        $ds = $this->where('service_card_id', $id)->first();
        return $ds;
    }

    public function getServiceCardOrder($code)
    {
        $ds = $this->select('service_card_id', 'name', 'service_is_all', 'service_id', 'service_card_type', 'date_using', 'number_using',
            'price', 'money', 'is_actived', 'code')
            ->where('code', $code)->first();
        return $ds;
    }

    public function getOption()
    {
        return $this->select('service_card_id', 'name')
            ->where('is_actived', 1)
            ->where('is_deleted', 0)
            ->get()
            ->toArray();
    }

    public function getAllServiceCard()
    {
        return $this->select('service_card_id', 'name', 'price', 'service_card_type', 'image', 'is_actived')
            ->where('is_deleted', 0)->orderBy('service_card_id', 'DESC')
            ->get()->toArray();
    }

    //Chi tiết thẻ dịch vụ
    public function detail($id)
    {
        $oSelect = $this
            ->leftJoin("service_card_groups", "service_card_groups.service_card_group_id", "=", "service_cards.service_card_group_id")
            ->select(
                'service_cards.service_card_id as service_card_id',
                'service_card_groups.name as group_name',
                'service_cards.name as card_name',
                'price',
                'code'
            )
            ->where("service_card_id", $id)
            ->where('service_cards.is_deleted', 0);
        return $oSelect->first()->toArray();
    }

    public function filter($keyWord, $status, $cardType, $cardGroup)
    {
        $select = $this
            ->leftJoin("service_card_groups", "service_card_groups.service_card_group_id", "=", "service_cards.service_card_group_id")
            ->select(
                'service_cards.service_card_id as service_card_id',
                'service_card_groups.name as group_name',
                'service_cards.name as card_name',
                'price',
                'code',
                'service_card_type',
                'image',
                'service_cards.is_actived as is_actived'
            );
        if ($keyWord != null) {
            $select->where('service_cards.name', 'like', '%' . $keyWord . '%');
        }
        if ($status != null) {
            $select->where('service_cards.is_actived', $status);
        }
        if ($cardType != null) {
            $select->where('service_cards.service_card_type', $cardType);
        }
        if ($cardGroup != null) {
            $select->where('service_cards.service_card_group_id', $cardGroup);
        }
        $select->where('service_cards.is_deleted', 0);

        return $select->get()->toArray();
    }

    //Kiểm tra tên thẻ.
    public function checkName($name, $id, $groupId)
    {
        if ($id != null) {
            $select = $this->where('service_card_id', '<>', $id)
                ->where('service_card_group_id', $groupId)->where('slug', str_slug($name));
            return $select->first();
        } else {
            $select = $this->where('service_card_group_id', $groupId)->where('slug', str_slug($name));
            return $select->first();
        }
    }

    //Lấy danh sách thẻ đã bán.
    public function getServiceCardSold($cardType)
    {
        $select = $this->leftJoin('order_details', 'order_details.object_id', '=', 'service_cards.service_card_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_code as service_code',
                'orders.branch_id as branch_id',
                'orders.created_by as staff_id',
                'orders.customer_id as customer_id'
            )
            ->where('orders.process_status', 'paysuccess')
            ->where('order_details.object_type', 'service_card')
            ->where('service_cards.service_card_type', $cardType)
            ->where('service_cards.is_deleted', 0);
        return $select->get();
    }

    //Lấy danh sách thẻ hết hạn theo ngày truyền vào.
    public function serviceCardNearlyExpireds($datetime)
    {
        $select = $this->leftJoin('customer_service_cards', 'customer_service_cards.service_card_id', '=', 'service_cards.service_card_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
            ->select(
                'customer_service_cards.customer_service_card_id as customer_service_card_id',
                'customers.full_name as full_name',
                'customers.gender as gender',
                'customer_service_cards.card_code as card_code',
                'customer_service_cards.expired_date as datetime',
                'customers.phone1 as phone1'
            )->where('customer_service_cards.expired_date', $datetime)
            ->where('customers.phone1', '<>', null)->get();
        return $select;
    }

    //Lấy danh sách các thẻ hết số lần sử dụng
    public function serviceCardOverNumberUseds($id)
    {
        $select = $this->leftJoin('customer_service_cards', 'customer_service_cards.service_card_id', '=', 'service_cards.service_card_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
            ->select(
                'customers.full_name as full_name',
                'customers.gender as gender',
                'customer_service_cards.card_code as card_code',
                'customer_service_cards.expired_date as datetime',
                'customers.phone1 as phone'
            )
            ->where('customer_service_cards.number_using', '<>', 0)
            ->where('customer_service_card_id', $id)
            ->whereColumn('customer_service_cards.number_using', 'customer_service_cards.count_using')->first();
        return $select;
    }

    //Lấy danh sách thẻ hết hạn hôm nay.
    public function serviceCardExpireds()
    {
        $select = $this->leftJoin('customer_service_cards', 'customer_service_cards.service_card_id', '=', 'service_cards.service_card_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_service_cards.customer_id')
            ->select(
                'customer_service_cards.customer_service_card_id as customer_service_card_id',
                'customers.full_name as full_name',
                'customers.gender as gender',
                'customers.phone1 as phone',
                'customer_service_cards.card_code as card_code',
                'customer_service_cards.expired_date as datetime'
            )
            ->where('customer_service_cards.number_using', '<>', 0)
            ->where('customer_service_cards.expired_date', date('Y-m-d'))->get();
        return $select;
    }

    //Lấy nhóm thẻ thông qua id thẻ.
    public function getServiceGroup($id)
    {
        $oSelect = $this
            ->leftJoin("service_card_groups", "service_card_groups.service_card_group_id", "=", "service_cards.service_card_group_id")
            ->select(
                'service_cards.service_card_id as service_card_id',
                'service_card_groups.name as group_name',
                'service_cards.name as card_name'
            )
            ->where("service_card_id", $id);
        return $oSelect->first();
    }
}
//

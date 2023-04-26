<?php

namespace Modules\Warranty\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceCardTable extends Model
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
                'is_actived'
            )
            ->where('service_cards.is_deleted', 0);
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $from = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $oSelect->whereBetween('service_cards.created_at', [$from, $to]);
        }
        unset($filter["created_at"]);
        if (isset($filter['search_keyword']) != '') {
            $search = $filter['search_keyword'];
            $oSelect->where(function ($query) use ($search) {
                $query->where('service_cards.name', 'like', '%' . $search . '%')
                    ->orWhere('service_cards.code', 'like', '%' . $search . '%');
            });
            unset($filter["search_keyword"]);
        }
        return $oSelect;
    }

    /**
     * Lấy thông tin thẻ dịch vụ
     *
     * @param $code
     * @return mixed
     */
    public function getServiceCard($code)
    {
        return $this
            ->select(
                "service_card_id",
                "name",
                "price"
            )
            ->where("code", $code)
            ->first();
    }

    /**
     * Lấy danh sách thẻ dịch vụ ở phiếu bảo trì
     *
     * @param array $filter
     * @return mixed
     */
    public function getListServiceCard($filter = [])
    {
        $oSelect = $this
            ->leftJoin("service_card_groups as card_groups", "card_groups.service_card_group_id", "=", "service_cards.service_card_group_id")
            ->select(
                'service_card_id',
                'card_groups.name as group_name',
                'service_cards.name as card_name',
                'code',
                'service_card_type',
                'date_using',
                'number_using',
                'price',
                'money',
                'is_actived'
            )
            ->where('service_cards.is_deleted', 0);

        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $from = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $to = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $oSelect->whereBetween('service_cards.created_at', [$from, $to]);
        }

        if (isset($filter["search_keyword"]) && $filter["search_keyword"] != "") {
            $oSelect->where('service_cards.name', 'LIKE', '%' . $filter["search_keyword"] . '%')
                ->orWhere('service_cards.code', 'LIKE', '%' . $filter["search_keyword"] . '%');
        }

        $page = (int)(isset($filter['page']) ? $filter['page'] : 1);
        $display = (int)(isset($filter['perpage']) ? $filter['perpage'] : FILTER_ITEM_PAGE);

        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}
<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/27/2021
 * Time: 10:26 AM
 */

namespace Modules\Promotion\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceCardTable extends Model
{
    use ListTableTrait;
    protected $table = "service_cards";
    protected $primaryKey = "service_card_id";

    const SURCHARGE = 0;

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
                "name"
            )
            ->where("code", $code)
            ->first();
    }

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
                'is_actived','is_surcharge')
            ->where('service_cards.is_deleted', 0)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE);
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
}
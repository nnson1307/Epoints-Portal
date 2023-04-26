<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/01/2022
 * Time: 11:47
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerServiceCardTable extends Model
{
    protected $table = "customer_service_cards";
    protected $primaryKey = "customer_service_card_id";

    /**
     * Lấy thông tin thẻ liệu trình của KH
     *
     * @param $customerServiceCardId
     * @return mixed
     */
    public function getInfo($customerServiceCardId)
    {
        return $this
            ->select(
                "{$this->table}.card_code",
                "{$this->table}.customer_id",
                "{$this->table}.money as money",
                "{$this->table}.is_actived",
                "{$this->table}.number_using",
                "{$this->table}.count_using",
                "{$this->table}.actived_date",
                "{$this->table}.expired_date",
                "{$this->table}.is_deleted",
                "{$this->table}.note",
                "service_cards.name as name",
                "service_cards.service_card_type",
                "service_cards.service_is_all",
                "service_cards.service_id",
                "service_cards.date_using",
                "service_cards.number_using as number_using_sv",
                "services.price_standard"
            )
            ->join("service_cards", "service_cards.service_card_id", "=", "customer_service_cards.service_card_id")
            ->leftJoin("services", "services.service_id", "=", "service_cards.service_id")
            ->where("{$this->table}.customer_service_card_id", $customerServiceCardId)
            ->first();
    }

    /**
     * Chỉnh sửa thẻ liệu trình của KH
     *
     * @param array $data
     * @param $customerServiceCardId
     * @return mixed
     */
    public function edit(array $data, $customerServiceCardId)
    {
        return $this->where("customer_service_card_id", $customerServiceCardId)->update($data);
    }
}
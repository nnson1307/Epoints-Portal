<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerServiceCardTable extends Model
{
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
        'note'
    ];

    /**
     * Lấy thông tin hoa hồng
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
                "service_cards.type_deal_commission",
                "service_cards.deal_commission_value",
                "service_cards.price"
            )
            ->join("service_cards", "service_cards.service_card_id", "=", "{$this->table}.service_card_id")
            ->where("{$this->table}.card_code", $cardCode)
            ->where("service_cards.is_deleted", 0)
            ->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $select = $this
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
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'customer_service_cards.service_card_id')
            ->leftJoin('services', 'services.service_id', '=', 'service_cards.service_id')
            ->where('customer_service_cards.customer_service_card_id', $id);
        return $select->first();
    }

    /**
     * Cập nhật theo card code
     *
     * @param array $data
     * @param $code
     * @return mixed
     */
    public function editByCode(array $data, $code)
    {
        return $this->where('card_code', $code)->update($data);
    }
    public function add(array $data)
    {
        return $this->create($data)->{$this->primaryKey};
    }
}
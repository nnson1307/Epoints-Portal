<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCardListTable extends Model
{
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

    public function add(array $data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * search card
     *
     * @param $code
     * @return mixed
     */
    public function searchCard($code)
    {
        $select = $this->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'service_card_list.service_card_id')
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
            ->where('service_card_list.code', $code);
        return $select->first();
    }
}
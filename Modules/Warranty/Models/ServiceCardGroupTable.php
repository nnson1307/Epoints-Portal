<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCardGroupTable extends Model
{
    protected $table = "service_card_groups";
    protected $primaryKey = "service_card_group_id";

    const NOT_DELETED = 0;

    public function getAllName()
    {
        return $this->select('service_card_group_id', 'name')->where("is_deleted", self::NOT_DELETED)->get();
    }
}
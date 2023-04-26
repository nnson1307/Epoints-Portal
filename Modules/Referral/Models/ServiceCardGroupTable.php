<?php


namespace Modules\Referral\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;


class ServiceCardGroupTable extends Model
{
    use ListTableTrait;
    protected $table = "service_card_groups";
    protected $primaryKey = "service_card_group_id";

    public function getGroupCommodity(){
        $mSelect = $this
            ->select(
                "{$this->table}.service_card_group_id as id",
                "{$this->table}.name",
                DB::raw("'service_card' as type")
            );
        return $mSelect->get()->toArray();
    }

}
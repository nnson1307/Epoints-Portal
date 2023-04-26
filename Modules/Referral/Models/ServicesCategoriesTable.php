<?php


namespace Modules\Referral\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class ServicesCategoriesTable extends Model
{
    protected $table = "service_categories";
    protected $primaryKey = "service_category_id";

    public function getGroupCommodity(){
        $mSelect = $this
            ->select(
                "{$this->table}.service_category_id as id",
                "{$this->table}.name",
                DB::raw("'service' as type")
                );
        return $mSelect->get()->toArray();
    }

}
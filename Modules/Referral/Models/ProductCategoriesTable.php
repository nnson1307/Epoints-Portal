<?php


namespace Modules\Referral\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;


class ProductCategoriesTable extends Model
{
    use ListTableTrait;
    protected $table = "product_categories";
    protected $primaryKey = "product_category_id";

    public function getGroupCommodity(){
        $mSelect = $this
            ->select(
                "{$this->table}.product_category_id as id",
                "{$this->table}.category_name as name",
                DB::raw("'product' as type")
            );
        return $mSelect->get()->toArray();
    }

}
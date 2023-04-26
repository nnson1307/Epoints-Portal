<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:37 AM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class ProductSuggestConfigTable extends Model
{
    use ListTableTrait;
    protected $table = "product_suggest_config";
    protected $primaryKey = "product_suggest_config_id";

    //function fillable
    protected $fillable = [
        'product_suggest_config_id',
        'key',
        'type',
        'is_condition',
        'product_condition_id',
        'type_condition',
        'quantity',
        'start_date',
        'end_date',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

//    Thêm cấu hình gợi ý
    public function insertProductSuggestConfig($data){
        return $this->insertGetId($data);
    }

//    Xoá tất cả cấu hình gợi ý
    public function deleteProductConfig(){
        return $this->whereNotNull('product_suggest_config_id')->delete();
    }

//    Lấy tất cả cấu hình gợi ý
    public function getAll(){
        return $this
            ->select(
                'product_suggest_config_id',
                'key',
                'type',
                'is_condition',
                'product_condition_id',
                'type_condition',
                'quantity',
                'start_date',
                'end_date'
            )
            ->get();
    }
}
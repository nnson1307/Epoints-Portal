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

class ProductSuggestConfigMapTable extends Model
{
    use ListTableTrait;
    protected $table = "product_suggest_config_map";
    protected $primaryKey = "product_suggest_config_map_id";

    //function fillable
    protected $fillable = [
        'product_suggest_config_map_id',
        'product_suggest_config_id',
        'type',
        'object_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

//    Tạo cấu hình map với tags
    public function insertProductSuggestConfigMap($data){
        return $this->insert($data);
    }

//    Xoá tất cả các map
    public function deleteProductConfig(){
        return $this->whereNotNull('product_suggest_config_map_id')->delete();
    }

    //    Lấy tất cả cấu hình gợi ý
    public function getAll(){
        return $this
            ->select(
                'product_suggest_config_id',
                'type',
                'object_id'
            )
            ->get();
    }
}
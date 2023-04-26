<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:24 AM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ProductToppingTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_topping';
    protected $primaryKey = 'product_topping_id';
    protected $fillable
        = [
            'product_topping_id',
            'product_id',
            'product_child_id',
            'unit_id',
            'price',
            'quantity',
            'is_actived',
            'is_deleted',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];

    const IS_NOT_DELETED = 0;
    const IS_ACTIVE = 1;

    /**
     * Lấy tất cả danh sách
     * @param array $filter
     * @return mixed
     */
    public function getALl($filter = []){
        $oSelect = $this
            ->select(
                $this->table.'.*',
                'product_childs.product_child_name'
            )
            ->join('product_childs','product_childs.product_child_id',$this->table.'.product_child_id');

        if (isset($filter['product_id'])){
            $oSelect = $oSelect->where($this->table.'.product_id',$filter['product_id']);
        }

        return $oSelect->get();
    }

    /**
     * Xóa sản phẩm đi kèm
     * @param $productId
     */
    public function removeProductTopping($productId){
        return $this
            ->where('product_id',$productId)
            ->delete();
    }

    /**
     * Lấy danh sách theo product id
     * @param $productId
     */
    public function getAllByProductId($productId){
        return $this
            ->where('product_id',$productId)
            ->get();
    }

    /**
     * Tạo topping
     * @param $data
     */
    public function addTopping($data){
        return $this
            ->insert($data);
    }

    /**
     * Lấy danh sách topping theo productId
     * @param $productId
     */
    public function getListToppingByProductId($productId){
        return $this
            ->select(
                $this->table.'.*',
                'product_childs.product_child_name as product_child_name_vi',
                'product_childs.product_child_name_en',
            )
            ->join('product_childs','product_childs.product_child_id',$this->table.'.product_child_id')
            ->where($this->table.'.product_id',$productId)
            ->where($this->table.'.is_actived',self::IS_ACTIVE)
            ->where($this->table.'.is_deleted',self::IS_NOT_DELETED)
            ->get();
    }


}
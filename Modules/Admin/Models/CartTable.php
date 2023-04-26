<?php
/**
 * Created by PhpStorm
 * User: Huniel
 * Date: 4/26/2022
 * Time: 4:32 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CartTable extends Model
{
    protected $table = "cart";
    protected $primaryKey = "cart_id";
    protected $fillable = [
        "cart_id",
        "user_id",
        "branch_id",
        "amount",
        "total_amount",
        "discount",
        "total_line",
        "voucher_code",
        "created_at",
        "updated_at",
    ];

    const NOT_DELETED = 0;

    /**
     * tự làm query builder
     *
     * @param array $filter
     * @return mixed
     */
    public function queryBuild($param = []){
        $query = $this->select(
            "{$this->table}.cart_id",
            "{$this->table}.user_id"
            );

        // filter search
        if (isset($param['search']) && $param['search'] ) {
            $search = $param['search'];
            $query = $query->where(function ($condition)use($search){
                $condition->where("customers.full_name","LIKE","%{$search}%");
                $condition->orWhere("customers.phone1","LIKE","%{$search}%");
            });
        }

        // filter customer_id
        if (isset($param['customer_id']) ) {
            $query = $query->where("customers.customer_id",$param['customer_id']);
        }

        // filter branch_id
        if (isset($param['branch_id']) ) {
            $query = $query->where("branches.branch_id",$param['branch_id']);
        }


        if( isset($param['cart_id']) ){
            $query = $query->where( "{$this->table}.cart_id",$param['cart_id'] );
            $query = $query->join('customers','customers.customer_id',"{$this->table}.user_id");
            $query = $query->leftJoin('cart_item','cart_item.cart_id',"{$this->table}.cart_id");
            $query = $query->leftJoin('product_childs','product_childs.product_child_id',"cart_item.object_id");
            $query = $query->join('products','products.product_id',"product_childs.product_id");
            $query = $query->leftJoin('branches','branches.branch_id',"{$this->table}.branch_id");
            $query = $query->leftJoin('product_images',function ($join){
                $join->on('product_images.product_child_code','product_childs.product_code')
                    ->where('product_images.is_avatar',1);
            });
            $query = $query->addSelect(
                "product_childs.product_child_name as product_name",
                "product_images.name as product_avatar",
                "product_childs.product_child_id as product_child_id",
                "cart_item.quantity as quantity"
            );
        }else{
            $query = $query->join('customers','customers.customer_id',"{$this->table}.user_id");
            $query = $query->leftJoin('cart_item','cart_item.cart_id',"{$this->table}.cart_id");
            $query = $query->leftJoin('product_childs','product_childs.product_child_id',"cart_item.object_id");
            $query = $query->join('products','products.product_id',"product_childs.product_id");
            $query = $query->leftJoin('branches','branches.branch_id',"{$this->table}.branch_id");

            $query = $query->addSelect(
                DB::raw('COUNT(cart_item.cart_item_id) as cart_total'),
                "customers.full_name as customer_name",
                "branches.branch_name as branch_name",
                "customers.phone1 as customer_phone");

            $query = $query->groupBy("{$this->table}.user_id","{$this->table}.branch_id");
        }

        return $query;
    }

    /**
     * Danh sách có phân trang
     *
     * @param array $filter
     * @return mixed
     */
    public function getPaginate($param = [])
    {
        $query = $this->queryBuild($param);
        // paginate
        $per_page = $param['per_page']??10;
        $current_page = $param['current_page']??1;

        return $query->paginate($per_page,'*','page',$current_page);
    }

    /**
     * Chi tiết
     *
     * @param array $filter
     * @return mixed
     */
    public function getItem($param = [])
    {
        return $this->queryBuild($param)->first();
    }


    /**
     * Thêm
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->product_category_parent_id;
    }
}

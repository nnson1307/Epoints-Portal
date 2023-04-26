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
use Illuminate\Support\Carbon;

class FNBCustomerReviewTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_customer_reviews';
    protected $primaryKey = 'customer_review_id';
    protected $fillable
        = [
            'customer_review_id',
            'fnb_customer_id',
            'table_id',
            'review_list_id',
            'review_list_detail_id',
            'note',
            'note'
        ];

    public $timestamps = false;


    public function allCustomerReview( &$input = []){
        $page    = (int) ($input['page'] ?? 1);
        $display = (int) ($input['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $oSelect = $this
            ->select(
                "{$this->table}.customer_review_id",
                "{$this->table}.fnb_customer_id as customer_id",
                "customers.full_name as customer_name",
                "{$this->table}.table_id",
                "fnb_table.name as table_name",
                "{$this->table}.note",
                "{$this->table}.review_list_id",
                "fnb_reviews_list.name as review_list_name",
                "{$this->table}.review_list_detail_id",
                "{$this->table}.created_at"
            )
            ->orderBy( "{$this->table}.customer_review_id",'asc')
            ->leftJoin("fnb_customer","{$this->table}.fnb_customer_id","fnb_customer.fnb_customer_id")
            ->leftJoin("customers","fnb_customer.customer_id","customers.customer_id")
            ->leftJoin("fnb_reviews_list","fnb_customer_reviews.review_list_id","fnb_reviews_list.review_list_id")
            ->leftJoin("fnb_table","{$this->table}.table_id","fnb_table.table_id");
        if(isset($input['table_id']) && $input['table_id'] != null){
            $oSelect = $oSelect->where( "{$this->table}.table_id",$input['table_id'] );
        }
        if(isset($input['review_list_id']) && $input['review_list_id'] != null){
            $oSelect = $oSelect->where( "{$this->table}.review_list_id",$input['review_list_id'] );
        }
        if (isset($input["created_at"]) && $input["created_at"] != null) {
            $arr_filter_create = explode(" - ", $input["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $oSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
    public function allCustomerReviewNoPage( &$input = []){
        $oSelect = $this
            ->select(
                "{$this->table}.customer_review_id",
                "{$this->table}.fnb_customer_id as customer_id",
                "customers.full_name as customer_name",
                "{$this->table}.table_id",
                "fnb_table.name as table_name",
                "{$this->table}.note",
                "{$this->table}.review_list_id",
                "fnb_reviews_list.name as review_list_name",
                "{$this->table}.review_list_detail_id",
                "{$this->table}.created_at"
            )
            ->orderBy( "{$this->table}.customer_review_id",'asc')
            ->leftJoin("fnb_customer","{$this->table}.fnb_customer_id","fnb_customer.fnb_customer_id")
            ->leftJoin("customers","fnb_customer.customer_id","customers.customer_id")
            ->leftJoin("fnb_reviews_list","fnb_customer_reviews.review_list_id","fnb_reviews_list.review_list_id")

            ->leftJoin("fnb_table","{$this->table}.table_id","fnb_table.table_id");
        if(isset($input['table_id']) && $input['table_id'] != null){
            $oSelect = $oSelect->where( "{$this->table}.table_id",$input['table_id'] );
        }
        if (isset($input["created_at"]) && $input["created_at"] != null) {
            $arr_filter_create = explode(" - ", $input["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $oSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        return $oSelect->get();

    }

    public function checkUsing($review_list_detail_id){
        return $this
            ->whereJsonContains('review_list_detail_id',$review_list_detail_id)
            ->get();
    }
}
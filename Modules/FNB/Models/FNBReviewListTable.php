<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:24 AM
 */

namespace Modules\FNB\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class FNBReviewListTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_reviews_list';
    protected $primaryKey = 'review_list_id';
    protected $fillable
        = [
            'review_list_id',
            'name',
            'value',
            'order',
            'created_at'
        ];

    public $timestamps = false;

    public function _getList(&$filter = []){
        $oSelect = $this;

        return $oSelect->orderBy('order','ASC');
    }

    public function getAll(){
        return $this->orderBy('value','ASC')->get();
    }

    public function allReview( &$input = []){
        $oSelect = $this
            ->select(
                "{$this->table}.review_list_id",
                "{$this->table}.name as  review_name",
                "{$this->table}.value",
                "{$this->table}.order",
                "{$this->table}.created_at"
            );
        return $oSelect->get();
    }
}
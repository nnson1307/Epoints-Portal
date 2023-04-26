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

class FNBReviewListDetailTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_reviews_list_detail';
    protected $primaryKey = 'review_list_detail_id';
    protected $fillable
        = [
            'review_list_detail_id',
            'review_list_id',
            'name',
            'created_at'
        ];

    public $timestamps = false;



    public function _getList(&$filter = []){
        $oSelect = $this
            ->select(
                $this->table.'.review_list_detail_id',
                $this->table.'.review_list_id',
                $this->table.'.name as review_list_detail_name',
                'fnb_reviews_list.name as review_list_name'
            )
            ->join('fnb_reviews_list','fnb_reviews_list.review_list_id',$this->table.'.review_list_id');

        if (isset($filter['name'])){
            $oSelect = $oSelect->where($this->table.'.name','like','%'.$filter['name'].'%');
            unset($filter['name']);
        }

        if (isset($filter['review_list_id'])){
            $oSelect = $oSelect->where($this->table.'.review_list_id',$filter['review_list_id']);
            unset($filter['review_list_id']);
        }

        return $oSelect->orderBy('review_list_detail_id','DESC');
    }

    public function insertData($data){
        return $this->insertGetId($data);
    }

    public function getDetail($id){
        return $this
            ->where('review_list_detail_id',$id)
            ->first();
    }

    public function editData($data,$id){
        return $this
            ->where('review_list_detail_id',$id)
            ->update($data);
    }

    public function listReviewDetail( &$input = []){
        $oSelect = $this
            ->select(
                "{$this->table}.review_list_detail_id",
                "{$this->table}.review_list_id",
                "{$this->table}.name",
                "{$this->table}.created_at"
            );
        return $oSelect->get();
    }

    public function checkName($review_list_id,$name,$review_list_detail_id = null){
        $oSelect = $this
            ->where('review_list_id',$review_list_id)
            ->where('name',$name);

        if ($review_list_detail_id != null){
            $oSelect = $oSelect->where('review_list_detail_id','<>',$review_list_detail_id);
        }

        return $oSelect->get();
    }

    public function removeReviewListDetail($id){
        return $this
            ->where('review_list_detail_id',$id)
            ->delete();
    }
}
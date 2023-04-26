<?php

namespace Modules\ChatHub\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ChatHubSubBrandTable extends Model
{

    use ListTableTrait;
    protected $table = 'chathub_sub_brand';
    protected $primaryKey = 'sub_brand_id';
    protected $fillable = ['sub_brand_name', 'old_entities','entities', 'sub_brand_status', 'created_at', 'parent_id'];

    public function store($data){
        $insert = $this->create($data);
        return $insert->sub_brand_id;
    }

    public function _getList(array &$filter = []){
        $select = $this->orderBy('sub_brand_id','desc');
        if(isset($filter['sub_brand_name']) && !empty($filter['sub_brand_name'])){
            $select->where('sub_brand_name', 'like', '%' . $filter['sub_brand_name'] . '%');
            unset($filter['sub_brand_name']);
        }
        if(isset($filter['entities']) && !empty($filter['entities'])){
            $select->where('entities', 'like', '%' . $filter['entities'] . '%');
            unset($filter['entities']);
        }
        return $select;
    }

    public function remove($sub_brand_id){
        $this->where('sub_brand_id', '=', $sub_brand_id)->delete();
    }

    public function getSubBrand($sub_brand_id){
        return $this->where('sub_brand_id', '=', $sub_brand_id)->first();
    }
    public function edit($data, $sub_brand_id){
        return $this->where('sub_brand_id', '=', $sub_brand_id)->update($data);
    }
    public function getActive(){
        return $this->where('sub_brand_status', '=', 1)->get();
    }
}

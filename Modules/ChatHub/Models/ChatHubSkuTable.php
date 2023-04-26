<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ChatHubSkuTable extends Model
{
    use ListTableTrait;
    protected $table = 'chathub_sku';
    protected $primaryKey = 'sku_id';
    protected $fillable = ['sku_name', 'old_entities','entities', 'sku_status', 'created_at', 'parent_id', 'sku_entities'];

    public function store($data){
        $insert = $this->create($data);
        return $insert->sku_id;
    }

    public function _getList(array &$filter = []){
        $select = $this->orderBy('sku_id','desc');
        if(isset($filter['sku_name']) && !empty($filter['sku_name'])){
            $select->where('sku_name', 'like', '%' . $filter['sku_name'] . '%');
            unset($filter['sku_name']);
        }
        if(isset($filter['entities']) && !empty($filter['entities'])){
            $select->where('entities', 'like', '%' . $filter['entities'] . '%');
            unset($filter['entities']);
        }

        return $select;
    }

    public function remove($sku_id){
        $this->where('sku_id', '=', $sku_id)->delete();
    }

    public function getSku($sku_id){
        return $this->where('sku_id', '=', $sku_id)->first();
    }
    public function edit($data, $sku_id){
        return $this->where('sku_id', '=', $sku_id)->update($data);
    }
    public function getActive(){
        return $this->where('sku_status', '=', 1)->get();
    }

}

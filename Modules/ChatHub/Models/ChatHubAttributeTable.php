<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ChatHubAttributeTable extends Model
{
    use ListTableTrait;
    protected $table = 'chathub_attribute';
    protected $primaryKey = 'attribute_id';
    protected $fillable = [
        'attribute_id','attribute_name', 'old_entities','entities', 'attribute_status', 'created_at', 'parent_id', 'updated_at','date_start_report','type'];
    // protected $attributes = [
    //     'attribute_id',
    //     'attribute_name'
    // ];
    public function _getList(array &$filter = []){
        $select = $this->orderBy('attribute_id','desc');
        if(isset($filter['attribute_name']) && !empty($filter['attribute_name'])){
            $select->where('attribute_name', 'like', '%' . $filter['attribute_name'] . '%');
            unset($filter['attribute_name']);
        }
        if(isset($filter['entities']) && !empty($filter['entities'])){
            $select->where('entities', 'like', '%' . $filter['entities'] . '%');
            unset($filter['entities']);
        }
        return $select;
    }
    public function store($data){
        $insert = $this->create($data);
        return $insert->attribute_id;
    }
    public function getAttribute($attribute_id){
        return $this->where('attribute_id', '=', $attribute_id)->first();
    }
    public function remove($brand_id){
        $this->where('attribute_id', '=', $brand_id)->delete();
    }

    public function edit($data, $brand_id){
        return $this->where('attribute_id', '=', $brand_id)->update($data);
    }

    public function getActive(){
        return $this->where('attribute_status', '=', 1)->get();
    }
}

<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ChatHubBrandTable extends Model
{

    use ListTableTrait;

    protected $table = 'chathub_brand';
    protected $primaryKey = 'brand_id';
    protected $fillable = ['brand_name', 'old_entities','entities', 'brand_status', 'created_at', 'parent_id'];

    public function store($data){
        $insert = $this->create($data);
        return $insert->brand_id;
    }

    public function _getList(array &$filter = []){
        $select = $this->orderBy('brand_id','desc');
        if(isset($filter['brand_name']) && !empty($filter['brand_name'])){
            $select->where('brand_name', 'like', '%' . $filter['brand_name'] . '%');
            unset($filter['brand_name']);
        }
        if(isset($filter['entities']) && !empty($filter['entities'])){
            $select->where('entities', 'like', '%' . $filter['entities'] . '%');
            unset($filter['entities']);
        }
        return $select;
    }

    public function remove($brand_id){
        $this->where('brand_id', '=', $brand_id)->delete();
    }

    public function getBrand($brand_id){
        return $this->where('brand_id', '=', $brand_id)->first();
    }
    public function edit($data, $brand_id){
        return $this->where('brand_id', '=', $brand_id)->update($data);
    }
    public function getActive(){
        return $this->where('brand_status', '=', 1)->get();
    }

    /**
     * Tạo lựa chọn cho chathub brand
     *
     * @author: nhandt
     * @return mixed
     */
    public function getOptionChatHubBrand(){
        return $this->where("brand_status","=","1")->get()->toArray();
    }
}

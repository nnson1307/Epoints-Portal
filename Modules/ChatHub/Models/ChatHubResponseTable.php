<?php

namespace Modules\ChatHub\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ChatHubResponseTable extends Model
{
    use ListTableTrait;
    protected $table = 'chathub_response';
    protected $primaryKey = 'response_id';
    protected $fillable = [
        'response_id', 'response_name', 'response_status', 'response_content', 'brand',
        'sub_brand', 'sku', 'attribute', 'created_at', 'updated_at'];

    public function _getList(array &$filter = []){
        $select = $this->select(
            "{$this->table}.response_id",
            "{$this->table}.response_name",
            "{$this->table}.response_status",
            "{$this->table}.brand",
            "{$this->table}.sub_brand",
            "{$this->table}.sku",
            "{$this->table}.attribute",
            "chathub_response_content.response_content",
        )->leftJoin("chathub_response_content","{$this->table}.response_content","=","chathub_response_content.response_content_id");
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where("{$this->table}.response_name", 'like', '%' . $search . '%')
                    ->orWhere('chathub_response_content.response_content', 'like', '%' . $search . '%');
            });
        }
        return $select->orderBy("{$this->table}.response_id","ASC");
    }
    public function remove($id)
    {
        return $this->where("{$this->primaryKey}",$id)->delete();
    }
    public function getDataViewEdit($id)
    {
        $data = $this->where("{$this->primaryKey}",$id)->first();
        return $data;
    }
    public function saveUpdate($item,$id)
    {
        return $this->where($this->primaryKey,$id)->update($item);
    }
    public function insertData($item)
    {
        return $this->create($item)->{$this->primaryKey};
    }
}

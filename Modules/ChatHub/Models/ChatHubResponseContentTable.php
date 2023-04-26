<?php

namespace Modules\ChatHub\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ChatHubResponseContentTable extends Model
{
    use ListTableTrait;
    protected $table = 'chathub_response_content';
    protected $primaryKey = 'response_content_id';
    protected $fillable = [
        'response_content_id ','title', 'response_content', 'response_end', 'response_target', 'is_personalized',
        'is_multi_response', 'created_at', 'updated_at', 'response_forward', 'brand_entities', 'link_id', 'type_message', 'template_type'];

    public function _getList(array &$filter = []){
        $select = $this->orderBy('response_content_id','ASC');
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('response_content', 'like', '%' . $search . '%');
            });
        }
        return $select;
    }
    public function remove($id)
    {
        return $this->where('response_content_id',$id)->delete();
    }
    public function getDataViewEdit($id)
    {
        $data = $this->where('response_content_id',$id)->first();
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
    public function getActive()
    {
        return $this->get();
    }
    public function getById($response_content_id){
        return $this->where('response_content_id', $response_content_id)->first();
    }
    public function updateType($response_content_id, $data){
        $this->where('response_content_id', $response_content_id)->update($data);
    }
}

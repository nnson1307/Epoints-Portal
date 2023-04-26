<?php

namespace Modules\ChatHub\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\DB;

class ChatHubResponseDetailElementTable extends Model
{
    use ListTableTrait;
    protected $table = 'chathub_response_detail_element';
    protected $primaryKey = 'response_detail_element_id';
//    protected $fillable = [
//        'brand','sub_brand', 'sku', 'attribute', 'response_content', 'response_element_id',
//        'response_status', 'created_at', 'type', 'title', 'type_message', 'type_message', 'type_link', 'template_type'];

    public function store($data){
        $insert = $this->create($data);
        return $insert->response_detail_element_id;
    }
    public function getDetailElementByContent($response_content_id){
        $data = $this->where("{$this->table}.response_content_id",$response_content_id)->get()->toArray();
        return $data;
    }
}

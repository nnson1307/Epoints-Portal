<?php

namespace Modules\ChatHub\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ChatHubResponseButtonTable extends Model
{

    use ListTableTrait;

    protected $table = 'chathub_response_button';
    protected $primaryKey = 'response_button_id';
    protected $fillable = ['response_button_id', 'response_element_id','title', 'type', 'payload', 'url', 'created_at', 'updated_at'];

    public function store($data){
        $insert = $this->create($data);
        return $insert->response_button_id;
    }

    public function _getList(array &$filter = []){
        $select = $this->orderBy('response_button_id','ASC');
        if(isset($filter['response_button_name']) && !empty($filter['response_button_name'])){
            $select->where('title', 'like', '%' . $filter['response_button_name'] . '%');
            unset($filter['response_button_name']);
        }
        
        if(isset($filter['data_time'])){
            $time=Carbon::createFromFormat('m/d/Y', $filter['data_time'])->format('Y-m-d');
            $select->where($this->table.'.created_at', '>=', $time)->where($this->table.'.created_at', '<=', $time . ' 23:59:59');
            unset($filter['data_time']);
        }
        return $select;
    }

    public function remove($response_button_id){
        $this->where('response_button_id', '=', $response_button_id)->delete();
    }

    public function getResponseButton($response_button_id){
        return $this->where('response_button_id', '=', $response_button_id)->first();
    }
    public function edit($data, $response_button_id){
        return $this->where('response_button_id', '=', $response_button_id)->update($data);
    }
    public function getActive(){
        return $this->get();
    }
    //Nhandt
    public function create($data){
        unset($data['_token']);
        $data['created_at']=Carbon::now();
        $response_element_id = $data['response_element_id'];
        unset($data['response_element_id']);
        $response_button_id = $this->insertGetId($data);
        $insertMap = [
            'response_element_id'=> $response_element_id,
            'response_button_id'=>$response_button_id,
            'created_at'=>Carbon::now()
        ];
        DB::table('chathub_response_element_button')->insert($insertMap);
        return $response_button_id;
    }
    public function updateButton($data){
        unset($data['_token']);
        $data['updated_at']=Carbon::now();
        $id=$data['response_button_id'];
        unset($data['response_button_id']);
        return $this->where('response_button_id', '=',$id)->update($data);
    }
    public function removeButton($response_button_id){
        $this->where('response_button_id',$response_button_id)->delete();
        DB::table('chathub_response_element_button')->where('response_button_id',$response_button_id)->delete();
    }
    public function getById($id){
        return $this->where('response_button_id', '=', $id)->first();
    }
}

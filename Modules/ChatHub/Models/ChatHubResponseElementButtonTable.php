<?php

namespace Modules\ChatHub\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ChatHubResponseElementButtonTable extends Model
{
    protected $table = 'chathub_response_element_button';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'response_element_button_id', 'response_element_id', 'response_button_id', 'created_at'];
    protected $primaryKey = 'response_element_button_id';
    
    public function store($data){
        $insert = $this->create($data);
        return $insert->response_element_button_id;
    }
    
    public function remove($response_element_id){
        $this->where('response_element_id', '=', $response_element_id)->delete();
    }



}
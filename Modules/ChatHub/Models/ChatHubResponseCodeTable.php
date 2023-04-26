<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ChatHubResponseCodeTable extends Model
{
    protected $table = 'chathub_response_code';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'response_code_id', 'code', 'status', 'created_at','use_at'
    ];

    public function getList($filter = null){
        $codeList= $this->orderBy('response_code_id', 'ASC');
        if(!empty($filter->data_value)){
            $codeList->where('code', 'like', '%' . $filter->data_value . '%');
        }
        if(!empty($filter->data_time)){
            $time=Carbon::createFromFormat('m/d/Y', $filter->data_time)->format('Y-m-d');
            $codeList->where($this->table.'.use_at', '>=', $time)->where($this->table.'.use_at', '<=', $time . ' 23:59:59');
        }
        if($filter->data_active !== null){
            $codeList->where('status', '=', $filter->data_active);
        }
        return $codeList->paginate(25);
    }
}

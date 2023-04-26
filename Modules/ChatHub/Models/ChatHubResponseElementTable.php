<?php

namespace Modules\ChatHub\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Input;
use Modules\ChatHub\Models\ChatHubResponseButtonTable;
use Modules\ChatHub\Models\ChatHubResponseElementButtonTable;
use MyCore\Models\Traits\ListTableTrait;


class ChatHubResponseElementTable extends Model
{
    use ListTableTrait;
    protected $table = 'chathub_response_element';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'response_element_id', 'response_detail_id', 'title', 'subtitle', 'image_url', 'created_at', 'updated_at'];
    
    protected $primaryKey = 'response_element_id';

    public function store($data){
        $insert = $this->create($data);
        return $insert->response_element_id;
    }
    public function _getList(array &$filter = []) { 
        $select=$this->orderBy('response_element_id','ASC');
                // ->belongsToMany(ChatHubResponseButtonTable::class, 'chathub_response_element_button', 'response_element_id', 'response_button_id');
        if(!empty($filter['data_value'])){
            $select->where('title', 'LIKE', "%{$filter['data_value']}%")
                    ->orwhere('subtitle', 'LIKE', "%{$filter['data_value']}%");
            unset($filter['data_value']);
        }
        if(isset($filter['data_time'])){
            $time=Carbon::createFromFormat('m/d/Y', $filter['data_time'])->format('Y-m-d');
            $select->where($this->table.'.created_at', '>=', $time)->where($this->table.'.created_at', '<=', $time . ' 23:59:59');
            unset($filter['data_time']);
        }
        // foreach($select as $index=>$item){
        //     $item['child']= DB::table('chatbot_response_element_button')->join('chatbot_response_button','chatbot_response_element_button.response_button_id', '=','chatbot_response_button.response_button_id')
        //                                                                 ->where('chatbot_response_element_button.response_element_id','=',$item['response_element_id'])
        //                                                                 ->get();
        // }
        return $select;
    }
    public function getById($id) { 
        $select=$this->where($this->table.'.response_element_id','=',$id)->first();
        $select['child']= DB::table('chathub_response_element_button')->join('chathub_response_button','chathub_response_element_button.response_button_id', '=','chathub_response_button.response_button_id')
                                                                    ->where('chathub_response_element_button.response_element_id','=',$select
                                                                    ['response_element_id'])
                                                                    ->get();
        return $select;
    }
    public function remove($response_element_id){
        $this->where('response_element_id', '=', $response_element_id)->delete();
    }
    public function edit($data, $response_element_id){
        $insert = $this->where('response_element_id', '=', $response_element_id)->update($data);
    }
    public function getActive(){
        return $this->get();
    }
    // Nhandt
    public function create($data){
        $insert=[
            'title'=>$data['title'],
            'subtitle'=>$data['subtitle'],
            'image_url'=> $data['image_url'],
            'created_at'=>Carbon::now()
        ];
        $id=$this->insertGetId($insert);
        return $id;
    }
    public function updateTemplate($data){
        if($data->hasFile('getFileLogo')){
            $image = $data->file('getFileLogo');
            $getImage = time().'_'.$image->getClientOriginalName();
            $destinationPath = public_path('static/image/');
            // dd($destinationPath);
            $image->move($destinationPath, $getImage);
        }
        $insert=[
            'title'=>$data['title'],
            'subtitle'=>$data['subtitle'],
            'image_url'=> $data['image_url'],
            'created_at'=>Carbon::now()
        ];
        $this->where('response_element_id','=', $data['response_element_id'])->update($insert);
    }

    protected function getSelected($id){
        $select= $this->select($this->table.'.*')
            ->join('chathub_response_detail_element as de', 'de.response_element_id', '=', 'chathub_response_element.response_element_id')
            // ->join('chathub_response_detail as d', 'd.response_detail_id', '=', 'de.response_detail_id')
            ->join('chathub_response_content as c', 'c.response_content_id', '=', 'de.response_content_id')
            ->where('c.response_content_id', '=', $id)->get();
        foreach($select as $item){
            $item['child']= DB::table('chathub_response_element_button')->join('chathub_response_button','chathub_response_element_button.response_button_id', '=','chathub_response_button.response_button_id')
                ->where('chathub_response_element_button.response_element_id','=',$item['response_element_id'])
                ->get();
        }
        return $select;

    }
}
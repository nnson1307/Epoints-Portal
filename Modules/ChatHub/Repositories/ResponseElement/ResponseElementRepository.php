<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */
namespace Modules\ChatHub\Repositories\ResponseElement;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubResponseElementTable;
use Modules\ChatHub\Models\ChatHubResponseElementButtonTable;

class ResponseElementRepository implements ResponseElementRepositoryInterface
{
    public function __construct(
        ChatHubResponseElementTable $response_element,
        ChatHubResponseElementButtonTable $response_element_button
    )
    {
        $this->response_element = $response_element;
        $this->response_element_button = $response_element_button;
    }
    public function create($data){
        $insert=[
            'title'=>$data['title'],
            'subtitle'=>$data['subtitle'],
            'image_url'=> isset($data['image_url'])?$data['image_url']:null,
            'created_at'=>Carbon::now()
        ];
        $id=$this->response_element->store($insert);
        if(isset($data['response_button'])){
            foreach($data['response_button'] as $item){
                $button=[
                    'response_button_id'=>$item,
                    'response_element_id'=>$id,
                    'created_at'=>Carbon::now()
                ];
                DB::table('chathub_response_element_button')->insert($button);
            }
        }
        
    }
    public function getList($filters = null){
        return $this->response_element->getList($filters);
    }
    public function delete($response_element_id){
        $this->response_element->remove($response_element_id);
        $this->response_element_button->remove($response_element_id);
    }

    public function getResponseElement($response_element_id){
        return $this->response_element->getById($response_element_id);
    }
    public function update($data){       
        if(isset($data['image_url'])){            
            $insert=[
                'title'=>$data['title'],
                'subtitle'=>$data['subtitle'],
                'image_url'=> $data['image_url']=="0"?null: $data['image_url'],
                'created_at'=>Carbon::now()
            ];
        }
        else{
            $insert=[
                'title'=>$data['title'],
                'subtitle'=>$data['subtitle'],
                'created_at'=>Carbon::now()
            ];
        }
        $this->response_element->edit($insert, $data['response_element_id']);
        DB::table('chathub_response_element_button')->where('response_element_id','=', $data['response_element_id'])->delete();
        if(isset($data['response_button'])){
            foreach($data['response_button'] as $item){
                $button=[
                    'response_button_id'=>$item,
                    'response_element_id'=>$id,
                    'created_at'=>Carbon::now()
                ];
                DB::table('chathub_response_element_button')->insert($button);
            }
        }
    }
    public function getActive(){
        return $this->response_element->getActive();
    }
}
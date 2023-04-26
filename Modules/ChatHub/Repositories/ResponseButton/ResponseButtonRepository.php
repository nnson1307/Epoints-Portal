<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */
namespace Modules\ChatHub\Repositories\ResponseButton;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubResponseButtonTable;

class ResponseButtonRepository implements ResponseButtonRepositoryInterface
{
    public function __construct(
        ChatHubResponseButtonTable $response_button
    )
    {
        $this->response_button = $response_button;
    }
    public function create($data){
        $data['created_at']=date('Y-m-d H:i:s');
        $this->response_button->store($data);
    }
    public function getList($filters = null){
        return $this->response_button->getList($filters);
    }
    public function delete($response_button_id){
        $this->response_button->remove($response_button_id);
    }

    public function getResponseButton($response_button_id){
        return $this->response_button->getResponseButton($response_button_id);
    }

    public function update($data){
        $data['updated_at']=date('Y-m-d H:i:s');
        $response_button_id=$data['response_button_id'];
        $this->response_button->edit($data, $response_button_id);
    }
    public function getActive(){
        return $this->response_button->getActive();
    }
}
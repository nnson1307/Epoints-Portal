<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */
namespace Modules\ChatHub\Repositories\ResponseDetail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubAttributeTable;
use Modules\ChatHub\Models\ChatHubBrandTable;
use Modules\ChatHub\Models\ChatHubResponseDetailTable;
use Modules\ChatHub\Models\ChatHubSkuTable;
use Modules\ChatHub\Models\ChatHubSubBrandTable;

class ResponseDetailRepository implements ResponseDetailRepositoryInterface
{
    protected $response_detail;
    public function __construct(
        ChatHubResponseDetailTable $response_detail
    )
    {
        $this->response_detail = $response_detail;
    }
    public function create($data){
        $data['created_at']=date('Y-m-d H:i:s');
        if(!isset($data['brand'])){
            $data['brand']=[0=>'0'];
        }
        if(!isset($data['sub_brand'])){
            $data['sub_brand']=[0=>'0'];
        }
        if(!isset($data['sku'])){
            $data['sku']=[0=>'0'];
        }
        if(!isset($data['attribute'])){
            $data['attribute']=[0=>'0'];
        }
        foreach($data['brand'] as $brand){
            foreach($data['sub_brand'] as $sub_brand){
                foreach($data['sku'] as $sku){
                    foreach($data['attribute'] as $attribute){
                        $insert=[];
                        $insert['brand']=$brand;
                        $insert['sub_brand']=$sub_brand;
                        $insert['sku']=$sku;
                        $insert['attribute']=$attribute;
                        $insert['response_content']=$data['response_content'];
                        if($data['response_element_id']){
                            $insert['type_message']='template';
                            $insert['template_type']='generic';
                            $insert['response_element_id']=$data['response_element_id'];
                        }else{
                            $insert['type_message']='define';
                        }
                        $this->response_detail->store($insert);
                    }
                }
            }
        }
        // if($data['response_element_id']){
        //     $data['type_message']='template';
        //     $data['template_type']='generic';
        // }else{
        //     $data['type_message']='define';
        // }
        // $this->response_detail->store($data);
    }
    public function getList($filters = null){
        return $this->response_detail->getList($filters);
    }
    public function delete($response_detail_id){
        $this->response_detail->remove($response_detail_id);
    }

    public function getResponseDetail($response_detail_id){
        return $this->response_detail->getResponseDetail($response_detail_id);
    }

    public function update($data){
        $data['updated_at']=date('Y-m-d H:i:s');
        $response_detail_id=$data['response_detail_id'];
        $this->response_detail->edit($data, $response_detail_id);
    }
    public function getActive(){
        return $this->response_detail->getActive();
    }

    public function getDetail(&$filter, $id = 'all')
    {
        $arrDetail  = $this->response_detail->getDetail($filter, $id);
        $mBrand = new ChatHubBrandTable();
        $data['arrBrand'] = $mBrand->getActive()->toArray();

        $mSubBrand = new ChatHubSubBrandTable();
        $data['arrSubBrand'] = $mSubBrand->getActive()->toArray();

        $mSku = new ChatHubSkuTable();
        $data['arrSku'] = $mSku->getActive()->toArray();

        $mAttribute = new ChatHubAttributeTable();
        $data['arrAttribute'] = $mAttribute->getActive()->toArray();

        $data['object'] = $arrDetail;
        $data['params'] = $filter;
        $data['response_id'] = $id;
        return $data;
    }
}
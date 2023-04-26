<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */
namespace Modules\ChatHub\Repositories\SubBrand;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubSubBrandTable;

class SubBrandRepository implements SubBrandRepositoryInterface
{
    public function __construct(
        ChatHubSubBrandTable $sub_brand
    )
    {
        $this->sub_brand = $sub_brand;
    }
    public function create($data){
        $data['created_at']=date('Y-m-d H:i:s');
        $this->sub_brand->store($data);
    }
    public function getList($filters = null){
        $sub_brand =$this->sub_brand->getList($filters);
        return $this->sub_brand->getList($filters);
    }
    public function delete($sub_brand_id){
        $this->sub_brand->remove($sub_brand_id);
    }

    public function getSubBrand($sub_brand_id){
        return $this->sub_brand->getSubBrand($sub_brand_id);
    }

    public function update($data){
        $data['updated_at']=date('Y-m-d H:i:s');
        $sub_brand_id=$data['sub_brand_id'];
        $this->sub_brand->edit($data, $sub_brand_id);
    }
    public function getActive(){
        return $this->sub_brand->getActive();
    }
}
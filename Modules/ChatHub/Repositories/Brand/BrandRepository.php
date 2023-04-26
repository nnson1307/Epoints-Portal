<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */
namespace Modules\ChatHub\Repositories\Brand;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubBrandTable;

class BrandRepository implements BrandRepositoryInterface
{

    protected $brand;
    public function __construct(
        ChatHubBrandTable $brand
    )
    {
        $this->brand = $brand;
    }
    public function create($data){
        $data['created_at']=date('Y-m-d H:i:s');
        $this->brand->store($data);
    }
    public function getList($filters = null){
        return $this->brand->getList($filters);
    }
    public function delete($brand_id){
        $this->brand->remove($brand_id);
    }

    public function getBrand($brand_id){
        return $this->brand->getBrand($brand_id);
    }

    public function update($data){
        $data['updated_at']=date('Y-m-d H:i:s');
        $brand_id=$data['brand_id'];
        $this->brand->edit($data, $brand_id);
    }
    public function getActive(){
        return $this->brand->getActive();
    }
}

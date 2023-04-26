<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */
namespace Modules\ChatHub\Repositories\Sku;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubSkuTable;

class SkuRepository implements SkuRepositoryInterface
{
    protected $sku;
    public function __construct(
        ChatHubSkuTable $sku
    )
    {
        $this->sku = $sku;
    }
    public function create($data){
        $data['created_at']=date('Y-m-d H:i:s');
        $this->sku->store($data);
    }
    public function getList($filters = null){
        return $this->sku->getList($filters);
    }
    public function delete($sku_id){
        $this->sku->remove($sku_id);
    }

    public function getSku($sku_id){
        return $this->sku->getSku($sku_id);
    }

    public function update($data){
        $data['updated_at']=date('Y-m-d H:i:s');
        $sku_id=$data['sku_id'];
        $this->sku->edit($data, $sku_id);
    }
    public function getActive(){
        return $this->sku->getActive();
    }
}

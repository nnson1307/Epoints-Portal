<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/13/2018
 * Time: 1:29 PM
 */

namespace Modules\Admin\Repositories\ProductOrigin;
use Modules\Admin\Models\ProductOriginTable;

class ProductOriginRepository implements ProductOriginRepositoryInterface
{
    protected $productorigin;
    protected $timestamps = true;

    public function __construct(ProductOriginTable $productorigin)
    {
        $this->productorigin = $productorigin;
    }

    /**
     * Lấy danh sách product origin
     */
    public function list(array $filters=[]){
        return $this->productorigin->getList($filters);
    }

    /**
     * Xóa danh sách product origin
     */
    public function remove($id)
    {
         $this->productorigin->remove($id);
    }

    /**
     * Thêm  product origin
     */
    public function add(array $data){
        return $this->productorigin->add($data);
    }
    /**
     * Edit product origin
     */
    public function edit(array $data,$id){
        return $this->productorigin->edit($data,$id);
    }
    public function getEdit($id)
    {
        // TODO: Implement getEdit() method.
        return $this->productorigin->getEdit($id);
    }

}
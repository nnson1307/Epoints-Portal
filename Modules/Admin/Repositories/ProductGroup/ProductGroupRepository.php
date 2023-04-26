<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/03/2018
 * Time: 11:18 AM
 */

namespace Modules\Admin\Repositories\ProductGroup;

use Modules\Admin\Models\ProductGroupTable;

class ProductGroupRepository implements ProductGroupRepositoryInterface
{
    protected $productGroup;
    protected $timestamps = true;


    public function __construct(ProductGroupTable $productGroup)
    {
        $this->productGroup = $productGroup;
    }


    /**
     * Lấy danh sách user
     */
    public function list(array $filters = [])
    {
        return $this->productGroup->getList($filters);
    }


    /**
     * Xóa user
     */
    public function remove($id)
    {
        $this->productGroup->remove($id);
    }

//    public function getListProvinceOptions()
//    {
//        $this->user->getListProvinceOptions();
//    }
//
//    public function getListDistrictOptions($id)
//    {
//        $this->user->getListDistrictOptions($id);
//    }
//
//    public function getxa($id)
//    {
//        $this->user->getxa($id);
//    }
    /**
     * Thêm user
     */
    public function add(array $data)
    {
//        $data['password'] = bcrypt($data['password']);

        return $this->productGroup->add($data);
    }

    public function  edit(array $data,$id)
    {
//        if(!empty($data['password'])){
//            $data['password']=bcrypt ($data['password']);
//        }
        return $this->productGroup->edit($data,$id);
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->productGroup->getItem($id);
    }


}
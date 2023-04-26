<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:48 CH
 */

namespace Modules\Admin\Repositories\ProductUnit;


use Modules\Admin\Models\ProductUnitTable;

class ProductUnitRepository implements ProductUnitRepositoryInterface
{

    /**
     * @var ProductUnitTable
     */
    protected $productUnit;
    protected $timestamps = true;

    public function __construct(ProductUnitTable $productUnit)
    {
        $this->productUnit = $productUnit;
    }


    /**
     * Lấy danh sách product
     */
    public function list(array $filters = [])
    {
        return $this->productUnit->getList($filters);
    }


    /**
     * Xóa product
     */
    public function remove($id)
    {
        $this->productUnit->remove($id);
    }


    /**
     * Thêm productUnit
     */
    public function add(array $data)
    {

        return $this->productUnit->add($data);

    }
    // function update
    public function  edit(array $data,$id)
    {
        try{
            if ($this->productUnit->edit($data ,$id) === false) throw new \Exception() ;
            return $id;
        }
        catch(\Exception  $e){
            $e->getMessage();
        }
        return false;
    }
    // function get item
    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->productUnit->getItem($id);
    }

}

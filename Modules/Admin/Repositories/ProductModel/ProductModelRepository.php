<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/28/2018
 * Time: 4:59 PM
 */

namespace Modules\Admin\Repositories\ProductModel;

use Modules\Admin\Models\ProductModelTable;

class ProductModelRepository implements ProductModelRepositoryInterface
{
    /**
     * @var ProductModelTable
     */
    protected $productModel;
    protected $timestamps = true;

    public function __construct(ProductModelTable $productModel)
    {
        $this->productModel = $productModel;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->productModel->getList($filters);
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->productModel->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->productModel->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->productModel->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->productModel->getItem($id);
    }

    /*
     * get product model
     */
    public function getAll()
    {
        $array = [];
        $data = $this->productModel->getAll();
        foreach ($data as $item) {
            $array[$item['product_model_id']] = $item['product_model_name'];
        }
        return $array;
    }

    /*
     * get option edit product
     */
    public function getOptionEditProduct($id)
    {
        return $this->productModel->getOptionEditProduct($id);
    }

    //Kiểm tra tồn tại của nhãn sp.
    public function check($name, $isDelete)
    {
        return $this->productModel->check($name, $isDelete);
    }

    /*
   * Cập nhật với tên nhãn
   */
    public function editByName($name)
    {
        return $this->productModel->editByName($name);
    }

    /*
   * check unique.
   */
    public function checkEdit($id, $name)
    {
        return $this->productModel->checkEdit($id, $name);
    }
}
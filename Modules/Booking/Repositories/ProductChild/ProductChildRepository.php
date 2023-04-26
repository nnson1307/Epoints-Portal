<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:32 AM
 */

namespace Modules\Booking\Repositories\ProductChild;

use Modules\Booking\Models\ProductChildTable;

class ProductChildRepository implements ProductChildRepositoryInterface
{
    /**
     * @var ProductChildTable
     */
    protected $productChild;
    protected $timestamps = true;

    public function __construct(ProductChildTable $productChild)
    {
        $this->productChild = $productChild;
    }

    //Lấy danh sách sản phẩm.
    public function getProductChild(array $filter = [])
    {
        return $this->productChild->getProductChild($filter);
    }

    /*
     * get product child by id
     */
    public function getProductChildById($id)
    {
        return $this->productChild->getProductChildById($id);
    }
}
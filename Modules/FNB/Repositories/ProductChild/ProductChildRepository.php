<?php


namespace Modules\FNB\Repositories\ProductChild;


use Modules\Admin\Models\ProductTable;
use Modules\FNB\Models\ProductChildTable;

class ProductChildRepository implements ProductChildRepositoryInterface
{
    private $productChild;

    public function __construct(ProductChildTable $productChild)
    {
        $this->productChild = $productChild;
    }

    /**
     * Cập nhật
     * @param $productId
     * @return mixed|void
     */
    public function updateNameProductChild($productId)
    {
        $mProduct = app()->get(ProductTable::class);

//        Lấy chi tiết sản phẩm
        $detail = $mProduct->getItem($productId);

//        Lấy danh sách sản phẩm con
        $list = $this->productChild->getProductChildByProductId($productId);
        $nameVI = $detail['productName'];
        $nameEN = $detail['productNameEn'];

        foreach ($list as $item){
            $dataTmp = [];
            $nameENTmp = explode('/',$item['product_child_name'],2);
            $nameENMain = $nameEN;
            if(isset($nameENTmp[1])){
                $nameENMain = $nameENMain.'/'.$nameENTmp[1];
            }

            $dataTmp = [
                'product_child_name_en' => $nameENMain,
                'slug_en' => str_slug($nameENMain),
            ];

            $this->productChild->edit($dataTmp,$item['product_child_id']);
        }
        return true;
    }

    /**
     * Lấy danh sách sản phẩm cơn có phân trang
     * @param $data
     * @return mixed|void
     */
    public function getListProductChild(array $filters = [])
    {
        $list = session()->get('list-topping');
        $filters['list_product_child_id'] = [];
        $filters['is_topping'] = 1;
        if (count($list) != 0) {
            $filters['list_product_child_id'] = collect($list)->pluck('product_child_id');
        }
        return $this->productChild->getListPagination($filters);
    }

    /**
     * Lấy thông tin product cha
     * @param $productChildId
     * @return mixed|void
     */
    public function getParentProduct($productChildId)
    {
        return $this->productChild->getItem($productChildId);
    }

    /**
     * Lấy product child master
     * @param $productId
     * @return mixed|void
     */
    public function getParentProductMaster($productId)
    {
        return $this->productChild->getItemMaster($productId);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->productChild->getItem($id);
    }

    /*
     * get product child by code
     */
    public function getProductChildByCode($code)
    {
        return $this->productChild->getProductChildByCode($code);
    }
}
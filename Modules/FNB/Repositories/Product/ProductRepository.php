<?php


namespace Modules\FNB\Repositories\Product;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Admin\Models\ProductTable;
use Modules\FNB\Repositories\ProductChild\ProductChildRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    private $product;

    public function __construct(ProductTable $product)
    {
        $this->product = $product;
    }

    public function getDetail($productId)
    {
        $mProduct = app()->get(\Modules\FNB\Models\ProductTable::class);
        return $mProduct->getItem($productId);
    }

    /**
     * Cập nhật tiếng anh
     * @param $data
     * @return mixed|void
     */
    public function update($data)
    {
        try {

            $rProductChild = app()->get(ProductChildRepositoryInterface::class);

            $productNameEN = $data['productNameEN'];
            $id = $data['id'];
            $dataEditProduct = [
                'product_name_en' => $productNameEN,
                'slug_en' => str_slug($productNameEN),
                'description_en' => $data['description_en'],
                'description_detail_en' => $data['description_detail_en'],
            ];
            $this->product->edit($dataEditProduct, $id);

            $rProductChild->updateNameProductChild($id);

            return [
                'error'=> false,
                'message' => __('Cập nhật sản phẩm thành công')
            ];
        }catch (Exception $e){
            return [
                'error'=> false,
                'message' => __('Cập nhật sản phẩm thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Kiểm tra tên tiếng anh
     * @param $data
     * @return mixed|void
     */
    public function checkNameAction($data)
    {
        $productNameEN = $data['productNameEN'];
        $id = $data['id'];
        $message = __('Sản phẩm đã tồn tại');
        $checkResultEN = $this->product->checkNameEN(str_slug($productNameEN), $id);
        if ($checkResultEN == null) {
            return [
                'error' => false,
            ];
        } else {
            return [
                'error' => true,
                'message' => $message,
            ];
        }
    }

    /**
     * Cập nhật tất cả
     * @param $data
     * @param $id
     * @return mixed|void
     */
    public function updateAll($data, $id)
    {
        return $this->product->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->product->getItem($id);
    }
}
<?php


namespace Modules\FNB\Repositories\ProductTopping;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\ProductChildTable;
use Modules\FNB\Models\ProductToppingTable;
use Modules\FNB\Repositories\Product\ProductRepositoryInterface;

class ProductToppingRepository implements ProductToppingRepositoryInterface
{
    private $productTopping;

    public function __construct(ProductToppingTable $productTopping)
    {
        $this->productTopping = $productTopping;
    }

    /**
     * Lấy danh sách topping đã được thêm
     * @param $data
     * @return mixed|void
     */
    public function getAllTopping($productId){
        $data['product_id'] = $productId;
        return $this->productTopping->getALl($data);
    }

    /**
     * Lưu topping vào session
     * @param $data
     * @return mixed|void
     */
    public function storeTopping($data)
    {
        try {
            $rProduct = app()->get(ProductRepositoryInterface::class);
            $mProductChild = app()->get(ProductChildTable::class);
            $list = session()->get('list-topping');
            $tmp = [];
            if (count($list) != 0){
                $tmp = collect($list)->pluck('product_child_id')->toArray();
            }

//            Lấy danh sách các product_child_id
            $listChild = $mProductChild->getAllByArrChildID($tmp);

            if (count($listChild) != 0){
                $listChild = collect($listChild)->keyBy('product_child_id');
            }

//            Xóa các topping thuộc sản phẩm
            $this->productTopping->removeProductTopping($data['product_id']);

            $dataTopping = [];
            foreach ($list as $item){
                $dataTopping[] = [
                    'product_id' => $data['product_id'],
                    'product_child_id' => $item['product_child_id'],
                    'unit_id' => isset($listChild[$item['product_child_id']]) ? $listChild[$item['product_child_id']]['unit_id'] : '',
                    'price' => isset($listChild[$item['product_child_id']]) ? $listChild[$item['product_child_id']]['price'] : '',
                    'quantity' => $item['quantity'],
                    'is_actived' => 1,
                    'is_deleted' => 0,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];
            }

            $this->productTopping->addTopping($dataTopping);

//            Cập nhật is_topping
            $is_topping = isset($data['is_topping']) ? 1 : 0;
            $rProduct->updateAll(['is_topping' => $is_topping],$data['product_id']);

            return [
                'error' => false,
                'message' => __('Luu sản phẩm đi kèm thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Luu sản phẩm đi kèm thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Thêm product_child_id vào session
     * @param $data
     * @return mixed|void
     */
    public function addToppingSession($data)
    {
        try {
            $list = session()->get('list-topping');

            $productChildId = $data['product_child_id'];
            $productChildName = $data['product_child_name'];
            if (isset($list[$productChildId])) {
//                $list[$productChildId]['quantity'] = $list[$productChildId]['quantity'] + (isset($data['quantity']) ? (int)$data['quantity'] : 1);
                $list[$productChildId]['quantity'] = isset($data['is_quantity']) && $data['is_quantity'] == true ? (int)$data['quantity'] : $list[$productChildId]['quantity'] + 1;
            } else {
                $list[$productChildId] = [
                    'product_child_id' => $productChildId,
                    'product_child_name' => $productChildName,
                    'quantity' => isset($data['quantity']) ? (int)$data['quantity'] : 1
                ];
            }

            $view = view('fnb::product.append.list-topping', ['listTopping' => $list])->render();

            session()->put('list-topping',$list);
            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true
            ];
        }
    }

    public function removeToppingSession($data)
    {
        try {
            $list = session()->get('list-topping');

            $productChildId = $data['product_child_id'];
            if (isset($list[$productChildId])) {
                unset($list[$productChildId]);
            }

            $view = view('fnb::product.append.list-topping', ['listTopping' => $list])->render();

            session()->put('list-topping',$list);
            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true
            ];
        }
    }

    /**
     * Lấy danh sác topping theo productId
     * @param $productId
     * @return mixed|void
     */
    public function getListTopping($productId)
    {
        return $this->productTopping->getListToppingByProductId($productId);
    }
}
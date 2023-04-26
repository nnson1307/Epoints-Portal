<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 19/05/2021
 * Time: 11:29
 */

namespace Modules\Report\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Report\Repository\ProductInventory\ProductInventoryRepoInterface;

class ProductInventoryController extends Controller
{
    protected $productInventory;

    public function __construct(
        ProductInventoryRepoInterface $productInventory
    ) {
        $this->productInventory = $productInventory;
    }

    /**
     * View báo cáo tồn kho
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->productInventory->dataViewIndex();

        return view('report::product-inventory.index', $data);
    }

    /**
     * Filter - phân trang list chi tiết tồn kho
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function paginateDetailAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'created_at', 'warehouse_id', 'product_id']);

        $data =  $this->productInventory->list($filter);

        return view('report::product-inventory.list', [
            'LIST' => $data['list'],
            'listWarehouse' => $data['optionWarehouse'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Export chi tiết tồn kho
     *
     * @param Request $request
     * @return mixed
     */
    public function exportDetailAction(Request $request)
    {
        return $this->productInventory->exportExcelDetail($request->all());
    }

    /**
     * Lấy option sản phẩm load more
     *
     * @param Request $request
     * @return mixed
     */
    public function getListChildAction(Request $request)
    {
        return $this->productInventory->getListChild($request->all());
    }

}
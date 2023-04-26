<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/8/2018
 * Time: 12:32 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Repositories\InventoryInputDetail\InventoryInputDetailRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutputDetail\InventoryOutputDetailRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\ProductInventory\ProductInventoryRepositoryInterface;
use Modules\Admin\Repositories\Supplier\SupplierRepositoryInterface;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;
use Maatwebsite\Excel\Excel;
use App\Exports\ProductInventoryExport;
use Carbon\Carbon;

class ProductInventoryController extends Controller
{
    protected $productInventory;
    protected $supplier;
    protected $wareHouse;
    protected $productChild;
    protected $inventoryInputDetail;
    protected $inventoryOutputDetail;
    protected $excel;

    public function __construct(
        ProductInventoryRepositoryInterface $productInventory,
        SupplierRepositoryInterface $supplier,
        WarehouseRepositoryInterface $wareHouse,
        ProductChildRepositoryInterface $productChild,
        InventoryInputDetailRepositoryInterface $inventoryInputDetail,
        InventoryOutputDetailRepositoryInterface $inventoryOutputDetail,
        Excel $excel
    )
    {
        $this->productInventory = $productInventory;
        $this->supplier = $supplier;
        $this->wareHouse = $wareHouse;
        $this->productChild = $productChild;
        $this->inventoryInputDetail = $inventoryInputDetail;
        $this->inventoryOutputDetail = $inventoryOutputDetail;
        $this->excel = $excel;
    }

    protected function filters()
    {
        $supplierList = (['' => 'Nhà cung cấp']) + $this->supplier->getAll();
        return [
            'suppliers$supplier_id' => [
                'data' => $supplierList
            ]
        ];
    }

    public function indexAction(Request $request)
    {
        //Danh sách tất cả kho

        $wareHouse = $this->wareHouse->getWareHouseOption();
//        $data = $this->listAction2();
//        $result = collect($data)->forPage(1, 10);
        return view('admin::product-inventory.index',
            [
                'wareHouse' => $wareHouse,
//                'result' => $result,
                'page' => 1,
//                'data' => $data,
            ]);
    }

    private function getListWareHouseByCode($code)
    {
        $data = DB::table('product_childs')
            ->leftJoin('product_inventorys', 'product_inventorys.product_id', '=', 'product_childs.product_id')
            ->leftJoin('warehouses', 'warehouses.warehouse_id', '=', 'product_inventorys.warehouse_id')
            ->select('warehouses.name as wName', 'product_inventorys.quantity as quantitysss')->where('product_childs.product_code', $code)->get();
        return $data->toArray();
    }

    public function getListProductInventory(Request $request)
    {
        $data = $this->productInventory->getListProductInventory();
        $result['meta'] = array(
            "page" => 1,
            "pages" => 1,
            "perpage" => -1,
            "total" => $data->total(),
            "sort" => "asc",
        );
        $i = 1;
        foreach ($data as $item) {
            $result['data'][] = (object)array(
                'id' => $i++,
                'code' => $item['productCode'],
                'name' => $item['productChildName'],
                'wareHouse' => $this->getListWareHouseByCode($item['productCode'])
//                'quantity' => $item['quantity']
            );
        }

        return response()->json($result);
    }

    public function searchByWarehouse(Request $request)
    {
        $warehouse = $request->warehouse;

        $resultSearchWarehouse = $this->wareHouse->searchWhereIn($warehouse);

        $wareHouse = [];

        foreach ($resultSearchWarehouse as $item) {
            $wareHouse[$item['warehouse_id']] = $item['name'];
        }
        //Danh sách tất cả kho
        $wareHouse = $this->wareHouse->getWareHouseOption();
        //Danh sách sản phẩm.
        $product = $this->productChild->getProductChildOption();
        // Danh sách sản phẩm tồn kho.
        $productInventory = $this->productInventory->getProductWhereIn($warehouse);

        //Kết quả thu được.
        $result = [];
        foreach ($product as $a => $b) {
            //Thông tin của sản phẩm.
            $data = [];
            foreach ($resultSearchWarehouse as $c => $d) {
                $data['productCode'] = $a;
                $data['productName'] = $b;
                $data['productInventory'] = $this->productInventory->getQuantityProductInventoryByCode($a)->quantityInventory;
                $data['warehouse'][$c] = 0;
            }
            foreach ($data['warehouse'] as $k => $v) {
                foreach ($productInventory as $key => $value) {
                    if ($a == $value[1] && $k == $value[0]) {
                        $data['warehouse'][$k] = $value[2];
                    }
                }
            }
            $result[] = $data;
        }

        $contents = view('admin::product-inventory.filter', [
            'resultSearchWarehouse' => $resultSearchWarehouse,
            'result' => $result
        ])
            ->render();
        return $contents;
    }

    //Hàm tìm kiếm sản phẩm.
    public function searchProduct(Request $request)
    {
        $keyword = $request->keyword;
        //Danh sách tất cả kho
        $wareHouse = $this->wareHouse->getWareHouseOption();
        $data = $this->functionSearchProductInventory($keyword);
        $resultSearchWarehouse = [];
        foreach ($wareHouse as $ke => $ite) {
            $resultSearchWarehouse[]['name'] = $ite;
        }
        $res = collect($data)->forPage(1, 10);
        $contents = view('admin::product-inventory.filter', [
            'wareHouse' => $resultSearchWarehouse,
            'result' => $res,
            'page' => 1,
            'data' => $data
        ])->render();
        return $contents;
    }

    //Hàm xem lịch sử sản phẩm xuất nhập kho.
    public function historyAction(Request $request)
    {
        $code = $request->code;
        $inventoryInput = $this->inventoryInputDetail->getHistory($code);
        $inventoryOutput = $this->inventoryOutputDetail->getHistory($code);
        $totalInput = 0;
        $totalOutput = 0;
        foreach ($inventoryInput as $input) {
            $totalInput += $input['quantity'];
        }
        foreach ($inventoryOutput as $output) {
            $totalOutput += $output['quantity'];
        }
        $data = $this->calculateHistory($inventoryInput, $inventoryOutput);
        $list = collect($data)->forPage(1, 6);
        $productChild = $this->productChild->getProductChildByCode($code)->product_child_name;
        $contents = view('admin::product-inventory.history', [
            'data' => $data,
            'LIST' => $list,
            'totalInput' => $totalInput,
            'totalOutput' => $totalOutput,
            'page' => 1,
            'code' => $code,
            'productChild'=>$productChild
        ])->render();

        return $contents;
    }

    //Hàm phân trang lịch sử sản phẩm xuất nhập kho.
    public function pagingHistoryAction(Request $request)
    {
        $code = $request->code;
        $inventoryInput = $this->inventoryInputDetail->getHistory($code);
        $inventoryOutput = $this->inventoryOutputDetail->getHistory($code);
        $data = $this->calculateHistory($inventoryInput, $inventoryOutput);
        $page = $request->page;
        $list = collect($data)->forPage($page, 6);
        $contents = view('admin::product-inventory.paging-history', [
            'data' => $data,
            'LIST' => $list,
            'page' => $page
        ])->render();
        return $contents;
    }

    public function calculateHistory($inventoryInput, $inventoryOutput)
    {
        $result = [];
        foreach ($inventoryInput as $item) {
            $result[] = [
                'promissoryNote' => 'input',
                'code' => $item['code'],
                'warehouse' => $item['warehouse'],
                'type' => $item['type'],
                'quantity' => $item['quantity'],
                'user' => $item['user'],
                'createdAt' => $item['createdAt']
            ];
        }
        foreach ($inventoryOutput as $item) {
            $result[] = [
                'promissoryNote' => 'output',
                'code' => $item['code'],
                'warehouse' => $item['warehouse'],
                'type' => $item['type'],
                'quantity' => $item['quantity'],
                'user' => $item['user'],
                'createdAt' => $item['createdAt']
            ];
        }
        return $result;
    }

    //Tồn kho của tất cả sản phẩm ở tất cả các kho.
    public function listAction2()
    {
        //Danh sách tất cả kho
        $wareHouse = $this->wareHouse->getWareHouseOption();
        //Danh sách sản phẩm.
        $product = $this->productChild->getProductChildOption();
        // Danh sách sản phẩm tồn kho.
        $productInventory = $this->productInventory->getProduct();
        //Kết quả thu được.
        $result = [];

        if (count($wareHouse)>0){
            foreach ($product as $a => $b) {
                //Thông tin của sản phẩm.
                $data = [];
                foreach ($wareHouse as $c => $d) {
                    $data['productCode'] = $a;
                    $data['productName'] = $b;
                    $data['productInventory'] = $this->productInventory->getQuantityProductInventoryByCode($a)->quantityInventory;
                    $data['warehouse'][$c] = 0;
                    $data['createdAt'] = $this->productChild->getProductChildByCode($a)->created_at;
                }
                foreach ($data['warehouse'] as $k => $v) {
                    foreach ($productInventory as $key => $value) {
                        if ($a == $value[1] && $k == $value[0]) {
                            $data['warehouse'][$k] = $value[2];
                        }
                    }
                }
                $result[] = $data;
            }
        }

        return $result;
    }

    //Phân trang (tất cả sản phẩm).
    public function pagingAction(Request $request)
    {
        $page = $request->page;
        //Danh sách tất cả kho
        $wareHouse = $this->wareHouse->getWareHouseOption();
        $data = $this->listAction2();
        $result = collect($data)->forPage($page, 10);
        $contents = view('admin::product-inventory.paging', [
            'wareHouse' => $wareHouse,
            'result' => $result,
            'page' => $page,
            'data' => $data,
        ])->render();
        return $contents;
    }

    //Hàm trả về kết quả tìm kiểm sản phẩm tồn kho theo $keyword
    public function functionSearchProductInventory($keyword)
    {
        //Danh sách tất cả kho
        $wareHouse = $this->wareHouse->getWareHouseOption();
        //Danh sách sản phẩm.
        $product = $this->productChild->searchProduct($keyword);
        // Danh sách sản phẩm tồn kho.
        $productInventory = $this->productInventory->getProduct();
        //Kết quả thu được.
        $result = [];
        foreach ($product as $a => $b) {
            //Thông tin của sản phẩm.
            $productChilds = $this->productChild->getProductChildByCode($a);
            if ($productChilds != null) {
                $data = [];
                foreach ($wareHouse as $c => $d) {
                    $data['productCode'] = $a;
                    $data['productName'] = $b;
                    $data['productInventory'] = $this->productInventory->getQuantityProductInventoryByCode($a)->quantityInventory;
                    $data['warehouse'][$c] = 0;
                    $data['createdAt'] = $productChilds->created_at;
                }
                foreach ($data['warehouse'] as $k => $v) {
                    foreach ($productInventory as $key => $value) {
                        if ($a == $value[1] && $k == $value[0]) {
                            $data['warehouse'][$k] = $value[2];
                        }
                    }
                }
                $result[] = $data;
            }
        }
        return $result;
    }

    //Phân trang khi tìm kiếm sản phẩm tồn kho.
    public function pagingSearchProductInventory(Request $request)
    {
        $keyword = $request->keyword;
        $page = $request->page;
        //Danh sách tất cả kho
        $listWarehouse = $this->wareHouse->getWareHouseOption();
        $wareHouse = [];
        foreach ($listWarehouse as $ke => $ite) {
            $wareHouse[]['name'] = $ite;
        }
        $data = $this->functionSearchProductInventory($keyword);
        $result = collect($data)->forPage($page, 10);
        $contents = view('admin::product-inventory.filter-paging', [
            'wareHouse' => $listWarehouse,
            'result' => $result,
            'page' => $page,
            'data' => $data,
        ])->render();
        return $contents;
    }

    public function exportExcelAction(Request $request)
    {
        $keyword = $request->keyword;
        $params = [
            'keyword' => $keyword,
            'page' => 1,
            'perpage' => 1000000000,
        ];
        //Dữ liệu từ db
        $data = $this->productInventory->listProductInventory($params)['data'];
        //Danh sách product child
        $productChild = $data['productChild'];
        $result = [];
        //Danh sách tất cả kho
        $wareHouse = $data['wareHouse'];
        //Danh sách sản phẩm tồn ở kho
        $productInventory = $data['result'];
        $heading = [
            '#',
            'MÃ SẢN PHẨM',
            'TÊN SẢN PHẨM',
            __('NGÀY TẠO'),
        ];
        $isAdmin = Auth::user()->is_admin;
        //Nếu là admin thì cho xem tất cả kho
        if ($isAdmin == 1) {
            $heading[] = 'TẤT CẢ KHO';
        }
        foreach ($wareHouse as $k => $item) {
            $heading[] = mb_strtoupper($item, 'UTF-8');
        }
        foreach ($productChild as $key => $value) {
            $temp = [];
            $temp[] = $key + 1;
            $temp[] = $value['product_code'];
            $temp[] = $value['product_child_name'];
            $temp[] = (new \DateTime($value['created_at']))->format('d/m/Y');
            //Nếu là admin thì cho xem tất cả kho
            if ($isAdmin == 1) {
                $total = $productInventory[$value['product_child_id']]['total'];
                $total = $total != 0 ? $total : '0';
                $temp[] = $total;
            }
            //Tồn kho của từng sp trong kho.
            foreach ($wareHouse as $k => $v) {
                $pI = @$productInventory[$value['product_child_id']][$k] ?? '0';
                $pI = $pI != 0 ? $pI : '0';
                $temp[] = $pI;
            }
            $result[] = $temp;
        }
        ob_end_clean(); ob_start();
        return $this->excel->download(new ProductInventoryExport($heading, $result), 'Tồn kho.csv');
    }

    /**
     * Danh sách sản phẩm tồn kho
     * @param Request $request
     *
     * @return mixed
     */
    public function listProductInventory(Request $request)
    {
        $params = $request->all();
        $result = $this->productInventory->listProductInventory($params);
        return $result['view'];
    }

    /**
     * View tab cấu hình chi nhánh
     *
     * @return array|string
     * @throws \Throwable
     */
    public function inventoryConfig()
    {
        $getDataConfig = $this->productInventory->getDataConfig();
        return view('admin::product-inventory.config', [
            'optionBranch' => $getDataConfig['optionBranch'],
            'getConfig' => $getDataConfig['getConfig']
        ])->render();
    }

    public function saveInventoryConfig(Request $request)
    {
        return $this->productInventory->saveInventoryConfig($request->all());
    }

    /**
     * View cảnh báo tồn kho dưới định mức
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function belowNormAction()
    {
        $data = $this->productInventory->listBelowNorm();

        return view('admin::product-inventory.inventory-below-norm.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filtersBellowNorm()
        ]);
    }

    protected function filtersBellowNorm()
    {

        return [

        ];
    }

    /**
     * Ajax filter + paginate cảnh báo tồn kho dưới định mức
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listBelowNormAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search']);

        $data = $this->productInventory->listBelowNorm($filter);

        return view('admin::product-inventory.inventory-below-norm.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }
}
//

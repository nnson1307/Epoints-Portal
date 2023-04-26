<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/15/2019
 * Time: 10:19 PM
 */

namespace Modules\Admin\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\Product\ProductRepositoryInterface;
use Modules\Admin\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Modules\Admin\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;

class ReportGrowthByBranchController extends Controller
{
    protected $branch;
    protected $orderDetail;
    protected $service;
    protected $serviceCategory;
    protected $product;
    protected $productChild;
    protected $productCategory;
    protected $serviceCard;
    protected $order;

    public function __construct(
        BranchRepositoryInterface $branch,
        OrderDetailRepositoryInterface $orderDetail,
        ServiceRepositoryInterface $service,
        ServiceCategoryRepositoryInterface $serviceCategory,
        ProductRepositoryInterface $product,
        ProductChildRepositoryInterface $productChild,
        ProductCategoryRepositoryInterface $productCategory,
        ServiceCardRepositoryInterface $serviceCard,
        OrderRepositoryInterface $order
    )
    {
        $this->branch = $branch;
        $this->orderDetail = $orderDetail;
        $this->service = $service;
        $this->serviceCategory = $serviceCategory;
        $this->product = $product;
        $this->productChild = $productChild;
        $this->productCategory = $productCategory;
        $this->serviceCard = $serviceCard;
        $this->order = $order;
    }

    public function indexAction()
    {
        $branch = $this->branch->getBranch();
        return view('admin::report.report-growth.report-growth-by-branch', [
            'branch' => $branch
        ]);
    }

    public function chartIndexAction(Request $request)
    {
        $year = date('Y');
        $branchOption = $this->branch->getBranch();
        $arrayBranchValue = [];
        $listBranch = [];
        $time = $request->time;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        //Lấy dữ liệu cho thống kê tất cả chi nhánh.
        foreach ($branchOption as $key => $value) {
            $listBranch[] = $value;
            $dataService = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'service', $key, null);
            $dataProduct = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'product', $key, null);
            $dataServiceCard = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'service_card', $key, null);
            $dataVoucherOrder = $this->orderDetail->getAll($startTime, $endTime, $key);
            $dataVoucherOrderDetail = $this->order->getAllByCondition($startTime, $endTime, $key);


            //Danh sách tổng số lần sử dụng dịch vụ, sản phẩm, thẻ dịch vụ của chi nhánh.
            $calculateChartByBranch = $this->calculateChartByBranch($dataService, $dataProduct, $dataServiceCard, $dataVoucherOrder, $dataVoucherOrderDetail);

            $arrayBranchValue['total'][] = $calculateChartByBranch['total'];
            $arrayBranchValue['quantityService'][] = $calculateChartByBranch['quantityService'];
            $arrayBranchValue['quantityProduct'][] = $calculateChartByBranch['quantityProduct'];
            $arrayBranchValue['quantityServiceCard'][] = $calculateChartByBranch['quantityServiceCard'];
            $arrayBranchValue['quantityVoucher'][] = $calculateChartByBranch['quantityVoucher'];
        }
        //end

        //Lấy thống kê cho biểu đồ khách hàng
        $dataService = $this->orderDetail->getQuantityByObjectTypeTime('service', null, $startTime, $endTime);
        $dataProduct = $this->orderDetail->getQuantityByObjectTypeTime('product', null, $startTime, $endTime);
        $dataServiceCard = $this->orderDetail->getQuantityByObjectTypeTime('service_card', null, $startTime, $endTime);
        $calculateCustomer = $this->calculateCustomer($year, $dataService, $dataProduct, $dataServiceCard);
        //end

        //Lấy dữ liệu cho biểu đồ nhóm dịch vụ.
        $arrayServiceCategory = $this->serviceCategory($dataService);
        $dataserviceCategorys = [];
        $dataserviceCategorys[] = ['Task', 'Hours per Day'];
        foreach ($arrayServiceCategory as $item) {
            $dataserviceCategorys[] = [$item['name'], $item['value']];
        }
        //end
        //Lấy dữ liệu cho nhóm sản phẩm
        $productCategory = $this->productCategory($dataProduct);
        $dataproductCategory = [];
        $dataproductCategory[] = ['Task', 'Hours per Day'];
        foreach ($productCategory as $item) {
            $dataproductCategory[] = [$item['name'], $item['value']];
        }


        //Biểu đồ thống kê số lượng theo chi nhánh
        $dataQuantity = [];
        $dataQuantity[] = ['', __('DỊCH VỤ'), __('SẢN PHẨM'), __('THẺ DỊCH VỤ'), __('VOUCHER')];
        foreach ($listBranch as $key => $value) {
            $dataQuantity[] = [
                $value,
                $arrayBranchValue['quantityService'][$key][0],
                $arrayBranchValue['quantityProduct'][$key][0],
                $arrayBranchValue['quantityServiceCard'][$key][0],
                $arrayBranchValue['quantityVoucher'][$key][0],

            ];
        }

        //Biểu đồ nhóm thẻ dịch vụ.
        $serviceCardGroup = $this->serviceCardGroup($dataServiceCard);
        $dataServiceCardGroup = [];
        $dataServiceCardGroup[] = ['Task', 'Hours per Day'];
        foreach ($serviceCardGroup as $item) {
            $dataServiceCardGroup[] = [$item['name'], $item['value']];
        }

        //Biểu đồ tỉ lệ sử dụng voucher.
        $dataVoucherOrderDetail = $this->orderDetail->getAll($startTime, $endTime, null);
        $dataVoucherOrder = $this->order->getAllByCondition($startTime, $endTime, null);
        $ratioVoucher = $this->calculateRatioVoucher($dataVoucherOrder, $dataVoucherOrderDetail);

        //Biểu đồ tỉ lệ sử dụng thẻ dịch vụ.
        $ratioServiceCard = $this->calculateRatioServiceCard($dataVoucherOrderDetail);

        return response()->json([
            'listBranch' => $listBranch,
            'total' => $arrayBranchValue['total'],
            'service' => $arrayBranchValue['quantityService'],
            'product' => $arrayBranchValue['quantityProduct'],
            'serviceCard' => $arrayBranchValue['quantityServiceCard'],
            'voucher' => $arrayBranchValue['quantityVoucher'],
            'quantityAllCustomer' => $calculateCustomer['quantityAllCustomer'],
            'quantityOddCustomer' => $calculateCustomer['quantityOddCustomer'],
            'arrayServiceCategory' => $arrayServiceCategory,
            'arrayProductCategory' => $productCategory,
            'dataQuantity' => $dataQuantity,
            'dataserviceCategorys' => $dataserviceCategorys,
            'dataproductCategory' => $dataproductCategory,
            'dataServiceCardGroup' => $dataServiceCardGroup,
            'totalOrder' => $ratioVoucher['total'],
            'totalUseVoucher' => $ratioVoucher['voucher'],
            'totalUseServiceCard' => $ratioServiceCard['totalUseServiceCard'],
            'totalOrderDetail' => $ratioServiceCard['totalOrderDetail']
        ]);
    }

    public function productCategory($dataProduct)
    {
        $arrayProduct = [];
        foreach ($this->productChild->getProductChildOptionIdName() as $key => $value) {
            $arrayProduct[$key] = 0;
        }

        foreach ($arrayProduct as $key => $value) {
            foreach ($dataProduct as $key2 => $value2) {
                if ($value2['object_id'] == $key) {
                    $arrayProduct[$key] += $value2['quantity'];
                }
            }
        }
        //Chỉ lấy các sản phẩm được sử dụng.
        $arrayProductHaveValue = [];
        foreach ($arrayProduct as $key => $value) {
            if ($value != 0) {
                $arrayProductHaveValue[$key] = $value;
            }
        }

        $arrayProductCategory = [];
        foreach ($arrayProductHaveValue as $key => $value) {
            $productChildDetail = $this->productChild->getItem($key);
            if ($productChildDetail != null && $this->product->getItem($productChildDetail->product_id) != null) {
                if ($this->product->getItem($productChildDetail->product_id) != null) {
                    $productCategoryId = $this->product->getItem($productChildDetail->product_id)['productCategoryId'];
                    $arrayProductCategory[] = $productCategoryId;
                }
            }
        }
        //Gộp các nhóm sản phẩm giống nhau.
        $arrayProductCategoryValue = [];
        $arrayProductCategoryUnique = array_unique($arrayProductCategory);
        foreach ($arrayProductCategoryUnique as $key => $value) {
            if ($value != null) {
                $arrayProductCategoryValue[$value] = 0;
            }
        }

        foreach ($arrayProductCategoryValue as $key => $value) {
            foreach ($arrayProductHaveValue as $key1 => $value1) {
                if ($this->productChild->getItem($key1) != null) {
                    $productId = $this->productChild->getItem($key1)->product_id;
                    if ($this->product->getItem($productId) != null) {
                        $productCategoryId = $this->product->getItem($productId)['productCategoryId'];
                        if ($key == $productCategoryId) {
                            $arrayProductCategoryValue[$key] += $value1;
                        }
                    }
                }
            }
        }
        $arrayCategory = [];

        foreach ($arrayProductCategoryValue as $key => $value) {
            if ($value != 0 && $this->productCategory->getItem($key) != null) {
                $arrayCategory[] = ['name' => $this->productCategory->getItem($key)->category_name, 'value' => $value];
            }
        }
        return $arrayCategory;
    }

    public function filterAction(Request $request)
    {
        $year = date('Y');
        $branch = $request->branch;
        $time = $request->time;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        if ($time != null && $branch == null) {
            $branchOption = $this->branch->getBranch();
            $arrayBranchValue = [];
            $listBranch = [];
            foreach ($branchOption as $key => $value) {
                $listBranch[] = $value;
                $dataService = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'service', $key, null);
                $dataProduct = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'product', $key, null);
                $dataServiceCard = $this->orderDetail->getValueByDateObjectTypeBranch($startTime, $endTime, 'service_card', $key, null);
                $dataVoucherOrder = $this->orderDetail->getAll($startTime, $endTime, $key);
                $dataVoucherOrderDetail = $this->order->getAllByCondition($startTime, $endTime, $key);

                //Danh sách tổng số lần sử dụng dịch vụ, sản phẩm, thẻ dịch vụ của chi nhánh.
                $calculateChartByBranch = $this->calculateChartByBranch($dataService, $dataProduct, $dataServiceCard, $dataVoucherOrder, $dataVoucherOrderDetail);
                $arrayBranchValue['total'][] = $calculateChartByBranch['total'];
                $arrayBranchValue['quantityService'][] = $calculateChartByBranch['quantityService'];
                $arrayBranchValue['quantityProduct'][] = $calculateChartByBranch['quantityProduct'];
                $arrayBranchValue['quantityServiceCard'][] = $calculateChartByBranch['quantityServiceCard'];
                $arrayBranchValue['quantityVoucher'][] = $calculateChartByBranch['quantityVoucher'];
            }
            //end

            //Lấy thống kê cho biểu đồ khách hàng
            $dataService = $this->orderDetail->getQuantityByObjectTypeTime('service', null, $startTime, $endTime);
            $dataProduct = $this->orderDetail->getQuantityByObjectTypeTime('product', null, $startTime, $endTime);
            $dataServiceCard = $this->orderDetail->getQuantityByObjectTypeTime('service_card', null, $startTime, $endTime);
            $calculateCustomer = $this->calculateCustomer($year, $dataService, $dataProduct, $dataServiceCard);
            //end
            //Lấy dữ liệu cho biểu đồ nhóm dịch vụ.
            $arrayServiceCategory = $this->serviceCategory($dataService);
            $dataserviceCategorys = [];
            $dataserviceCategorys[] = ['Task', 'Hours per Day'];
            foreach ($arrayServiceCategory as $item) {
                $dataserviceCategorys[] = [$item['name'], $item['value']];
            }
            //end
            //Lấy dữ liệu cho nhóm sản phẩm
            $productCategory = $this->productCategory($dataProduct);
            $dataproductCategory = [];
            $dataproductCategory[] = ['Task', 'Hours per Day'];
            foreach ($productCategory as $item) {
                $dataproductCategory[] = [$item['name'], $item['value']];
            }

            //Biểu đồ thống kê số lượng theo chi nhánh
            $dataQuantity = [];
            $dataQuantity[] = [__('Chi nhánh'), __('DỊCH VỤ'), __('SẢN PHẨM'), __('THẺ DỊCH VỤ'), __('VOUCHER')];
            foreach ($listBranch as $key => $value) {
                $dataQuantity[] = [
                    $value,
                    $arrayBranchValue['quantityService'][$key][0],
                    $arrayBranchValue['quantityProduct'][$key][0],
                    $arrayBranchValue['quantityServiceCard'][$key][0],
                    $arrayBranchValue['quantityVoucher'][$key][0],

                ];
            }

            //Biểu đồ nhóm thẻ dịch vụ.
            $serviceCardGroup = $this->serviceCardGroup($dataServiceCard);
            $dataServiceCardGroup = [];
            $dataServiceCardGroup[] = ['Task', 'Hours per Day'];

            foreach ($serviceCardGroup as $item) {
                $dataServiceCardGroup[] = [$item['name'], $item['value']];
            }
            //Biểu đồ tỉ lệ sử dụng voucher.
            $dataVoucherOrderDetail = $this->orderDetail->getAll($startTime, $endTime, null);
            $dataVoucherOrder = $this->order->getAllByCondition($startTime, $endTime, null);
            $ratioVoucher = $this->calculateRatioVoucher($dataVoucherOrder, $dataVoucherOrderDetail);

            //Biểu đồ tỉ lệ sử dụng thẻ dịch vụ.
            $ratioServiceCard = $this->calculateRatioServiceCard($dataVoucherOrderDetail);

            return response()->json([
                'listBranch' => $listBranch,
                'total' => $arrayBranchValue['total'],
                'service' => $arrayBranchValue['quantityService'],
                'product' => $arrayBranchValue['quantityProduct'],
                'serviceCard' => $arrayBranchValue['quantityServiceCard'],
                'quantityAllCustomer' => $calculateCustomer['quantityAllCustomer'],
                'quantityOddCustomer' => $calculateCustomer['quantityOddCustomer'],
                'arrayServiceCategory' => $arrayServiceCategory,
                'arrayProductCategory' => $productCategory,
                'dataQuantity' => $dataQuantity,
                'dataserviceCategorys' => $dataserviceCategorys,
                'dataproductCategory' => $dataproductCategory,
                'dataServiceCardGroup' => $dataServiceCardGroup,
                'totalOrder' => $ratioVoucher['total'],
                'totalUseVoucher' => $ratioVoucher['voucher'],
                'totalUseServiceCard' => $ratioServiceCard['totalUseServiceCard'],
                'totalOrderDetail' => $ratioServiceCard['totalOrderDetail']
            ]);
        } else if ($time != null && $branch != null) {
            //Từ ngày đến ngày và chi nhánh.
            $total = [];
            $service = [];
            $product = [];
            $serviceCard = [];
            $voucher = [];
            $day = [];
            $arrayDayValue = [];
            //Số ngày.
            $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;

            $dataService = $this->orderDetail->getValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, 'service');

            $dataProduct = $this->orderDetail->getValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, 'product');
            $dataServiceCard = $this->orderDetail->getValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, 'service_card');

            $dataVoucherOrder = $this->orderDetail->getAll($startTime, $endTime, $branch);
            $dataVoucherOrderDetail = $this->order->getAllByCondition($startTime, $endTime, $branch);

            $arrayTempVoucher = [];

            foreach ($dataVoucherOrder as $key22 => $item) {
                if ($item['voucher_code'] != null) {
                    $arrayTempVoucher[] = [
                        'created_at' => $item['created_at']->format('d/m/Y'),
                        'voucher_code' => $item['voucher_code'],
                    ];
                }
            }

            foreach ($dataVoucherOrderDetail as $item) {
                if ($item['voucher_code'] != null) {
                    $arrayTempVoucher[] = [
                        'created_at' => $item['created_at']->format('d/m/Y'),
                        'voucher_code' => $item['voucher_code'],
                    ];
                }
            }

            for ($i = 0; $i < $datediff; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $day[] = substr($tomorrow, 0, -5);
                $arrayDayValue[$tomorrow] = [];
            }
            foreach ($arrayDayValue as $key => $value) {

                //Tổng
                $totalAll = 0;
                $totalService = 0;
                $totalProduct = 0;
                $totalServiceCard = 0;
                $totalVoucher = 0;

                foreach ($dataService as $key2 => $value2) {
                    $days = date('d/m/Y', strtotime($value2['created_at']));
                    if ($key == $days) {
                        $totalAll += $value2['quantity'];
                        $totalService += $value2['quantity'];
                    }
                }
                foreach ($dataProduct as $key2 => $value2) {
                    $days = date('d/m/Y', strtotime($value2['created_at']));
                    if ($key == $days) {
                        $totalAll += $value2['quantity'];
                        $totalProduct += $value2['quantity'];
                    }
                }
                foreach ($dataServiceCard as $key2 => $value2) {
                    $days = date('d/m/Y', strtotime($value2['created_at']));
                    if ($key == $days) {
                        $totalAll += $value2['quantity'];
                        $totalServiceCard += $value2['quantity'];
                    }
                }

                foreach ($arrayTempVoucher as $key2 => $value2) {
                    if ($key == $value2['created_at']) {
                        $totalAll += 1;
                        $totalVoucher += 1;
                    }
                }

                $total[] = $totalAll;
                $service[] = $totalService;
                $product [] = $totalProduct;
                $serviceCard  [] = $totalServiceCard;
                $voucher  [] = $totalVoucher;
            }
            //end
            //Lấy thống kê cho biểu đồ khách hàng
            $dataService = $this->orderDetail->getQuantityByObjectTypeTime('service', null, $startTime, $endTime, $branch);
            $dataProduct = $this->orderDetail->getQuantityByObjectTypeTime('product', null, $startTime, $endTime, $branch);
            $dataServiceCard = $this->orderDetail->getQuantityByObjectTypeTime('service_card', null, $startTime, $endTime, $branch);
            $calculateCustomer = $this->calculateCustomer($year, $dataService, $dataProduct, $dataServiceCard);
            //end
            //Lấy dữ liệu cho biểu đồ nhóm dịch vụ.
            $arrayServiceCategory = $this->serviceCategory($dataService);
            $dataserviceCategorys = [];
            $dataserviceCategorys[] = ['Task', 'Hours per Day'];
            foreach ($arrayServiceCategory as $item) {
                $dataserviceCategorys[] = [$item['name'], $item['value']];
            }
            //end
            //Lấy dữ liệu cho nhóm sản phẩm
            $productCategory = $this->productCategory($dataProduct);
            $dataproductCategory = [];
            $dataproductCategory[] = ['Task', 'Hours per Day'];
            foreach ($productCategory as $item) {
                $dataproductCategory[] = [$item['name'], $item['value']];
            }
            //Biểu đồ thống kê số lượng theo chi nhánh
            $dataQuantity = [];
            $dataQuantity[] = ['',__('DỊCH VỤ'), __('SẢN PHẨM'), __('THẺ DỊCH VỤ'), __('VOUCHER')];
            foreach ($day as $key => $value) {
                $dataQuantity[] = [
                    $value,
                    $service[$key],
                    $product[$key],
                    $serviceCard[$key],
                    $voucher[$key]
                ];
            }

            //Biểu đồ nhóm thẻ dịch vụ.
            $serviceCardGroup = $this->serviceCardGroup($dataServiceCard);
            $dataServiceCardGroup = [];
            $dataServiceCardGroup[] = ['Task', 'Hours per Day'];
            foreach ($serviceCardGroup as $item) {
                $dataServiceCardGroup[] = [$item['name'], $item['value']];
            }

            //Biểu đồ tỉ lệ sử dụng voucher.
            $dataVoucherOrderDetail = $this->orderDetail->getAll($startTime, $endTime, $branch);
            $dataVoucherOrder = $this->order->getAllByCondition($startTime, $endTime, $branch);
            $ratioVoucher = $this->calculateRatioVoucher($dataVoucherOrder, $dataVoucherOrderDetail);

            //Biểu đồ tỉ lệ sử dụng thẻ dịch vụ.
            $ratioServiceCard = $this->calculateRatioServiceCard($dataVoucherOrderDetail);
            return response()->json([
                'day' => $day,
                'total' => $total,
                'service' => $service,
                'product' => $product,
                'serviceCard' => $serviceCard,
                'quantityAllCustomer' => $calculateCustomer['quantityAllCustomer'],
                'quantityOddCustomer' => $calculateCustomer['quantityOddCustomer'],
                'arrayServiceCategory' => $arrayServiceCategory,
                'arrayProductCategory' => $productCategory,
                'dataQuantity' => $dataQuantity,
                'dataserviceCategorys' => $dataserviceCategorys,
                'dataproductCategory' => $dataproductCategory,
                'dataServiceCardGroup' => $dataServiceCardGroup,
                'totalOrder' => $ratioVoucher['total'],
                'totalUseVoucher' => $ratioVoucher['voucher'],
                'totalUseServiceCard' => $ratioServiceCard['totalUseServiceCard'],
                'totalOrderDetail' => $ratioServiceCard['totalOrderDetail']
            ]);
        }
    }

    public function serviceCategory($dataService)
    {
        $arrayService = [];
        foreach ($this->service->getServiceOption() as $key => $value) {
            $arrayService[$key] = 0;
        }
        foreach ($arrayService as $key => $value) {
            foreach ($dataService as $key2 => $value2) {
                if ($value2['object_id'] == $key) {
                    $arrayService[$key] += $value2['quantity'];
                }
            }
        }
        //Chỉ lấy các dịch vụ được sử dụng.
        $arrayServiceHaveValue = [];
        foreach ($arrayService as $key => $value) {
            if ($value != 0) {
                $arrayServiceHaveValue[$key] = $value;
            }
        }
        //Lấy dữ liệu cho nhóm dịch vụ
        $arrayServiceCategory = [];
        foreach ($arrayServiceHaveValue as $key => $value) {
            $serviceDetail = $this->service->getItem($key);
            if ($serviceDetail != null) {
                $arrayServiceCategory[] = $serviceDetail->service_category_id;
            } else {
                $arrayServiceCategory[] = '';
            }
        }
        //Gộp các nhóm dịch vụ giống nhau
        $arrayServiceCategoryValue = [];
        $arrayServiceCategoryUnique = array_unique($arrayServiceCategory);

        foreach ($arrayServiceCategoryUnique as $key => $value) {
            $arrayServiceCategoryValue[$value] = 0;
        }
        foreach ($arrayServiceCategoryUnique as $key => $value) {
            foreach ($arrayServiceHaveValue as $key1 => $value2) {
                $categoryId = $this->service->getItem($key1);
                if ($categoryId != null) {
                    if ($value == $categoryId->service_category_id) {
                        $arrayServiceCategoryValue[$value] += $value2;
                    }
                }
            }
        }
        $arrayServiceCategory = [];
        foreach ($arrayServiceCategoryValue as $key => $value) {
            $categoryDetail = $this->serviceCategory->getItem($key);
            if ($categoryDetail != null) {
                $arrayServiceCategory[] = ['name' => $categoryDetail->name, 'value' => $value];
            }
        }
        return $arrayServiceCategory;
    }

    public function calculateCustomer($year, $dataService, $dataProduct, $dataServiceCard)
    {
        //Lấy thống kê cho biểu đồ khách hàng
        $quantityOddCustomer = 0;
        $quantityAllCustomer = 0;

        //Tính số lượng của dịch vụ của khách vãng lai và tất cả khách hàng.
        foreach ($dataService as $key => $value) {
            $quantityAllCustomer += $value['quantity'];
            if ($value['customer_id'] == 1) {
                $quantityOddCustomer += $value['quantity'];
            }
        }

        //Tính số lượng của sản phẩm của khách vãng lai và tất cả khách hàng.
        foreach ($dataProduct as $key => $value) {
            $quantityAllCustomer += $value['quantity'];
            if ($value['customer_id'] == 1) {
                $quantityOddCustomer += $value['quantity'];
            }
        }
        //Tính số lượng của thẻ dịch vụ của khách vãng lai và tất cả khách hàng.
        foreach ($dataServiceCard as $key => $value) {
            $quantityAllCustomer += $value['quantity'];
            if ($value['customer_id'] == 1) {
                $quantityOddCustomer += $value['quantity'];
            }
        }
        $result = [];
        $result['quantityAllCustomer'] = $quantityAllCustomer;
        $result['quantityOddCustomer'] = $quantityOddCustomer;
        return $result;
    }

    private function calculateChartByBranch($dataService, $dataProduct, $dataServiceCard, $dataVoucherOrder, $dataVoucherOrderDetail)
    {
        //Danh sách tổng số lần sử dụng dịch vụ, sản phẩm, thẻ dịch vụ của chi nhánh.
        $arrayBranchValue = [];
        $total = 0;
        foreach ($dataService as $key1 => $value1) {
            $total += $value1['quantity'];
        }
        foreach ($dataProduct as $key2 => $value2) {
            $total += $value2['quantity'];
        }
        foreach ($dataServiceCard as $key3 => $value3) {
            $total += $value3['quantity'];
        }
        $arrayBranchValue['total'][] = $total;
        //Danh sách tổng số lần sử dụng dịch vụ của chi nhánh.
        $quantityService = 0;
        foreach ($dataService as $key4 => $value4) {
            $quantityService += $value4['quantity'];
        }
        $arrayBranchValue['quantityService'][] = $quantityService;
        //Danh sách tổng số lần sử dụng sản phẩm của chi nhánh.
        $quantityProduct = 0;
        foreach ($dataProduct as $key5 => $value5) {
            $quantityProduct += $value5['quantity'];
        }
        $arrayBranchValue['quantityProduct'][] = $quantityProduct;
        //Danh sách tổng số lần sử dụng thẻ dịch vụ của chi nhánh.
        $quantityServiceCard = 0;
        foreach ($dataServiceCard as $key6 => $value6) {
            $quantityServiceCard += $value6['quantity'];
        }
        $arrayBranchValue['quantityServiceCard'][] = $quantityServiceCard;

        //Danh sách số lần sử dụng voucher của chi nhánh
        $quantityVoucher = 0;
        foreach ($dataVoucherOrder as $key => $value) {
            if ($value['voucher_code'] != null)
                $quantityVoucher += 1;
        }
        foreach ($dataVoucherOrderDetail as $key => $value) {
            if ($value['voucher_code'] != null)
                $quantityVoucher += 1;
        }
        $arrayBranchValue['quantityVoucher'][] = $quantityVoucher;
        return $arrayBranchValue;
    }

    public function serviceCardGroup($dataServiceCard)
    {
        $result = [];
        $arrayTemp = [];
        foreach ($dataServiceCard as $item) {
            $arrayTemp[$item['object_id']] = 0;
        }
        foreach ($arrayTemp as $key => $value) {
            foreach ($dataServiceCard as $item) {
                if ($item['object_id'] == $key) {
                    $arrayTemp[$item['object_id']] += $item['quantity'];
                }
            }
        }

        foreach ($arrayTemp as $key => $value) {
            $group = $this->serviceCard->getServiceGroup($key);
            $result[] = ['name' => $group->group_name, 'value' => $value];
        }
        return $result;
    }

    public function calculateRatioVoucher($dataOrder, $dataOrderDetail)
    {
        $result = [];
        $total = 0;
        $voucher = 0;
        foreach ($dataOrderDetail as $item) {
            $total += ($item['price'] * $item['quantity']);
            $voucher += $item['discount'];
        }
        foreach ($dataOrder as $item) {
            $voucher += $item['discount'];
        }

        $result['total'] = $total;
        $result['voucher'] = $voucher;
        return $result;
    }

    public function calculateRatioServiceCard($data)
    {
        $result = [];

        $totalOrderDetail = 0;
        $totalUseServiceCard = 0;

        foreach ($data as $item) {
            if ($item['object_type'] == 'member_card') {
                $totalUseServiceCard += $item['quantity'];
            }
            $totalOrderDetail += $item['quantity'];
        }
        $result['totalOrderDetail'] = $totalOrderDetail;
        $result['totalUseServiceCard'] = $totalUseServiceCard;

        return $result;
    }
}
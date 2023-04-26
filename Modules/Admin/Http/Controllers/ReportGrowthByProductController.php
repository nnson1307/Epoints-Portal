<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/14/2019
 * Time: 1:19 PM
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

class ReportGrowthByProductController extends Controller
{
    protected $order;
    protected $orderDetail;
    protected $branch;
    protected $productCategory;
    protected $productChild;
    protected $product;

    public function __construct(
        OrderRepositoryInterface $order,
        OrderDetailRepositoryInterface $orderDetail,
        BranchRepositoryInterface $branch,
        ProductCategoryRepositoryInterface $productCategory,
        ProductChildRepositoryInterface $productChild,
        ProductRepositoryInterface $product

    )
    {
        $this->order = $order;
        $this->orderDetail = $orderDetail;
        $this->branch = $branch;
        $this->productCategory = $productCategory;
        $this->productChild = $productChild;
        $this->product = $product;
    }

    public function indexAction()
    {
        $productChild = $this->productChild->getProductChildOptionIdName();
        return view('admin::report.report-growth.report-growth-by-product', [
            'productChild' => $productChild
        ]);
    }

    public function chartIndexAction(Request $request)
    {
        $year = date('Y');
        $time = $request->time;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        //Sản phẩm sử dụng tại năm hiện tại.
        $productOption = $this->productChild->getProductChildOptionIdName();
        $arrayProduct = [];
        $arrayBranch = [];
        foreach ($productOption as $key => $value) {
            $arrayProduct[$key] = 0;
        }
        $dataSelect = $this->orderDetail->getQuantityByObjectTypeTime('product', null, $startTime, $endTime);
        foreach ($arrayProduct as $key => $value) {
            foreach ($dataSelect as $key1 => $value1) {
                if ($value1['object_id'] == $key) {
                    $arrayProduct[$key] += $value1['quantity'];
                }
            }
        }
        //Sắp xếp mảng theo giá trị giảm dần.
        arsort($arrayProduct);
        $arrayProductName = [];
        $arrayProductQuantity = [];
        foreach ($arrayProduct as $key => $value) {
            if ($this->productChild->getItem($key) != null) {
                $arrayProductName[] = $this->productChild->getItem($key)->product_child_name;
                $arrayProductQuantity[] = $value;
            }
        }
        //Lấy dữ liệu cho biểu đồ khách hàng.
        $totalQuantity = array_sum($arrayProductQuantity);
        $totalQuantityOddCustomer = 0;

        foreach ($dataSelect as $key => $value) {
            if ($value['customer_id'] == 1) {
                $totalQuantityOddCustomer += $value['quantity'];
            }
            if (!in_array($value['branch_id'], $arrayBranch)) {
                $arrayBranch[$value['branch_id']] = 0;
            }
        }

        //Lấy dữ liệu cho nhóm sản phẩm
        $arrayProductCategory = [];
        foreach ($arrayProduct as $key => $value) {
            $productChildDetail = $this->productChild->getItem($key);
            if ($productChildDetail != null && $this->product->getItem($productChildDetail->product_id) != null) {
                $productCategoryId = $this->product->getItem($productChildDetail->product_id)['productCategoryId'];
                $arrayProductCategory[] = $productCategoryId;
            }
        }
        //Gộp các nhóm sản phẩm giống nhau.
        $arrayProductCategoryValue = [];
        $arrayProductCategory = array_unique($arrayProductCategory);
        foreach ($arrayProductCategory as $key => $value) {
            if ($value != null) {
                $arrayProductCategoryValue[$value] = 0;
            }
        }
        foreach ($arrayProductCategoryValue as $key => $value) {
            foreach ($arrayProduct as $key1 => $value1) {
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

        //Lấy dữ liệu cho biểu đồ chi nhánh.
        foreach ($arrayBranch as $key => $value) {
            foreach ($dataSelect as $key2 => $value2) {
                if ($key == $value2['branch_id']) {
                    $arrayBranch[$key] += $value2['quantity'];
                }
            }
        }
        $arrayBranchChart = [];
        foreach ($arrayBranch as $key => $value) {
            if ($this->branch->getItem($key) != null) {
                $arrayBranchChart[] = ['name' => $this->branch->getItem($key)->branch_name, 'value' => $value];
            }
        }

        //Biểu đồ miền
        $dataAreaChartProduct = [];
        $dataAreaChartProduct[] = ['', ''];
        $count = 0;
        foreach ($arrayProductName as $key => $value) {
            $dataAreaChartProduct[] = [$value, $arrayProductQuantity[$key]];
            $count++;
            if ($count == 7) {
                break;
            }
        }

        if (count($arrayProductQuantity) > 7) {
            $temp = 0;
            foreach ($arrayProductQuantity as $key => $value) {
                if ($key > 7) {
                    $temp += $value;
                }
            }
            $dataAreaChartProduct[] = [__('Khác'), $temp];
        }
        //Biểu đồ nhóm sản phẩm
        $dataProductGroup = [];
        $dataProductGroup[] = ['', ''];
        foreach ($arrayCategory as $item) {
            $dataProductGroup[] = [$item['name'], $item['value']];
        }

        //Biểu đồ chi nhánh
        $dataBranchChart = [];
        $dataBranchChart[] = ['', ''];
        foreach ($arrayBranchChart as $item) {
            $dataBranchChart[] = [$item['name'], $item['value']];
        }
        return response()->json([
            'listAllProductQuantity' => $arrayProductQuantity,
            'totalQuantity' => $totalQuantity,
            'quantityOddCustomer' => $totalQuantityOddCustomer,
            'dataAreaChartProduct' => $dataAreaChartProduct,
            'dataProductGroup' => $dataProductGroup,
            'dataBranchChart' => $dataBranchChart
        ]);
    }

    public function filterAction(Request $request)
    {
        $year = date('Y');
        $product = $request->product;
        $time = $request->time;
        $startTime = $endTime = null;

        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        if ($time != null && $product == null) {
            //Dịch vụ sử dụng từ ngày đến ngày.
            $productOption = $this->productChild->getProductChildOptionIdName();
            $arrayProduct = [];
            foreach ($productOption as $key => $value) {
                $arrayProduct[$key] = 0;
            }

            $dataSelect = $this->orderDetail->getQuantityByObjectTypeTime('product', null, $startTime, $endTime);
            foreach ($arrayProduct as $key => $val) {
                foreach ($dataSelect as $key1 => $val1) {
                    if ($val1['object_id'] == $key) {
                        $arrayProduct[$key] += $val1['quantity'];
                    }
                }
            }

            //Sắp xếp mảng theo giá trị giảm dần.
            arsort($arrayProduct);
            $arrayProductName = [];
            $arrayProductQuantity = [];

            foreach ($arrayProduct as $key => $value) {
                if ($this->productChild->getItem($key) != null) {
                    $arrayProductName[] = $this->productChild->getItem($key)->product_child_name;
                    $arrayProductQuantity[] = $value;
                }
            }

            //Lấy dữ liệu cho biểu đồ khách hàng.
            $totalQuantity = array_sum($arrayProductQuantity);
            $quantityOddCustomer = 0;
            foreach ($dataSelect as $key => $value) {
                if ($value['customer_id'] == 1) {
                    $quantityOddCustomer += $value['quantity'];
                }
            }
            //Lấy dữ liệu cho nhóm sản phẩm
            $arrayProductCategory = [];
            foreach ($arrayProduct as $key => $value) {
                if ($this->productChild->getItem($key) != null) {
                    $productId = $this->productChild->getItem($key)->product_id;
                    if ($this->product->getItem($productId) != null) {
                        $productCategoryId = $this->product->getItem($productId)['productCategoryId'];
                        $arrayProductCategory[] = $productCategoryId;
                    }
                }
            }

            //Gộp các nhóm sản phẩm giống nhau.
            $arrayProductCategoryValue = [];
            $arrayProductCategory = array_unique($arrayProductCategory);
            foreach ($arrayProductCategory as $key => $value) {
                if ($value != null) {
                    $arrayProductCategoryValue[$value] = 0;
                }
            }
            foreach ($arrayProductCategoryValue as $key => $value) {
                foreach ($arrayProduct as $key1 => $value1) {
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
                if ($value != 0) {
                    $productCategoryDetail = $this->productCategory->getItem($key);
                    if ($productCategoryDetail != null) {
                        $arrayCategory[] = ['name' => $productCategoryDetail->category_name, 'value' => $value];
                    }
                }
            }

            //Lấy dữ liệu cho biểu đồ chi nhánh.
            $arrayBranch = [];
            foreach ($dataSelect as $key => $value) {
                if (!in_array($value['branch_id'], $arrayBranch)) {
                    $arrayBranch[$value['branch_id']] = 0;
                }
            }

            foreach ($arrayBranch as $key => $value) {
                foreach ($dataSelect as $key2 => $value2) {
                    if ($key == $value2['branch_id']) {
                        $arrayBranch[$key] += $value2['quantity'];
                    }
                }
            }
            $arrayBranchChar = [];
            foreach ($arrayBranch as $key => $value) {
                $arrayBranchChar[] = ['name' => $this->branch->getItem($key)->branch_name, 'value' => $value];
            }

            //Biểu đồ miền
            $dataAreaChartProduct = [];
            $dataAreaChartProduct[] = ['', ''];
            $count = 0;
            foreach ($arrayProductName as $key => $value) {
                $dataAreaChartProduct[] = [$value, $arrayProductQuantity[$key]];
                $count++;
                if ($count == 7) {
                    break;
                }
            }

            if (count($arrayProductQuantity) > 7) {
                $temp = 0;
                foreach ($arrayProductQuantity as $key => $value) {
                    if ($key > 7) {
                        $temp += $value;
                    }
                }
                $dataAreaChartProduct[] = [__('Khác'), $temp];
            }

            //Biểu đồ nhóm sản phẩm
            $dataProductGroup = [];
            $dataProductGroup[] = ['', ''];
            foreach ($arrayCategory as $item) {
                $dataProductGroup[] = [$item['name'], $item['value']];
            }

            //Biểu đồ chi nhánh
            $dataBranchChart = [];
            $dataBranchChart[] = ['', ''];
            foreach ($arrayBranchChar as $item) {
                $dataBranchChart[] = [$item['name'], $item['value']];
            }

            return response()->json([
                'listAllProductName' => $arrayProductName,
                'listAllProductQuantity' => $arrayProductQuantity,
                'totalQuantity' => $totalQuantity,
                'quantityOddCustomer' => $quantityOddCustomer,
                'arrayCategory' => $arrayCategory,
                'arrayBranchChar' => $arrayBranchChar,
                'dataAreaChartProduct' => $dataAreaChartProduct,
                'dataProductGroup' => $dataProductGroup,
                'dataBranchChart' => $dataBranchChart
            ]);
        } else if ($time != null && $product != null) {
            //Từ ngày đến ngày và sản phẩm.
            $dataSelect = $this->orderDetail->getQuantityByObjectTypeTime('product', $product, $startTime, $endTime);
            //Số ngày.
            $datediff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;
            $day = [];
            $valueDay = [];
            $arrayDayValue = [];

            for ($i = 0; $i < $datediff; $i++) {
                $tomorrow = date('d/m/Y', strtotime($startTime . "+" . $i . " days"));
                $arrayDayValue[$tomorrow] = 0;
            }

            foreach ($arrayDayValue as $key => $value) {
                foreach ($dataSelect as $key2 => $value2) {
                    $days = date('d/m/Y', strtotime($value2['created_at']));
                    if ($key == $days) {
                        $arrayDayValue[$key] += $value2['quantity'];
                    }
                }
            }
            foreach ($arrayDayValue as $key => $value) {
                $day[] = substr($key, 0, -5);
                $valueDay[] = $value;
            }

            //Lấy dữ liệu cho biểu đồ khách hàng.
            $quantityOddCustomer = 0;
            $quantityAllCustomer = 0;
            foreach ($dataSelect as $key => $value) {
                $quantityAllCustomer += $value['quantity'];
                if ($value['customer_id'] == 1) {
                    $quantityOddCustomer += $value['quantity'];
                }
            }
            //Lấy dữ liệu cho nhóm sản phẩm.
            $arrayProductCategory = [];
            $productChildDetail = $this->productChild->getItem($product);
            if ($productChildDetail != null) {
                $productDetail = $this->product->getItem($productChildDetail->product_id);
                $arrayProductCategory[] = $productDetail->categoryName;
            }


            //Lấy dữ liệu cho biểu đồ chi nhánh.
            $arrayBranch = [];
            foreach ($dataSelect as $key => $value) {
                if (!in_array($value['branch_id'], $arrayBranch)) {
                    $arrayBranch[$value['branch_id']] = 0;
                }
            }
            foreach ($arrayBranch as $key => $value) {
                foreach ($dataSelect as $key2 => $value2) {
                    if ($key == $value2['branch_id']) {
                        $arrayBranch[$key] += $value2['quantity'];
                    }
                }
            }
            $arrayBranchChart = [];
            foreach ($arrayBranch as $key => $value) {
                $branchDetail = $this->branch->getItem($key);
                if ($branchDetail != null) {
                    $arrayBranchChart[] = ['name' => $branchDetail->branch_name, 'value' => $value];
                }
            }

            //Biểu đồ miền
            $dataAreaChartProduct = [];
            $dataAreaChartProduct[] = ['', ''];

            foreach ($day as $key => $value) {
                $dataAreaChartProduct[] = [$value, $valueDay[$key]];
            }

            //Biểu đồ nhóm sản phẩm
            $dataProductGroup = [['', ''], [$arrayProductCategory[0], array_sum($valueDay)]];

            //Biểu đồ chi nhánh
            $dataBranchChart = [];
            $dataBranchChart[] = ['', ''];
            foreach ($arrayBranchChart as $item) {
                $dataBranchChart[] = [$item['name'], $item['value']];
            }
            return response()->json([
                'day' => $day,
                'valueDay' => $valueDay,
                'quantityOddCustomer' => $quantityOddCustomer,
                'quantityAllCustomer' => $quantityAllCustomer,
                'dataAreaChartProduct' => $dataAreaChartProduct,
                'dataProductGroup' => $dataProductGroup,
                'dataBranchChart' => $dataBranchChart,
            ]);
        }
    }
}
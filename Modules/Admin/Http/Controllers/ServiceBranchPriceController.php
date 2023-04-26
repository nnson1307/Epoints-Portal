<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/13/2018
 * Time: 10:06 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\ServiceBranchPrice\ServiceBranchPriceRepositoryInterface;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;
use Modules\Admin\Repositories\ServiceImage\ServiceImageRepositoryInterface;

class ServiceBranchPriceController extends Controller
{
    protected $service_branch_price;
    protected $service_category;
    protected $service;
    protected $service_image;
    protected $branch;

    public function __construct(
        ServiceBranchPriceRepositoryInterface $service_branch_prices,
        ServiceCategoryRepositoryInterface $service_category,
        ServiceRepositoryInterface $services, ServiceImageRepositoryInterface $images,
        BranchRepositoryInterface $branches
    )
    {
        $this->service_branch_price = $service_branch_prices;
        $this->service_category = $service_category;
        $this->service = $services;
        $this->service_image = $images;
        $this->branch = $branches;
    }

    /**
     * Trang chính
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        $serviceBranchPriceList = $this->service_branch_price->getServiceBranchPrice();
        $branchList = $this->branch->getBranch();
        $serviceList = $this->service->getService();

        $priceList = [];
        $result = [];

        foreach ($serviceList as $itemService) {
            foreach ($branchList as $brachId => $branchName) {
                $priceList[$brachId] = 0;
                foreach ($serviceBranchPriceList as $item) {
                    if ($item['service_id'] == $itemService['service_id']) {
                        if ($item['branch_id'] == $brachId) {
                            $priceList[$brachId] = $item['new_price'];
                        }
                    }
                }
            }
            $result[] = [$itemService, $priceList];
        }

        //dd($result);
        $result = collect($result)->forPage(1, 10);
        return view('admin::service-branch-prices.index', [
            'LIST' => $result,
            'FILTER' => $this->filters(),
            'BRANCH_LIST' => $branchList,
            'SERVICE_LIST' => $this->service->list(),
        ]);
    }

    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'services$service_category_id', 'created_at', 'search']);
        $serviceBranchPriceList = $this->service_branch_price->getServiceBranchPrice();
        $branchList = $this->branch->getBranch();
        $serviceList = $this->service->getService();

        $priceList = [];
        $result = [];

        foreach ($serviceList as $itemService) {
            foreach ($branchList as $brachId => $branchName) {
                $priceList[$brachId] = 0;
                foreach ($serviceBranchPriceList as $item) {
                    if ($item['service_id'] == $itemService['service_id']) {
                        if ($item['branch_id'] == $brachId) {
                            $priceList[$brachId] = $item['new_price'];
                        }
                    }
                }
            }
            $result[] = [$itemService, $priceList];
        }

        $result = collect($result)->forPage($request->page, 10);
        return view('admin::service-branch-prices.list', [
            'LIST' => $result,
            'FILTER' => $this->filters(),
            'BRANCH_LIST' => $branchList,
            'SERVICE_LIST' => $this->service->list($filter),
        ]);
    }

    /**
     * Cấu hình giá
     */
    public function configAction()
    {
        $branchList = $this->branch->getBranch();
        $serviceList = $this->service->getService();

        return view('admin::service-branch-prices.config', [
            'BRANCH_LIST' => $branchList,
            'LIST' => $serviceList,
        ]);
    }

    /**
     * Ajax lấy danh sách dịch vụ theo chi nhánh
     *
     * @param Request $request
     */
    public function listConfigAction(Request $request)
    {
        $branchId = $request->branch_id;
        if ($branchId == 0) {
            $branchList = $this->branch->getBranch();
            $serviceList = $this->service->getService();
            return view('admin::service-branch-prices.list-branch-price', [
                'BRANCH_LIST' => $branchList,
                'LIST' => $serviceList,
            ]);
        }
        if ($request->price != 0) {
            $branchId = $request->price;
        }
//        dd($request->all(),$branchId);

        $serviceBranchPriceList = $this->service_branch_price->getServiceBranchPriceByBranchId($branchId);
        $branchCopyList = $this->branch->getBranch([$request->branchId]);
//        $result = [$branchList, $serviceBranchPriceList];


        $branchList = $this->branch->getBranch();
        $serviceList = $this->service->getService();
        return view('admin::service-branch-prices.list-branch-price', [
            'BRANCH_LIST' => $branchList,
            'LIST' => $serviceList,
            'PRICE_LIST' => $serviceBranchPriceList,
            'BRANCH_COPY_LIST' => $branchCopyList
        ]);
//        return response()->json($result);
    }

    /**
     * Lấy danh sách branch bảng giá cần sao chép
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listConfigActionBranchPrice(Request $request)
    {
        $branchCopyList = $this->branch->getBranch([$request->branchId]);
        return response()->json($branchCopyList);
    }

    public function submitConfigAction(Request $request)
    {
        $branchId = $request->branchId;
        $listService = $request->listService;
        $listPriceWeek = $request->listPriceWeek;
        $listPriceMonth = $request->listPriceMonth;
        $listPriceYear = $request->listPriceYear;
        for ($i = 0; $i < count($listService); $i++) {
            $this->service_branch_price->editConfigPrice($listService[$i], $listPriceWeek[$i], $listPriceMonth[$i], $listPriceYear[$i], $branchId);
        }
//        foreach ($listService as $key => $value) {
//            $this->service_branch_price->editConfigPrice($value, $branchId);
//        }

        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    protected function filters()
    {
        $optionCate = $this->service_category->getOptionServiceCategory();
        $groupCate = (["" => __("Chọn nhóm dịch vụ")]) + $optionCate;
        return [
            'services$service_category_id' => [
                'data' => $groupCate
            ],
        ];
    }

    public function listPriceServiceAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'services$is_actived',
            'services$service_category_id', 'created_at', 'search_branch']);
        $list = $this->service_branch_price->list($filter, $request->service_id);
        return view('admin::service-branch-prices.list-price-branch', ['LIST' => $list]);
    }

    public function removeAction($id)
    {
        $this->service_branch_price->remove($id);

        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function getBranchAction(Request $request)
    {
        $id = $request->id;
        $listId = $request->listid;

        if ($listId != null)
            $data = $this->service_branch_price->list('', $id, $listId);
        else
            $data = $this->service_branch_price->list('', $id);

        return response()->json($data);
    }

    public function editAction($id)
    {
        $serviceItem = $this->service->getItem($id);
        $get = $this->service_branch_price->list('', $id);
        $itemBranch = $this->service_branch_price->getItem($id);
        $itemImage = $this->service_image->getItem($id);
        $optionBranch = $this->branch->getBranch();
        $arrayBranch = [];
        $arrayBranchDB = [];
        foreach ($get as $item) {
            $arrayBranchDB[] = $item['branch_id'];
        }

        foreach ($optionBranch as $key => $value) {
            if (!in_array($key, $arrayBranchDB)) {
                $arrayBranch[] = $key;
            }

        }
        $branchWhereIn = $this->branch->searchWhereIn($arrayBranch);
//        dd($get);
        return view('admin::service-branch-prices.edit', [
            'item' => $serviceItem,
            'itemBranch' => $itemBranch,
            'itemImage' => $itemImage,
            'optionBranch' => $optionBranch,
            'LIST' => $get,
            'FILTER' => $this->filters(),
            'branchWhereIn' => $branchWhereIn
        ]);
    }

    public function submitEditAction(Request $request)
    {
        $listBranch = $request->listBranch;
        $listPriceWeek = $request->listPriceWeek;
        $listPriceMonth = $request->listPriceMonth;
        $listPriceYear = $request->listPriceYear;

        $idService = $request->idService;

        for ($i = 0; $i < count($listBranch); $i++) {
            $isActived = ($listBranch[$i][4] == 'true') ? 1 : 0;

            $data = [
                'old_price' => $listBranch[$i][2],
                'new_price' => $listBranch[$i][3],
                'price_week' => $listPriceWeek[$i][3],
                'price_month' => $listPriceMonth[$i][3],
                'price_year' => $listPriceYear[$i][3],
                'is_actived' => $isActived,
            ];

            if ($listBranch[$i][0] != "0") {
                $this->service_branch_price->edit($data, $listBranch[$i][0]);
            } else {
                if ($isActived == 1 || $listBranch[$i][3] != 0) {
                    $data2 = [
                        'branch_id' => $listBranch[$i][1],
                        'service_id' => $idService,
                        'old_price' => $listBranch[$i][2],
                        'new_price' => $listBranch[$i][3],
                        'price_week' => $listPriceWeek[$i][3],
                        'price_month' => $listPriceMonth[$i][3],
                        'price_year' => $listPriceYear[$i][3],
                        'is_actived' => $isActived,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->staff_id
                    ];
                    $this->service_branch_price->add($data2);
                }
            }
        }

        foreach ($listBranch as $item) {
            $isActived = ($item[4] == 'true') ? 1 : 0;

            $data = [
                'new_price' => $item[3],
                'is_actived' => $isActived,
            ];

            if ($item[0] != "0") {
                $this->service_branch_price->edit($data, $item[0]);
            } else {
                if ($isActived == 1 || $item[3] != 0) {
                    $data2 = [
                        'branch_id' => $item[1],
                        'service_id' => $idService,
                        'old_price' => $item[2],
                        'new_price' => $item[3],
                        'is_actived' => $isActived,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->staff_id
                    ];
                    $this->service_branch_price->add($data2);
                }
            }

        }
        return response()->json($listBranch[0]);
    }

    /**
     * Ajax lấy danh sách giá chi nhánh
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listBranchAction(Request $request)
    {
        $get = $this->service_branch_price->list('', $request->id)->toArray();

        return response()->json($get['data']);
    }

    public function filterAction(Request $request)
    {
        $keyword = $request->keyword;
        $serviceCategory = $request->serviceCategory;

        $serviceBranchPriceList = $this->service_branch_price->getServiceBranchPrice();
        $branchList = $this->branch->getBranch();
        $serviceList = $this->service->getService($keyword, $serviceCategory);

        $priceList = [];
        $result = [];

        foreach ($serviceList as $itemService) {
            foreach ($branchList as $brachId => $branchName) {
                $priceList[$brachId] = 0;
                foreach ($serviceBranchPriceList as $item) {
                    if ($item['service_id'] == $itemService['service_id']) {
                        if ($item['branch_id'] == $brachId) {
                            $priceList[$brachId] = $item['new_price'];
                        }
                    }
                }
            }
            $result[] = [$itemService, $priceList];
        }

        $resultPaginate = collect($result)->forPage(1, 10);

        $content = view('admin::service-branch-prices.filter', [
            'BRANCH' => $branchList,
            'page' => 1,
            'data' => $result,
            'LIST' => $resultPaginate,
        ])->render();
        return $content;
    }

    public function pagingFilterAction(Request $request)
    {
        $keyword = $request->keyword;
        $serviceCategory = $request->serviceCategory;
        $page = $request->page;

        $serviceBranchPriceList = $this->service_branch_price->getServiceBranchPrice();
        $branchList = $this->branch->getBranch();
        $serviceList = $this->service->getService($keyword, $serviceCategory);

        $priceList = [];
        $result = [];

        foreach ($serviceList as $itemService) {
            foreach ($branchList as $brachId => $branchName) {
                $priceList[$brachId] = 0;
                foreach ($serviceBranchPriceList as $item) {
                    if ($item['service_id'] == $itemService['service_id']) {
                        if ($item['branch_id'] == $brachId) {
                            $priceList[$brachId] = $item['new_price'];
                        }
                    }
                }
            }
            $result[] = [$itemService, $priceList];
        }

        $resultPaginate = collect($result)->forPage($page, 10);

        $content = view('admin::service-branch-prices.filter', [
            'BRANCH' => $branchList,
            'page' => $page,
            'data' => $result,
            'LIST' => $resultPaginate,
        ])->render();
        return $content;
    }
}
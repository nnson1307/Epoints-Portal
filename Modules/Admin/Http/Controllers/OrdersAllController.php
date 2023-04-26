<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderAll\OrderAllRepositoryInterface;
use Modules\Admin\Repositories\OrderSource\OrderSourceRepositoryInterface;

class OrdersAllController extends Controller
{

    protected $branch;
    protected $ordersAll;
    protected $orderSource;
    protected $orderOld;

    public function __construct(
        OrderAllRepositoryInterface $ordersAll,
        BranchRepositoryInterface $branch,
        OrderSourceRepositoryInterface $orderSource,
        OrderRepositoryInterface $orderOld

    )
    {
        $this->ordersAll = $ordersAll;
        $this->branch = $branch;
        $this->orderSource = $orderSource;
        $this->orderOld = $orderOld;
    }

    protected function filters()
    {
        $optionBranch = $this->branch->getBranch();
        $groupCate = (['' => __('Chọn chi nhánh')]) + $optionBranch;
        return [
            'branches$branch_id' => [
                'data' => $groupCate
            ],
            'orders$process_status' => [
                'data' => [
                    '' => __('Trạng thái'),
                    'confirmed' => __('Đã xác nhận'),
                    'paysuccess' => __('Đã thanh toán'),
                    'pay-half' => __('Thanh toán con thiếu'),
                    'new' => __('Mới'),
                    'ordercancle' => __('Đã hủy')
                ]
            ]
        ];
    }
    /**
     * Danh sách đơn hàng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search_type',
            'search',
            'branches$branch_id',
            'created_at',
            'orders$process_status',
            'receive_at_counter',
            'orders$customer_id',
            'order_source_id'
        ]);

        if (isset($filter['orders$customer_id']) && $filter['orders$customer_id'] == null) {
            $filter['orders$order_source_id'] = 1;
        }

        session()->put('order-all-export',$filter);

        $data = $this->orderOld->list($filter);

        return view('admin::orders-all.list', [
            'LIST' => $data['list'],
            'receipt' => $data['receipt'],
            'page' => $filter['page']
        ]);
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if (session()->has('order-all-export')){
            session()->forget('order-all-export');
        }
        $screening = $request->all();

        //danh sach nguon don hang
        $listOrderSource =  $this->orderSource->list();

        // danh sach don hang
        $data = $this->ordersAll->allOrder($screening);
        return view('admin::orders-all.index',
        [
            'LIST' => $data['list'],
            'receipt' => $data['receipt'],
            'FILTER' => $this->filters(),
            'orderSource' => $listOrderSource
        ]);
    }
    /**
     *  Export danh sách đơn hàng
     * @param Request $request
     * @return mixed
     */
    public function exportList(Request $request)
    {
        $params = [];
        if (session()->has('order-all-export')){
            $params = session()->get('order-all-export');
        }
        return $this->ordersAll->exportList($params);
    }
}


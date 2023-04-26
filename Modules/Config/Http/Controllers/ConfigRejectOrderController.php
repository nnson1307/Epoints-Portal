<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/05/2022
 * Time: 10:58
 */

namespace Modules\Config\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Config\Repositories\ConfigRejectOrder\ConfigRejectOrderRepoInterface;

class ConfigRejectOrderController extends Controller
{
    protected $rejectOrder;

    public function __construct(
        ConfigRejectOrderRepoInterface $rejectOrder
    ) {
        $this->rejectOrder = $rejectOrder;
    }

    /**
     * View cấu hình từ chối đơn hàng
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = $this->rejectOrder->getDataView();

        return view('config::config-reject-order.index', $data);
    }

    /**
     * Lưu cấu hình
     *
     * @param Request $request
     * @return mixed
     */
    public function saveAction(Request $request)
    {
        return $this->rejectOrder->save($request->all());
    }
}
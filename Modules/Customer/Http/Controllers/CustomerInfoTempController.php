<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/05/2021
 * Time: 10:12
 */

namespace Modules\Customer\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Customer\Http\Requests\CustomerInfoTemp\ConfirmRequest;
use Modules\Customer\Repositories\CustomerInfoTemp\CustomerInfoTempRepo;

class CustomerInfoTempController extends Controller
{
    protected $infoTemp;

    public function __construct(
        CustomerInfoTempRepo $infoTemp
    ) {
        $this->infoTemp = $infoTemp;
    }

    /**
     * View danh sách thông tin cần cập nhật
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->infoTemp->list();

        return view('customer::customer-info-temp.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    public function filters()
    {
        return [

        ];
    }

    /**
     * Ajax filter, phân trang ds thông tin cần cập nhật
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
        ]);

        $data = $this->infoTemp->list($filter);

        return view('customer::customer-info-temp.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View xác nhận thông tin cần cập nhật
     *
     * @param $infoTempId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function confirmAction($infoTempId)
    {
        $data = $this->infoTemp->dataViewConfirm([
            'customer_info_temp_id' => $infoTempId
        ]);

        return view('customer::customer-info-temp.confirm', $data);
    }

    /**
     * Xác nhận thông tin cần cập nhật
     *
     * @param ConfirmRequest $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function submitConfirmAction(ConfirmRequest $request)
    {
        return $this->infoTemp->confirm($request->all());
    }
}
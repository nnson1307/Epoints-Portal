<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2021
 * Time: 12:12
 */

namespace Modules\Customer\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Customer\Http\Requests\CustomerRemindUse\CareRequest;
use Modules\Customer\Repositories\CustomerRemindUse\CustomerRemindUseRepoInterface;

class CustomerRemindUseController extends Controller
{
    protected $remindUse;

    public function __construct(
        CustomerRemindUseRepoInterface $remindUse
    ) {
        $this->remindUse = $remindUse;
    }

    /**
     * Danh sách dự kiến nhắc sử dụng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        //Lấy data danh sách
        $data = $this->remindUse->list();

        return view('customer::customer-remind-use.index', [
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
     * Ajax filter + phân trang danh sách dự kiến nhắc sử dụng
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
            'sent_at',
        ]);
        //Lấy data danh sách
        $data = $this->remindUse->list($filter);

        return view('customer::customer-remind-use.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View chỉnh sửa dự kiến nhắc sử dụng
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($id)
    {
        $data = $this->remindUse->dataViewEdit($id);

        if ($data['item'] == null || $data['item']['is_finish'] == 1) {
            return redirect()->route('customer-remind-use');
        }

        return view('customer::customer-remind-use.edit', $data);
    }

    /**
     * Chỉnh sửa dự kiến nhắc sử dụng
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        return $this->remindUse->update($request->all());
    }

    /**
     * Modal chăm sóc khách hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modalCareAction(Request $request)
    {
        $data = $this->remindUse->dataViewEdit($request->customer_remind_use_id);

        $html = \View::make('customer::customer-remind-use.pop.care', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Chăm sóc khách hàng
     *
     * @param CareRequest $request
     * @return mixed
     */
    public function submitCareAction(CareRequest $request)
    {
        return $this->remindUse->submitCare($request->all());
    }

    /**
     * Chi tiết nhắc sử dụng lại
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|mixed
     */
    public function show($id)
    {
        $data = $this->remindUse->dataViewEdit($id);

        if ($data['item'] == null) {
            return redirect()->route('customer-remind-use');
        }

        return view('customer::customer-remind-use.detail', $data);
    }
}
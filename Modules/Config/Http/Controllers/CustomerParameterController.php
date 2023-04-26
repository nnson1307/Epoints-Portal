<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 18/11/2021
 * Time: 14:01
 */

namespace Modules\Config\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Config\Http\Requests\ConfigCustomerParameter\StoreRequest;
use Modules\Config\Http\Requests\ConfigCustomerParameter\UpdateRequest;
use Modules\Config\Repositories\ConfigCustomerParameter\ConfigCustomerParameterRepoInterface;

class CustomerParameterController extends Controller
{
    protected $configParameter;

    public function __construct(
        ConfigCustomerParameterRepoInterface $configParameter
    ) {
        $this->configParameter = $configParameter;
    }

    /**
     * Danh sách tham số
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        //Lấy ds tham số
        $data = $this->configParameter->list();

        return view('config::config-customer-parameter.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        return [];
    }

    /**
     * Ajax danh sách đánh giá
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search']);

        //Lấy ds tham số
        $data = $this->configParameter->list($filters);

        return view('config::config-customer-parameter.list', [
            'LIST' => $data['list'],
            'page' => $filters['page']
        ]);
    }

    /**
     * View thêm tham số
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create()
    {
        return view('config::config-customer-parameter.create');
    }

    /**
     * Thêm tham số
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->configParameter->store($request->all());
    }

    /**
     * View chỉnh sửa tham số
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($id)
    {
        $data = $this->configParameter->getDataEdit($id);

        return view('config::config-customer-parameter.edit', $data);
    }

    /**
     * Chỉnh sửa tham số
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->configParameter->update($request->all());
    }

    /**
     * Xoá tham số
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        return $this->configParameter->destroy($request->all());
    }
}
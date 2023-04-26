<?php

namespace Modules\Delivery\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Delivery\Repositories\DeliveryCost\DeliveryCostRepoInterface;
use Modules\Delivery\Http\Requests\DeliveryCost\StoreRequest;
use Modules\Delivery\Http\Requests\DeliveryCost\UpdateRequest;

class DeliveryCostController extends Controller
{
    protected $deliveryCost;

    public function __construct(
        DeliveryCostRepoInterface $deliveryCost
    )
    {
        $this->deliveryCost = $deliveryCost;
    }

    public function index()
    {
        $data = $this->deliveryCost->list();
        return view('delivery::delivery-cost.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        return [

        ];
    }

    /**
     * Ajax filter, phân trang ds chi phí giao hang
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at',
        ]);

        $data = $this->deliveryCost->list($filter);

        return view('delivery::delivery-cost.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View tao chi phi giao hang
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function create()
    {
        $dataCreate = $this->deliveryCost->dataViewCreate();
        return view('delivery::delivery-cost.create', [
            'optionProvince' => $dataCreate['optionProvince'],
        ]);
    }

    /**
     * Them moi chi phi giao hang
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->deliveryCost->store($data);
    }

    /**
     * View chinh sua chi phí giao hang
     *
     * @param $deliveryCostId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function edit($deliveryCostId)
    {
        $data = $this->deliveryCost->dataViewEdit($deliveryCostId);
        return view('delivery::delivery-cost.edit', $data);
    }

    /**
     * Chinh sua chi phi giao hang
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->deliveryCost->update($data);
    }

    /**
     * Xoa chi phi giao hang
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $id = $request->delivery_cost_id;
        return $this->deliveryCost->destroy($id);
    }

    /**
     * Load danh sách huyện theo tỉnh
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadDistrictAction(Request $request)
    {
        $district = $this->deliveryCost->getOptionDistrict(request()->all());
        $data = [];
        foreach ($district as $key => $value) {
            $data[] = [
                'id' => $value['districtid'],
                'postcode' => $value['postcode'],
                'name' => $value['name'],
                'type' => $value['type']
            ];
        }
        return response()->json([
            'optionDistrict' => $data,
            'pagination' => $district->nextPageUrl() ? true : false
        ]);
    }

    /**
     * Danh sách huyện theo tỉnh thành phân trang
     *
     * @param Request $request
     * @return mixed
     */
    public function loadDistrictPagination(Request $request)
    {
        $input = $request->all();
        return $this->deliveryCost->loadDistrictPagination($input);
    }
}
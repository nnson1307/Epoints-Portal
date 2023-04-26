<?php

namespace Modules\Warranty\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Warranty\Http\Requests\MaintenanceCostType\StoreRequest;
use Modules\Warranty\Http\Requests\MaintenanceCostType\UpdateRequest;
use Modules\Warranty\Repository\MaintenanceCostType\MaintenanceCostTypeRepoInterface;

class MaintenanceCostTypeController extends Controller
{
    protected $maintenanceCostType;

    public function __construct(MaintenanceCostTypeRepoInterface $maintenanceCostType)
    {
        $this->maintenanceCostType = $maintenanceCostType;
    }

    public function index()
    {
        $data = $this->maintenanceCostType->getList();
        return view('warranty::maintenance-cost-type.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    public function filters()
    {
        return [

        ];
    }

    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
        ]);
        $data = $this->maintenanceCostType->getList($filter);
        return view('warranty::maintenance-cost-type.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm chi phí phát sinh
     *
     * @return array
     */
    public function create()
    {
        return view('warranty::maintenance-cost-type.create');
    }

    /**
     * Thêm chi phí phát sinh
     *
     * @param Request $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->maintenanceCostType->store($data);
    }

    /**
     * View chỉnh sửa chi phí phát sinh
     *
     * @param $maintenanceCostTypeId
     * @return array
     */
    public function edit($maintenanceCostTypeId)
    {
        $getData = $this->maintenanceCostType->dataViewEdit($maintenanceCostTypeId);
        return view('warranty::maintenance-cost-type.edit', [
            'item' => $getData
        ]);
    }

    /**
     * Cập nhật chi phí phát sinh
     *
     * @param Request $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->maintenanceCostType->update($data);
    }

    /**
     * Xoá chi phí phát sinh
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $data = $request->all();
        return $this->maintenanceCostType->delete($data);
    }
}
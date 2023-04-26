<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payment\Http\Requests\DiscountCauses\StoreRequest;
use Modules\Payment\Http\Requests\DiscountCauses\UpdateRequest;
use Modules\Payment\Repositories\DiscountCauses\DiscountCausesRepoInterface;

class DiscountCausesController extends Controller
{
    protected $discountCauses;

    public function __construct(DiscountCausesRepoInterface $discountCauses)
    {
        $this->discountCauses = $discountCauses;
    }

    public function index()
    {
        $data = $this->discountCauses->getList();
        return view('payment::discount-causes.index', [
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
        $data = $this->discountCauses->getList($filter);
        return view('payment::discount-causes.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm lý do giảm giá
     *
     * @return array
     */
    public function create()
    {
        return view('payment::discount-causes.create');
    }

    /**
     * Thêm lý do giảm giá
     *
     * @param Request $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->discountCauses->store($data);
    }

    /**
     * View chỉnh sửa lý do giảm giá
     *
     * @param $discountCausesId
     * @return array
     */
    public function edit($discountCausesId)
    {
        $getData = $this->discountCauses->dataViewEdit($discountCausesId);
        return view('payment::discount-causes.edit', [
            'item' => $getData
        ]);
    }

    /**
     * Cập nhật lý do giảm giá
     *
     * @param Request $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->discountCauses->update($data);
    }

    /**
     * Xoá lý do giảm giá
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $data = $request->all();
        return $this->discountCauses->delete($data);
    }
}
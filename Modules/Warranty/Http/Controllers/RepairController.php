<?php

namespace Modules\Warranty\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Warranty\Http\Requests\Repair\StoreRequest;
use Modules\Warranty\Http\Requests\Repair\UpdateRequest;
use Modules\Warranty\Repository\Repair\RepairRepoInterface;

class RepairController extends Controller
{
    protected $repair;

    public function __construct(RepairRepoInterface $repair)
    {
        $this->repair = $repair;
    }

    public function index()
    {
        $data = $this->repair->list();
        return view('warranty::repair.index', [
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
            'created_at',
        ]);
        $data = $this->repair->list($filter);
        return view('warranty::repair.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Chi tiết phiếu bảo dưỡng
     *
     * @param $repairId
     * @return array
     */
    public function show($repairId)
    {
        $data = $this->repair->dataViewEdit($repairId);
        return view('warranty::repair.detail', $data);
    }

    /**
     * View thêm phiếu bảo dưỡng
     *
     * @return array
     */
    public function create()
    {
        $data = $this->repair->dataViewCreate();
        return view('warranty::repair.add', $data);
    }

    /**
     * Lưu phiếu bảo dưỡng
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->repair->store($data);
    }

    /**
     * view chỉnh sửa phiếu bảo dưỡng
     *
     * @param $repairId
     * @return array
     */
    public function edit($repairId)
    {
        $data = $this->repair->dataViewEdit($repairId);
        if ($data['item'] == null || in_array($data['item']['status'], ['finish', 'cancel'])) {
            return redirect()->route('repair');
        }
        return view('warranty::repair.edit', $data);
    }

    /**
     * Cập nhật phiếu bảo dưỡng
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->repair->update($data);
    }

    /**
     * Render modal phiếu chi cho bảo dưỡng
     *
     * @param Request $request
     * @return mixed
     */
    public function modalPayment(Request $request)
    {
        return $this->repair->modalPayment($request->all());
    }

    /**
     * Thêm phiếu chi
     *
     * @param Request $request
     * @return mixed
     */
    public function submitPayment(Request $request)
    {
        return $this->repair->submitPayment($request->all());
    }
}
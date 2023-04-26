<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/2/2021
 * Time: 2:41 PM
 */

namespace Modules\Warranty\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Modules\Warranty\Http\Requests\Maintenance\StoreRequest;
use Modules\Warranty\Http\Requests\Maintenance\UpdateRequest;
use Modules\Warranty\Repository\Maintenance\MaintenanceRepoInterface;

class MaintenanceController extends Controller
{
    protected $maintenance;

    public function __construct(
        MaintenanceRepoInterface $maintenance
    ) {
        $this->maintenance = $maintenance;
    }

    /**
     * Danh sách phiếu bảo hành
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        session()->forget('temp');
        session()->forget('warranty_choose');

        $list = $this->maintenance->list();

        return view('warranty::maintenance.index', [
            'LIST' => $list['list'],
            'FILTER' => $this->filters()
        ]);
    }

    public function filters()
    {
        return [

        ];
    }

    /**
     * Ajax load danh sách phiếu bảo trì
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
            'created_at',
            'date_estimate_delivery',
            'maintenance$staff_id',
            'maintenance$status',
            'maintenance$warranty_code'
        ]);

        $list = $this->maintenance->list($filter);

        return view('warranty::maintenance.list', [
            'LIST' => $list['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm phiếu bảo trì
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create(Request $request)
    {
        session()->forget('temp');
        session()->forget('warranty_choose');

        $data = $this->maintenance->dataViewCreate($request->all());

        return view('warranty::maintenance.create', $data);
    }

    /**
     * Chọn phiếu bảo hành
     *
     * @param Request $request
     * @return mixed
     */
    public function chooseWarrantyAction(Request $request)
    {
        return $this->maintenance->chooseWarranty($request->all());
    }


    /**
     * Load đối tượng khi loại đối tượng thay đổi
     *
     * @param Request $request
     * @return mixed
     */
    public function loadObjectAction(Request $request)
    {
        return $this->maintenance->loadObject($request->all());
    }

    /**
     * Show modal chọn phiếu bảo hành
     *
     * @param Request $request
     * @return mixed
     */
    public function modalWarrantyAction(Request $request)
    {
        return $this->maintenance->modalWarranty($request->all());
    }

    /**
     * Ajax filter, phân trang list phiếu bảo hành
     *
     * @param Request $request
     * @return mixed
     */
    public function listWarrantyAction(Request $request)
    {
        return $this->maintenance->listWarranty($request->all());
    }

    /**
     * Tắt modal chọn phiếu bảo hành, clear session temp
     *
     */
    public function closeModalWarrantyAction()
    {
        session()->forget('temp');
    }

    /**
     * Chọn phiếu bảo hành áp dụng
     *
     * @return mixed
     */
    public function submitChooseWarrantyAction()
    {
        return $this->maintenance->submitChooseWarranty();
    }

    /**
     * Xóa session đã lưu phiếu bảo hành
     *
     */
    public function clearSessionAction()
    {
        session()->forget('temp');
        session()->forget('warranty_choose');
    }

    /**
     * Tạo phiếu bảo trì
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->maintenance->store($request->all());
    }

    /**
     * Chỉnh sửa phiếu bảo trì
     *
     * @param $maintenanceId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($maintenanceId)
    {
        session()->forget('temp');
        session()->forget('warranty_choose');

        $data = $this->maintenance->dataViewEdit($maintenanceId);

        if ($data['item'] == null || in_array($data['item']['status'], ['finish', 'cancel'])) {
            return redirect()->route('maintenance');
        }

        return view('warranty::maintenance.edit', $data);
    }

    /**
     * Chỉnh sửa phiếu bảo trì
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->maintenance->update($request->all());
    }

    /**
     * Show modal thanh toán phiếu bảo trì
     *
     * @param Request $request
     * @return mixed
     */
    public function modalReceiptAction(Request $request)
    {
        return $this->maintenance->modalReceipt($request->all());
    }

    /**
     * Thanh toán phí bảo trì
     *
     * @param Request $request
     * @return mixed
     */
    public function submitReceiptAction(Request $request)
    {
        return $this->maintenance->submitReceipt($request->all());
    }

    /**
     * Chi tiết phiếu bảo trì
     *
     * @param $maintenanceId
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show($maintenanceId)
    {
        $data = $this->maintenance->show($maintenanceId);

        return view('warranty::maintenance.detail', $data);
    }

    /**
     * Tạo qr code thanh toán online
     *
     * @param Request $request
     * @return mixed
     */
    public function genQrCodeAction(Request $request)
    {
        return $this->maintenance->genQrCode($request->all());
    }
}
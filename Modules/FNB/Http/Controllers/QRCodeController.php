<?php

namespace Modules\FNB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FNB\Http\Requests\FNBQrCodeTemplate\StoreRequest;
use Modules\FNB\Http\Requests\FNBQrCodeTemplate\UpdateRequest;
use Modules\FNB\Repositories\Branch\BranchRepositoryInterface;
use Modules\FNB\Repositories\Config\ConfigRepositoryInterface;
use Modules\FNB\Repositories\ConfigColumn\ConfigColumnRepositoryInterface;
use Modules\FNB\Repositories\FNBAreas\FNBAreasRepositoryInterface;
use Modules\FNB\Repositories\FNBQrCode\FNBQrCodeRepositoryInterface;
use Modules\FNB\Repositories\FNBQrCodeScan\FNBQrCodeScanRepositoryInterface;
use Modules\FNB\Repositories\FNBQrTemplateFont\FNBQrTemplateFontRepositoryInterface;
use Modules\FNB\Repositories\FNBQrTemplateFrames\FNBQrTemplateFramesRepositoryInterface;
use Modules\FNB\Repositories\FNBQrTemplateLogo\FNBQrTemplateLogoRepositoryInterface;
use Modules\FNB\Repositories\FNBTable\FNBTableRepositoryInterface;
use Modules\FNB\Repositories\Staff\StaffRepositoryInterface;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    private $qrCode;
    private $route = 'fnb.qr-code';

//    private $typeQr;

    public function __construct(FNBQrCodeRepositoryInterface $qrCode)
    {
        $this->qrCode = $qrCode;
        $this->typeQr = [
            'url' => __('URL'),
            'vcard' => __('VCARD'),
            'text' => __('Văn bản'),
            'email' => __('Email'),
            'sms' => __('SMS'),
            'wifi' => __('Wifi'),
            'facebook' => __('Facebook'),
            'pdf' => __('PDF'),
            'mp3' => __('MP3'),
            'image' => __('Image'),
        ];

        $this->status = [
            'new' => [
                'name' => __('Nháp'),
                'color' => '#FFC700'
            ],
            'active' => [
                'name' => __('Đang sử dụng'),
                'color' => '#068229'
            ],
            'expired' => [
                'name' => __('Hết hạn'),
                'color' => '#FF0000'
            ],
            'cancel' => [
                'name' => __('Hủy'),
                'color' => '#787878'
            ],
        ];
    }

    public function index(){

        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);
        $rStaff = app()->get(StaffRepositoryInterface::class);

        if (session()->has('qr-code-'.Auth::id())){
            session()->forget('qr-code-'.Auth::id());
        }

//        Lấy danh sách cấu hình hiển thị hoặc tạo mới nếu chưa có
        $listConfigStaff = $rConfigColumn->getAllConfigStaff(Auth::id(),$this->route);

        if (count($listConfigStaff) != 0){
            $listConfigStaff = collect($listConfigStaff)->groupBy('type');
        }

//        Lấy danh sách nhân viên
        $listStaff = $rStaff->getAll();

        $list = $this->qrCode->getList();

        return view('fnb::qr-code.index', [
            'listConfigStaff' => $listConfigStaff,
            'list' => $list,
            'typeQR' => $this->typeQr,
            'status' => $this->status,
            'listStaff' => $listStaff

        ]);
    }

    public function list(Request $request){
        $param = $request->all();
        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);

//        Lấy danh sách cấu hình hiển thị hoặc tạo mới nếu chưa có
        $listConfigStaff = $rConfigColumn->getAllConfigStaff(Auth::id(),$this->route);

        if (count($listConfigStaff) != 0){
            $listConfigStaff = collect($listConfigStaff)->groupBy('type');
        }

        if (session()->has('qr-code-'.Auth::id())){
            session()->forget('qr-code-'.Auth::id());
        }

        session()->put('qr-code-'.Auth::id(),$param);

        $list = $this->qrCode->getList($param);

        return view('fnb::qr-code.list', [
            'listConfigStaff' => $listConfigStaff,
            'list' => $list,
            'typeQR' => $this->typeQr,
            'status' => $this->status
        ]);
    }

    /**
     * Hiển thị popup cấu hình hiển thị
     */
    public function showPopupConfig(Request $request){
        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);
        $param = $request->all();
        $param['route'] = $this->route;
        $data = $rConfigColumn->showColumn($param);
        return \response()->json($data);
    }

    /**
     * Lưu cấu hình
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function saveConfig(Request $request){
        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);
        $param = $request->all();
        $param['route'] = $this->route;
        $data = $rConfigColumn->saveConfig($param);
        return \response()->json($data);
    }

    public function addQrCode(){
        $rBranch = app()->get(BranchRepositoryInterface::class);
        $rQrTemplateFrames = app()->get(FNBQrTemplateFramesRepositoryInterface::class);
        $rQrTemplateLogo = app()->get(FNBQrTemplateLogoRepositoryInterface::class);
        $rQrTemplateFont = app()->get(FNBQrTemplateFontRepositoryInterface::class);
        $rConfig = app()->get(ConfigRepositoryInterface::class);

        $listBranch = $rBranch->getAllBranchPagination();

        $listFrames = $rQrTemplateFrames->getListFrames();
        $listLogo = $rQrTemplateLogo->getListLogo();
        $listFont = $rQrTemplateFont->getListFont();
        $config = $rConfig->getInfoByKey('url_qr_code');

        return view('fnb::qr-code.add',[
            'status' => $this->status,
            'listBranch' => $listBranch,
            'typeQR' => $this->typeQr,
            'listFrames' => $listFrames,
            'listLogo' => $listLogo,
            'listFont' => $listFont,
            'config' => $config
        ]);
    }

    /**
     * Lấy danh sách chi nhánh có phân trang
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getListBranch(Request $request){
        $rBranch = app()->get(BranchRepositoryInterface::class);
        $param = $request->all();
        $data = $rBranch->getAllBranchPagination($param);
        return response()->json($data);
    }

    /**
     * Lấy danh sách khu vực có phân trang
     * @param Request $request
     */
    public function getListArea(Request $request){
        $rArea = app()->get(FNBAreasRepositoryInterface::class);
        $param = $request->all();
        $data = $rArea->getListPagination($param);
        return response()->json($data);
    }

    /**
     * Lấy danh sách bàn có phân trang
     * @param Request $request
     */
    public function getListTable(Request $request){
        $rTable = app()->get(FNBTableRepositoryInterface::class);
        $param = $request->all();
        $data = $rTable->getListPagination($param);
        return response()->json($data);
    }

    /**
     * Lưu cấu hình QR Code
     * @param Request $request
     */
    public function submitQrCode(StoreRequest $request){
        $param = $request->all();
        $data = $this->qrCode->submitQrCode($param);
        return response()->json($data);
    }

    public function getClientIp() {
        $data = $this->qrCode->getClientIp();
        return response()->json($data);
    }

    /**
     * Lấy chi tiết qr code
     * @param $id
     */
    public function detail($id){
        $rQrCodeScan = app()->get(FNBQrCodeScanRepositoryInterface::class);
        $rConfig = app()->get(ConfigRepositoryInterface::class);
        $rQrTable = app()->get(FNBTableRepositoryInterface::class);

        $detail = $this->qrCode->getDetail($id);

        $listQr = $this->qrCode->getListQrCode($id);

        $totalScan = $rQrCodeScan->getTotalScan($id);

        $listTable = $rQrTable->getListTableByTemplate($id,$detail['apply_for']);

        $config = $rConfig->getInfoByKey('url_qr_code');

        return view('fnb::qr-code.detail', [
            'detail' => $detail,
            'status' => $this->status,
            'totalScan' => $totalScan,
            'listQr' => $listQr,
            'listTable' => $listTable,
            'config' => $config
        ]);
    }

    /**
     * Lấy chi tiết qr code
     * @param $id
     */
    public function edit($id){
        $rConfig = app()->get(ConfigRepositoryInterface::class);
        $rQrCodeScan = app()->get(FNBQrCodeScanRepositoryInterface::class);

        $detail = $this->qrCode->getDetail($id);

        $listQr = $this->qrCode->getListQrCode($id);

        $totalScan = $rQrCodeScan->getTotalScan($id);
        $config = $rConfig->getInfoByKey('url_qr_code');
        return view('fnb::qr-code.edit', [
            'detail' => $detail,
            'status' => $this->status,
            'totalScan' => $totalScan,
            'listQr' => $listQr,
            'config' => $config
        ]);
    }

    /**
     * Lấy danh sách table được scan
     * @param Request $request
     */
    public function searchTable(Request $request){
        $rQrCodeScan = app()->get(FNBQrCodeScanRepositoryInterface::class);
        $param = $request->all();
        $data = $rQrCodeScan->getListPagination($param);
        return response()->json($data);
    }

    /**
     * Export dữ liệu
     */
    public function export(){
        $data = [];

        if (session()->has('qr-code-'.Auth::id())){
            $data = session()->get('qr-code-'.Auth::id());
        }

        $data['typeQR'] = $this->typeQr;
        $data['status'] = $this->status;
        $data['route'] = $this->route;

        return $this->qrCode->export($data);
    }

    /**
     * render qr
     * @param Request $request
     */
    public function viewQrCode(Request $request){
        $param = $request->all();

        $data = $this->qrCode->viewQrCode($param);

        return response()->json($data);
    }

    /**
     * Xóa template
     * @param Request $request
     */
    public function remove(Request $request){
        $param = $request->all();

        $data = $this->qrCode->remove($param['id']);

        return response()->json($data);
    }

    /**
     * Cập nhật trạng thái
     * @param Request $request
     */
    public function update(UpdateRequest $request){
        $param = $request->all();

        $data = $this->qrCode->update($param);

        return response()->json($data);
    }

    public function uploadImage(Request $request){

        $data = $this->qrCode->uploadImage($request->all());

        return response()->json($data);
    }

    public function preview(Request $request){
        $param = $request->all();

        $data = $this->qrCode->preview($param);

        return view('fnb::qr-code.preview',$data);
    }

}

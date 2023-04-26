<?php


namespace Modules\FNB\Repositories\FNBQrCode;


use App\Exports\ExportFile;
use App\Http\Middleware\S3UploadsRedirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\FNBQrCodeTable;
use Modules\FNB\Models\FNBQrCodeTemplateTable;
use Modules\FNB\Models\FNBQrTemplateFramesTable;
use Modules\FNB\Models\FNBQrTemplateLogoTable;
use Modules\FNB\Models\FNBQrTemplateTable;
use Modules\FNB\Models\FNBTableTable;
use Modules\FNB\Repositories\Config\ConfigRepositoryInterface;
use Modules\FNB\Repositories\ConfigColumn\ConfigColumnRepositoryInterface;
use Modules\FNB\Repositories\FNBQrCodeScan\FNBQrCodeScanRepositoryInterface;


class FNBQrCodeRepository implements FNBQrCodeRepositoryInterface
{
    private $mQRCodeTemlate;

    public function __contruct(FNBQrCodeTemplateTable $mQRCode){
        $this->mQRCodeTemlate = $mQRCode;
    }

    public function getList(array $filter = [])
    {
        $mQRCodeTemlate = app()->get(FNBQrCodeTemplateTable::class);
        return $mQRCodeTemlate->getList($filter);
    }

    /**
     * Lưu cấu hình QR Code
     * @param $data
     * @return mixed|void
     */
    public function submitQrCode($data)
    {
        try
        {
            $rConfig = app()->get(ConfigRepositoryInterface::class);
            if ($data['expire_type'] == 'limited' ){
                if (Carbon::createFromFormat('H:i d/m/Y',$data['expire_start'])->format('Y-m-d H:i:00') > Carbon::createFromFormat('H:i d/m/Y',$data['expire_end'])->format('Y-m-d H:i:59')) {
                    return [
                        'error' => true,
                        'message' => __('Thời gian hiệu lực kết thúc phải lớn hơn thời gian hiệu lực bắt đầu')
                    ];
                }
            }

            $config = $rConfig->getInfoByKey('url_qr_code');

            $mQRCodeTemlate = app()->get(FNBQrCodeTemplateTable::class);
            $dataTmp = [
                'apply_for' => $data['apply_for'],
                'apply_branch_id' => isset($data['apply_branch_id']) && $data['apply_for'] == 'custom' ? $data['apply_branch_id'] : null,
                'apply_arear_id' => isset($data['apply_arear_id']) && $data['apply_for'] == 'custom' ? $data['apply_arear_id'] : null,
                'apply_table_id' => isset($data['apply_table_id']) && $data['apply_for'] == 'custom' ? $data['apply_table_id'] : null,
                'expire_type' => $data['expire_type'],
                'expire_start' => isset($data['expire_start']) && $data['expire_type'] == 'limited' ? Carbon::createFromFormat('H:i d/m/Y',$data['expire_start'])->format('Y-m-d H:i:00') : null,
                'expire_end' => isset($data['expire_end']) && $data['expire_type'] == 'limited' ? Carbon::createFromFormat('H:i d/m/Y',$data['expire_end'])->format('Y-m-d H:i:00') : null ,
                'status' => $data['status'],
                'is_request_location' => isset($data['is_request_location']) ? 1 : 0,
                'is_request_wifi' => isset($data['is_request_wifi']) ? 1 : 0,
                'qc_note' => $data['qc_note'],
                'qr_type' => $data['qr_type'],
                'location_lat' => isset($data['location_lat']) && isset($data['is_request_location']) ? $data['location_lat'] : null ,
                'location_lng' => isset($data['location_lng']) && isset($data['is_request_location']) ? $data['location_lng'] : null,
                'location_radius' => isset($data['location_radius']) && isset($data['is_request_location']) ? $data['location_radius'] : null,
                'wifi_name' => isset($data['wifi_name']) && isset($data['is_request_wifi']) ? $data['wifi_name'] : null,
                'wifi_ip' => isset($data['wifi_ip']) && isset($data['is_request_wifi']) ? $data['wifi_ip'] : null,
                'template_frames_id' => isset($data['template_frames_id']) ? $data['template_frames_id'] : null,
                'template_font_id' => isset($data['template_font_id']) ? $data['template_font_id'] : null,
                'template_content' => isset($data['template_content']) ? $data['template_content'] : null,
                'template_color' => isset($data['template_color']) ? $data['template_color'] : null,
                'template_logo' => isset($data['template_logo']) ? $data['template_logo'] : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];

            $arCodeTemplateId = $mQRCodeTemlate->insertTemplate($dataTmp);

            $mQrCode = app()->get(FNBQrCodeTable::class);
            $mQrTable = app()->get(FNBTableTable::class);

            $dataQrCode = [];
            if ($data['apply_for'] == 'all'){
//                $dataQrCode = [
//                    'qr_code_template_id' => $arCodeTemplateId,
//                    'created_at' => Carbon::now(),
//                    'code' => $this->generateCode()
//                ];
                $listTable = $mQrTable->getAll();

                foreach ($listTable as $item){
                    $code = $this->generateCode();
                    $dataQrCode[] = [
                        'qr_code_template_id' => $arCodeTemplateId,
                        'created_at' => Carbon::now(),
                        'table_id' => $item['table_id'],
                        'code' => $code,
                        'url' => $config['value'].$code
                    ];
                }
            } else {

                $listTable = $mQrTable->getAll([
                    'apply_arear_id' => $dataTmp['apply_arear_id'],
                    'apply_table_id' => $dataTmp['apply_table_id']
                ]);

                foreach ($listTable as $item){
                    $code = $this->generateCode();
                    $dataQrCode[] = [
                        'qr_code_template_id' => $arCodeTemplateId,
                        'created_at' => Carbon::now(),
                        'table_id' => $item['table_id'],
                        'code' => $code,
                        'url' => $config['value'].$code
                    ];
                }
            }

//            Thêm mã QR Code cho mỗi bàn
            if (count($dataQrCode) != 0){
                $mQrCode->insertQrCodeTable($dataQrCode);
            }

            return  [
                'error' => false,
                'message' => __('Tạo mã QR Code thành công')
            ];
        }catch (Exception $e){
            return  [
                'error' => true,
                'message' => __('Tạo mã QR Code thất bại'),
                '__message' => $e->getLine()
            ];
        }
    }

    public function generateCode() {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;

    }

    public function getClientIp()
    {
        try {
            $ipaddress = '';
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';
            return [
                'error' => false,
                'ip' => $ipaddress,
                'message' => __('Lấy IP thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lấy IP thất bại')
            ];
        }
    }

    /**
     * Lấy chi tiết qr code
     * @param $id
     * @return mixed|void
     */
    public function getDetail($id)
    {


        $mQRCodeTemlate = app()->get(FNBQrCodeTemplateTable::class);
        return $mQRCodeTemlate->getDetail($id);
    }

    /**
     * Xuất dữ liệu
     * @param $data
     * @return mixed|void
     */
    public function export($data)
    {
        $typeQR = $data['typeQR'];
        $status = $data['status'];
        $route = $data['route'];

        unset($data['typeQR']);
        unset($data['status']);
        unset($data['route']);

        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);

        $listConfigStaff = $rConfigColumn->getAllConfigStaff(Auth::id(),$route);

        if (count($listConfigStaff) != 0){
            $listConfigStaff = collect($listConfigStaff)->groupBy('type');
        }

        $heading = [];
        if (isset($listConfigStaff['show']) > 0){
            foreach ($listConfigStaff['show'] as $item){
                $heading[] = $item[getValueByLang('column_nameConfig_')];
            }
        }

        $mQRCodeTemlate = app()->get(FNBQrCodeTemplateTable::class);
        $list = $mQRCodeTemlate->getListExport($data);

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $listData = [];

        if (isset($listConfigStaff['show']) > 0) {
            foreach ($list as $key => $item) {
                foreach ($listConfigStaff['show'] as $itemValue){
                    if($itemValue['column_name'] == 'is_active'){
                        $listData[$key][$itemValue['column_name']] = $item[$itemValue['column_name']] == 1 ? __('Đang hoạt động') : __('Ngừng hoạt động');
                    }else if(in_array($itemValue['column_name'],['created_at','updated_at'])){
                        $listData[$key][$itemValue['column_name']] = isset($item[$itemValue['column_name']]) ? \Carbon\Carbon::parse($item[$itemValue['column_name']])->format('H:i:s d/m/Y') : '';
                    }else if(in_array($itemValue['column_name'],['exprire_date'])){
                        $listData[$key][$itemValue['column_name']] = (isset($item['expire_start']) ? \Carbon\Carbon::parse($item['expire_start'])->format('H:i d/m/Y') : '').' - '.(isset($item['expire_end']) ? \Carbon\Carbon::parse($item['expire_end'])->format('H:i d/m/Y') : '');
                    }else if(in_array($itemValue['column_name'],['qr_type'])) {
                        $listData[$key][$itemValue['column_name']] = $typeQR[$item[$itemValue['column_name']]];
                    }else if(in_array($itemValue['column_name'],['is_request_wifi','is_request_location'])){
                        $listData[$key][$itemValue['column_name']] = $item[$itemValue['column_name']] == 1 ? __('Đang hoạt động') :__('Không hoạt động');
                    }else if(in_array($itemValue['column_name'],['status'])){
                        $listData[$key][$itemValue['column_name']] = $status[$item[$itemValue['column_name']]]['name'];
                    }else if(in_array($itemValue['column_name'],['apply_for'])){
                        $listData[$key][$itemValue['column_name']] = $item[$itemValue['column_name']] == 'custom' ? __('Tùy chỉnh') : __('Tất cả các bàn');
                    }else {
                        $listData[$key][$itemValue['column_name']] = $item[$itemValue['column_name']];
                    }
                }
            }
        }

        return Excel::download(new ExportFile($heading, $listData), 'qr-code.xlsx');
    }

    /**
     * Render view qr code
     * @param $data
     * @return mixed|void
     */
    public function viewQrCode($data)
    {
        try {

//            Chi tiết frames
            $mFrames = app()->get(FNBQrTemplateFramesTable::class);

            $detailFrame = $mFrames->getDetail($data['frame_id']);
            if ($data['frame_id'] == 1) {
                $detailFrame['image'] = '';
            }

            $text = $data['text'];
            $color = $data['color'];
            $logo = $data['logo'];
            $font = isset($data['font']) ? $data['font'] : null;

            $rConfig = app()->get(ConfigRepositoryInterface::class);
            $config = $rConfig->getInfoByKey('url_qr_code');

            $view = view('fnb::qr-code.append.append-qr-code',[
                'detailFrame' => $detailFrame,
                'text' => $text,
                'color' => $color,
                'logo' => $logo,
                'font' => $font,
                'config' => $config
            ])->render();

            return [
                'error' => false,
                'view' => $view,
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Xuất QR Code thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    public function preview($data)
    {
        $rConfig = app()->get(ConfigRepositoryInterface::class);
        $config = $rConfig->getInfoByKey('url_qr_code');
        if (!isset($data['qr_code_template_id'])){
            //            Chi tiết frames
            $mFrames = app()->get(FNBQrTemplateFramesTable::class);
            $detailFrame = $mFrames->getDetail($data['frame_id']);
            if ($data['frame_id'] == 1) {
                $detailFrame['image'] = '';
            }

            $text = $data['text'];
            $color = isset($data['color']) ? '#'.$data['color'] : '';
            $logo = $data['logo'];
            $font = isset($data['font']) ? $data['font'] : null;



            return [
                'detailFrame' => $detailFrame,
                'text' => $text,
                'color' => $color,
                'logo' => $logo,
                'font' => $font,
                'page' => 'created',
                'config' => $config
            ];
        } else {
            $rQrCodeScan = app()->get(FNBQrCodeScanRepositoryInterface::class);
            $detail = $this->getDetail($data['qr_code_template_id']);

            $listQr = $this->getListQrCode($data['qr_code_template_id']);

            return [
                'detail' => $detail,
                'listQr' => $listQr,
                'page' => 'detail',
                'config' => $config
            ];
        }

    }

    /**
     * Lấy danh sách QR theo bàn
     * @param $idQrTemplate
     * @return mixed|void
     */
    public function getListQrCode($idQrTemplate)
    {
        $mQrCode = app()->get(FNBQrCodeTable::class);
        return $mQrCode->getListQrCode($idQrTemplate);
    }

    /**
     * Xóa template
     * @param $idQrTemplate
     * @return mixed|void
     */
    public function remove($idQrTemplate)
    {
        try {

            $mCodeTemplate = app()->get(FNBQrCodeTemplateTable::class);
            $mQrCode = app()->get(FNBQrCodeTable::class);

//            Xóa template
            $mCodeTemplate->removeTemplate($idQrTemplate);

//            Xóa table của template
            $mQrCode->removeTableByTemplate($idQrTemplate);

            return [
                'error' => false,
                'message'=> __('Xóa Qr Code thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message'=> __('Xóa Qr Code thất bại')
            ];
        }
    }

    /**
     * Cập nhật trạng thái
     * @param $data
     * @return mixed|void
     */
    public function update($data)
    {
        try {

            $dataTmp = [
                'status' => $data['status'],
                'is_request_location' => isset($data['is_request_location']) ? 1 : 0,
                'is_request_wifi' => isset($data['is_request_wifi']) ? 1 : 0,
                'location_lat' => isset($data['location_lat']) && isset($data['is_request_location']) ? $data['location_lat'] : null ,
                'location_lng' => isset($data['location_lng']) && isset($data['is_request_location']) ? $data['location_lat'] : null,
                'location_radius' => isset($data['location_radius']) && isset($data['is_request_location']) ? $data['location_radius'] : null,
                'wifi_name' => isset($data['location_lat']) && isset($data['is_request_wifi']) ? $data['wifi_name'] : null,
                'wifi_ip' => isset($data['wifi_ip']) && isset($data['is_request_wifi']) ? $data['wifi_ip'] : null,
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now()
            ];

            $qr_code_template_id = $data['qr_code_template_id'];
            $status = $data['status'];

            $mCodeTemplate = app()->get(FNBQrCodeTemplateTable::class);

//            Xóa template
            $mCodeTemplate->updateTemplate($dataTmp,$qr_code_template_id);

            return [
                'error' => false,
                'message'=> __('Cập nhật thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message'=> __('Cập nhật thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách table theo template
     * @param $idCodeTemplate
     * @return mixed|void
     */
    public function getListTableByTemplate($idCodeTemplate)
    {
        $mQrCode = app()->get(FNBQrCodeTable::class);
        return $mQrCode->getListTableByTemplate($idCodeTemplate);
    }

    /**
     * Upload hình ảnh
     *
     * @param $input
     * @return mixed|void
     */
    public function uploadImage($input)
    {
        $mTemplateLogo = app()->get(FNBQrTemplateLogoTable::class);
        if ($input['file'] != null) {
            $fileName = $this->uploadImageS3($input['file'], $input['link']);

//            insert logo
            $idLogo = $mTemplateLogo->insertLogo([
                'name' => 'Logo',
                'image' => $fileName,
                'is_active' => 1,
                'created_at' => Carbon::now()
            ]);

            $view = view('fnb::qr-code.append.append-logo',[
                'id' => $idLogo,
                'link' => $fileName
            ])->render();

            return [
                'error' => 0,
                'file' => $fileName,
                'view' => $view
            ];
        }
    }

    /**
     * Lưu ảnh vào folder temp
     *
     * @param $file
     * @param $link
     * @return string
     */
    private function uploadImageS3($file, $link)
    {
        $s3 = app()->get(S3UploadsRedirect::class);
        $time = Carbon::now();
//        $idTenant = "ed5fdecf0930c60d4dc30c103d826071";
        $idTenant = session()->get('idTenant');

        $to = $idTenant . '/' . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';

        $file_name =
            str_random(5) .
            rand(0, 9) .
            time() .
            date_format($time, 'd') .
            date_format($time, 'm') .
            date_format($time, 'Y') .
            $link .
            $file->getClientOriginalExtension();

        Storage::disk('public')->put( $to . $file_name, file_get_contents($file), 'public');

        //Lấy real path trên s3
        return $s3->getRealPath($to. $file_name);
    }
}
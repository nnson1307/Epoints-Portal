<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 31/3/2019
 * Time: 14:52
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\ConfigPrintServiceCard\ConfigPrintServiceCardRepositoryInterface;
use Modules\Admin\Repositories\SpaInfo\SpaInfoRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ConfigPrintServiceCardController extends Controller
{
    protected $config_print_service_card;
    protected $branch;
    protected $staff;
    protected $spa_info;

    public function __construct(ConfigPrintServiceCardRepositoryInterface $config_print_service_card,
                                BranchRepositoryInterface $branches,
                                StaffRepositoryInterface $staffs,
                                SpaInfoRepositoryInterface $spa_info)
    {
        $this->config_print_service_card = $config_print_service_card;
        $this->branch=$branches;
        $this->staff=$staffs;
        $this->spa_info=$spa_info;
    }

    public function indexAction()
    {
        $list = $this->config_print_service_card->list();
        $staff=$this->staff->getItem(Auth::id());
        $branch=$this->branch->getItem($staff['branch_id']);
        $qrCode = QrCode::size(60)->generate('test qr code');
        $spa_info=$this->spa_info->getItem(1);
        return view('admin::config-print-service-card.index', [
            'LIST' => $list,
            'branch'=>$branch,
            'qrCode' => $qrCode,
            'spa_info'=>$spa_info,
            'type'=>'load'
        ]);
    }

    public function listAction(Request $request)
    {
        $list = $this->config_print_service_card->list();
        $staff=$this->staff->getItem(Auth::id());
        $branch=$this->branch->getItem($staff['branch_id']);
        $qrCode = QrCode::size(60)->generate('test qr code');
        $spa_info=$this->spa_info->getItem(1);
        return view('admin::config-print-service-card.list', [
            'LIST' => $list,
            'branch'=>$branch,
            'qrCode' => $qrCode,
            'spa_info'=>$spa_info,
            'type'=>'load'
        ]);
    }

    public function uploadLogoAction(Request $request)
    {

        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
            $data['logo'] = $this->moveLogo($file, str_replace('', '', $file));
            $this->config_print_service_card->edit($data, $request->id);
            return response()->json(["file" => $file, "success" => "1"]);
        }

    }

    private function uploadImageTemp($file)
    {
        $time = Carbon::now();
        $file_name = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_config." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH. "/" .$file_name, file_get_contents($file));
        return $file_name;

    }

    private function moveLogo($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = CONFIG_SERVICE_CARD . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(CONFIG_SERVICE_CARD . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    public function removeLogoAction(Request $request)
    {
        $data = [
            'logo' => null,
            'updated_by' => Auth::id()
        ];
        $this->config_print_service_card->edit($data, $request->id);
    }

    public function submitEditAction(Request $request)
    {
        $data = [
            'name_spa' => $request->name_spa,
            'background' => $request->background,
            'color' => $request->color,
            'logo' => $request->logo,
            'background_image' => $request->background_image,
            'updated_by' => Auth::id()
        ];
        $this->config_print_service_card->edit($data, $request->id);
        return response()->json([
            'success' => 1,
            'message' => 'Lưu thông tin thành công'
        ]);
    }

    public function changeStatusQrCodeAction(Request $request)
    {
        $data = [
            'qr_code' => $request->qr_code,
            'updated_by' => Auth::id()
        ];
        $this->config_print_service_card->edit($data, $request->id);
    }

    public function uploadBackgroundAction(Request $request)
    {

        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
            $data['background_image'] = $this->moveLogo($file, str_replace('', '', $file));
            $this->config_print_service_card->edit($data, $request->id);
            return response()->json(["file" => $file, "success" => "1"]);
        }

    }

    public function removeBackgroundAction(Request $request)
    {
        $data = [
            'background_image' => null,
            'updated_by' => Auth::id()
        ];
        $this->config_print_service_card->edit($data, $request->id);
    }

    public function viewAfterAction(Request $request)
    {
        $config_print = $this->config_print_service_card->getItem($request->id);
        $staff=$this->staff->getItem(Auth::id());
        $branch=$this->branch->getItem($staff['branch_id']);
        $qrCode = QrCode::size(60)->generate('test qr code');
        $spa_info=$this->spa_info->getItem(1);

        $view = view('admin::config-print-service-card.view-before', [
            'print'=>$config_print,
            'name_spa'=>$request->name_spa,
            'branch'=>$branch,
            'color'=>$request->color,
            'background'=>$request->background,
            'qrCode' => $qrCode,
            'spa_info'=>$spa_info,
            'type'=>'click'
        ])->render();
        return $view;
    }

}
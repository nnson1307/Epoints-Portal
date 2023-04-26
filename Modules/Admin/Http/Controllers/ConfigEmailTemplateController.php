<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 1/4/2019
 * Time: 12:04
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Repositories\ConfigEmailTemplate\ConfigEmailTemplateRepositoryInterface;
use Modules\Admin\Repositories\EmailProvider\EmailProviderRepositoryInterface;
use Modules\Admin\Repositories\SpaInfo\SpaInfoRepositoryInterface;

class ConfigEmailTemplateController extends Controller
{
    protected $config_email_template;
    protected $spa_info;
    protected $email_provider;

    public function __construct(ConfigEmailTemplateRepositoryInterface $config_email_template,
                                SpaInfoRepositoryInterface $spa_info,
                                EmailProviderRepositoryInterface $email_provider)
    {
        $this->config_email_template = $config_email_template;
        $this->spa_info = $spa_info;
        $this->email_provider = $email_provider;
    }

    public function indexAction()
    {
        $list = $this->config_email_template->list();
        return view('admin::config-email-template.index', [
            'LIST' => $list
        ]);
    }

    public function listAction(Request $request)
    {
        $list = $this->config_email_template->list();
        return view('admin::config-email-template.list', [
            'LIST' => $list
        ]);
    }

    public function uploadAction(Request $request)
    {
        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
            $data = [
                'image' => $this->moveImage($file, str_replace('', '', $file)),
                'updated_by' => Auth::id()
            ];
            $this->config_email_template->edit($data, $request->id);
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

    private function moveImage($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = CONFIG_EMAIL_TEMPLATE . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(CONFIG_EMAIL_TEMPLATE . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    public function removeImage(Request $request)
    {
        $data = [
            'image' => null,
            'updated_by' => Auth::id()
        ];
        $this->config_email_template->edit($data, $request->id);
    }

    public function submitEditAction(Request $request)
    {
        $data = [
            'background_header' => $request->background_header,
            'color_header' => $request->color_header,
            'background_body' => $request->background_body,
            'color_body' => $request->color_body,
            'background_footer' => $request->background_footer,
            'color_footer' => $request->color_footer,
            'image' => $request->image
        ];
        $this->config_email_template->edit($data, $request->id);
        return response()->json([
            'success' => 1,
            'message' => 'Lưu thông tin thành công'
        ]);
    }

    public function changeStatusLogoAction(Request $request)
    {
        $data = [
            'logo' => $request->logo,
            'updated_by' => Auth::id()
        ];
        $this->config_email_template->edit($data, $request->id);
    }

    public function changeStatusWebsiteAction(Request $request)
    {
        $data = [
            'website' => $request->website,
            'updated_by' => Auth::id()
        ];
        $this->config_email_template->edit($data, $request->id);
    }

    public function viewAction(Request $request)
    {
        $config_template = DB::table('config_email_template')
            ->select('id', 'logo', 'website', 'background_header', 'color_header',
                'background_body', 'color_body', 'background_footer', 'color_footer', 'image')
            ->where('id', 1)->first();
        $spa_info = DB::table('spa_info')->leftJoin('province', 'province.provinceid', '=', 'spa_info.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'spa_info.districtid')
            ->select('spa_info.id',
                'spa_info.name',
                'spa_info.code',
                'spa_info.phone',
                'spa_info.is_actived',
                'spa_info.is_deleted',
                'spa_info.email',
                'spa_info.hot_line',
                'spa_info.provinceid',
                'spa_info.districtid',
                'spa_info.address',
                'spa_info.slogan',
                'spa_info.bussiness_id',
                'spa_info.logo',
                'spa_info.fanpage',
                'spa_info.zalo',
                'spa_info.instagram_page',
                'province.type as province_type',
                'province.name as province_name',
                'district.type as district_type',
                'district.name as district_name')
            ->where('spa_info.id', 1)->first();
        $email_provider = $this->email_provider->getItem(1);

        $view = view('admin::config-email-template.modal-view', [
            'title' => 'Mua hàng thành công',
            'config_template' => $config_template,
            'spa_info' => $spa_info,
            'type' => 'test',
            'content' => 'Hello',
            'email_provider' => $email_provider,
            'background_header' => $request->background_header,
            'color_header' => $request->color_header,
            'background_body' => $request->background_body,
            'color_body' => $request->color_body,
            'background_footer' => $request->background_footer,
            'color_footer' => $request->color_footer
        ])->render();


        return $view;
    }
}
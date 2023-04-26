<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 3/16/2019
 * Time: 12:25 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Repositories\BannerSlider\BannerSliderRepositoryInterface;
use Modules\Admin\Repositories\BookingExtra\BookingExtraRepositoryInterface;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Bussiness\BussinessRepositoryInterface;
use Modules\Admin\Repositories\Config\ConfigRepoInterface;
use Modules\Admin\Repositories\District\DistrictRepositoryInterface;
use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;
use Modules\Admin\Repositories\RuleBooking\RuleBookingRepositoryInterface;
use Modules\Admin\Repositories\RuleMenu\RuleMenuRepositoryInterface;
use Modules\Admin\Repositories\RuleSettingOther\RuleSettingOtherRepositoryInterface;
use Modules\Admin\Repositories\SpaInfo\SpaInfoRepositoryInterface;
use Modules\Admin\Repositories\TimeWorking\TimeWorkingRepositoryInterface;
use Monolog\Handler\IFTTTHandler;

class ConfigController extends Controller
{
    protected $spa_info;
    protected $province;
    protected $district;
    protected $bussiness;
    protected $banner_slider;
    protected $time_working;
    protected $rule_menu;
    protected $rule_booking;
    protected $rule_setting_other;
    protected $booking_extra;
    protected $branch;
    protected $config;

    public function __construct(SpaInfoRepositoryInterface $spa_info,
                                ProvinceRepositoryInterface $provinces,
                                DistrictRepositoryInterface $districts,
                                BussinessRepositoryInterface $bussiness,
                                BannerSliderRepositoryInterface $banner_sliders,
                                TimeWorkingRepositoryInterface $time_working,
                                RuleMenuRepositoryInterface $rule_menu,
                                RuleBookingRepositoryInterface $rule_booking,
                                RuleSettingOtherRepositoryInterface $rule_setting_other,
                                BookingExtraRepositoryInterface $booking_extra,
                                BranchRepositoryInterface $branch,
                                ConfigRepoInterface $config)
    {
        $this->spa_info = $spa_info;
        $this->province = $provinces;
        $this->district = $districts;
        $this->bussiness = $bussiness;
        $this->banner_slider = $banner_sliders;
        $this->time_working = $time_working;
        $this->rule_menu = $rule_menu;
        $this->rule_booking = $rule_booking;
        $this->rule_setting_other = $rule_setting_other;
        $this->booking_extra = $booking_extra;
        $this->branch = $branch;
        $this->config = $config;
    }

    public function indexAction()
    {
        $item_info=null;
        $optionProvince = $this->province->getOptionProvince();
        $optionBussiness = $this->bussiness->getBussinessOption();
        $spa_info = $this->spa_info->getItem();
        $introduction = $this->spa_info->getIntroduction();

        foreach ($introduction as $value)
        {
            $id_introduction = $value['id'];
            $spa_introduction = $value['introduction'];
        }
        if(count($spa_info)>0)
        {
            $item_info=$spa_info[0];
        }else{
            $item_info=null;
        }

        $banner = $this->banner_slider->list();
        $time = $this->time_working->list();
        $rule_menu = $this->rule_menu->list();
        $rule_booking = $this->rule_booking->list();
        $rule_setting_other = $this->rule_setting_other->list();
        $booking_extra = $this->booking_extra->list();

        $optionBranch = $this->branch->getBranchOption();

        return view('admin::config-page-appointment.index', [
//            'LIST' => $info,
            'FILTER' => $this->filters(),
            'LIST_BANNER' => $banner,
            'LIST_TIME' => $time,
            'LIST_MENU' => $rule_menu,
            'LIST_BOOKING' => $rule_booking,
            'LIST_SETTING_OTHER' => $rule_setting_other,
            'LIST_BOOKING_EXTRA' => $booking_extra,
            'optionProvince' => $optionProvince,
            'optionBussiness' => $optionBussiness,
            'item' => $item_info,
            'introduction' => $spa_introduction,
            'id_introduction' => $id_introduction,
            'optionBranch' => $optionBranch
        ]);
    }

    protected function filters()
    {
        return [
            'spa_info$is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]

        ];
    }

    public function listInfoAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'spa_info$is_actived', 'search_info']);
        $info = $this->spa_info->list($filter);
        return view('admin::config-page-appointment.spa-info.list', [
            'LIST' => $info,
            'page' => $filter['page']
        ]);
    }

    public function addInfoAction()
    {
        $optionProvince = $this->province->getOptionProvince();
        $optionBussiness = $this->bussiness->getBussinessOption();
        return view('admin::config-page-appointment.spa-info.add', [
            'optionProvince' => $optionProvince,
            'optionBussiness' => $optionBussiness
        ]);
    }

    public function uploadAction(Request $request)
    {
        $this->validate($request, [
            "logo" => "mimes:jpg,jpeg,png,gif|max:10000"
        ], [
            "logo.mimes" => __("File này không phải file hình"),
            "logo.max" => __("File quá lớn")
        ]);
        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
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

    private function transferTempfileToAdminfile($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = SPA_INFO_UPLOADS_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(SPA_INFO_UPLOADS_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    public function submitAddInfoAction(Request $request)
    {
        $test = $this->spa_info->testName($request->name, '0');
        if ($test['name'] == '') {
            $data = [
                'name' => $request->name,
                'code' => $request->code,
                'phone' => $request->phone,
                'email' => $request->email,
                'hot_line' => $request->hot_line,
                'fanpage' => $request->fanpage,
                'zalo' => $request->zalo,
                'instagram_page' => $request->instagram_page,
                'provinceid' => $request->provinceid,
                'districtid' => $request->districtid,
                'address' => $request->address,
                'slogan' => $request->slogan,
                'bussiness_id' => $request->bussiness_id,
                'created_by' => Auth::id()
            ];
            if ($request->logo != null) {
                $data['logo'] = $this->transferTempfileToAdminfile($request->logo, str_replace('', '', $request->logo));
            }
            $this->spa_info->add($data);
            return response()->json([
                'success' => 1,
                'message' => __('Thêm đơn vị kinh doanh thành công'),
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => __('Thêm đơn vị kinh doanh thất bại'),
            ]);
        }


    }

//    public function editInfoAction($id)
//    {
//        $optionProvince = $this->province->getOptionProvince();
//        $optionBussiness = $this->bussiness->getBussinessOption();
//        $item = $this->spa_info->getItem($id);
//        return view('admin::config-page-appointment.spa-info.edit', [
//            'optionProvince' => $optionProvince,
//            'optionBussiness' => $optionBussiness,
//            'item' => $item
//        ]);
//    }

    public function submitEditInfoAction(Request $request)
    {
        $data = [
            'name' => $request->name,
            'code' => $request->code,
            'phone' => $request->phone,
            'email' => $request->email,
            'hot_line' => $request->hot_line,
            'fanpage' => $request->fanpage,
            'zalo' => $request->zalo,
            'instagram_page' => $request->instagram_page,
            'provinceid' => $request->provinceid,
            'districtid' => $request->districtid,
            'address' => $request->address,
            'slogan' => $request->slogan,
            'bussiness_id' => $request->bussiness_id,
            'is_part_paid' => $request->is_part_paid,
            'branch_apply_order' => $request->branch_apply_order,
            'total_booking_time' => $request->total_booking_time,
            'updated_by' => Auth::id()
        ];
        if ($request->logo != null) {
            $data['logo'] = $request->logo;
        } else {
            $data['logo'] = $request->logo_edit;
        }
        if($request->id!=null)
        {
            $this->spa_info->edit($data, $request->id);
        }else{
            $this->spa_info->add($data);
        }

        return response()->json([
            'success' => 1,
            'message' => __('Cập nhật đơn vị kinh doanh thành công'),
        ]);

    }

    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $data['updated_by'] = Auth::id();
        $this->spa_info->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function removeInfoAction($id)
    {
        $this->spa_info->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function listBannerAction(Request $request)
    {
        $banner = $this->banner_slider->list();
        return view('admin::config-page-appointment.banner-slider.list', [
            'LIST_BANNER' => $banner
        ]);
    }

    public function submitAddBannerAction(Request $request)
    {
        $data = [
            'link' => $request->link,
            'position' => $request->position,
        ];
        if ($request->banner_img != '') {
            $data['name'] = $request->banner_img;
        }
        $this->banner_slider->add($data);
        return response()->json([
            'success' => 1,
            'message' => __('Thêm banner thành công')
        ]);

    }

    private function saveImageBannerUpload($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = BANNER_UPLOADS_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(BANNER_UPLOADS_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    public function editBannerAction(Request $request)
    {
        $id = $request->id;
        $item = $this->banner_slider->getItem($id);
        return response()->json([
            'item' => $item
        ]);
    }

    public function submitEditBannerAction(Request $request)
    {
        $data = [
            'link' => $request->link,
            'position' => $request->position,
            'updated_by' => Auth::id()
        ];
        if ($request->banner_edit_new != '') {
            $data['name'] = $request->banner_edit_new;
        } else {
            $data['name'] = $request->banner_edit_hidden;
        }
        $this->banner_slider->edit($data, $request->id);
        return response()->json([
            'success' => 1,
            'message' => __('Cập nhật banner thành công')
        ]);
    }

    public function removeBannerAction($id)
    {
        $this->banner_slider->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function listTimeWorkingAction(Request $request)
    {
        $time = $this->time_working->list();
        return view('admin::config-page-appointment.rule.rule-menu-booking.list-booking', [
            'LIST_TIME' => $time,
        ]);
    }

    public function changeStatusTimeAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = $request->is_actived;
        $data['updated_by'] = Auth::id();
        $this->time_working->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function submitEditTimeAction(Request $request)
    {
        $id = $request->id;
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        foreach ($id as $key => $value) {
            $data = [
                'id' => $value,
                'start_time' => $start_time[$key],
                'end_time' => $end_time[$key],
                'updated_by' => Auth::id()
            ];
            $this->time_working->edit($data, $value);
        }
        return response()->json([
            'success' => 1,
            'message' => __('Cập nhật thời gian làm việc thành công')
        ]);
    }

    public function listRuleMenuAction(Request $request)
    {
        $rule_menu = $this->rule_menu->list();
        return view('admin::config-page-appointment.rule.rule-menu-booking.list-menu', [
            'LIST_MENU' => $rule_menu
        ]);
    }

    public function changeStatusMenuAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = $request->is_actived;
        $data['updated_by'] = Auth::id();
        $this->rule_menu->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function submitEditRuleMenuAction(Request $request)
    {
        $id = $request->id;
        $position = $request->position;
        foreach ($id as $key => $value) {
            $data = [
                'id' => $value,
                'position' => $position[$key],
                'updated_by' => Auth::id()
            ];
            $this->rule_menu->edit($data, $value);
        }
        return response()->json([
            'success' => 1,
            'message' => __('Cập nhật vị trí thành công')
        ]);

    }

    public function listRuleBookingAction(Request $request)
    {
        $rule_booking = $this->rule_booking->list();
        return view('admin::config-page-appointment.rule.rule-menu-booking.list-booking', [
            'LIST_BOOKING' => $rule_booking
        ]);
    }

    public function changeStatusBookingAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = $request->is_actived;
        $data['updated_by'] = Auth::id();
        $this->rule_booking->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function listRuleSettingOtherAction(Request $request)
    {
        $rule_setting_other = $this->rule_setting_other->list();
        return view('admin::config-page-appointment.rule.rule-other-booking-extra.list-rule-other', [
            'LIST_SETTING_OTHER' => $rule_setting_other
        ]);
    }

    public function changeStatusSettingOtherAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = $request->is_actived;
        $data['updated_by'] = Auth::id();
        $this->rule_setting_other->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function submitEditDayAction(Request $request)
    {
        $data = [
            'day' => $request->day,
            'updated_by' => Auth::id()
        ];
        $this->rule_setting_other->edit($data, $request->id);
        return response()->json([
            'success' => 1,
            'message' => __('Cập nhật thời gian đặt lịch thành công')
        ]);
    }

    public function listBookingExtraAction(Request $request)
    {
        $booking_extra = $this->booking_extra->list();
        return view('admin::config-page-appointment.rule.rule-other-booking-extra.list-booking-extra', [
            'LIST_BOOKING_EXTRA' => $booking_extra
        ]);
    }

    public function submitEditBookingExtraAction(Request $request)
    {
        $data = [
            'value' => $request->value,
            'updated_by' => Auth::id()
        ];
        $this->booking_extra->edit($data, $request->id);
        return response()->json([
            'success' => 1,
            'message' => ('Cập nhật thành công')
        ]);
    }

    public function uploadImgFaceBookAction(Request $request)
    {

        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
            $data['image'] = $this->transferTempfileToAdminfile($file, str_replace('', '', $file));
            $this->booking_extra->edit($data,$request->id);
            return response()->json(["file" => $file, "success" => "1"]);
        }

    }
    public function removeImageFacbookAction(Request $request)
    {
        $data['image']=null;
        $this->booking_extra->edit($data,$request->id);
    }

    public function updateIntroduction(Request $request){
        $param = $request->all();
        $data = $this->spa_info->updateIntroduction($param);
        return $data;
    }

    public function configGeneral() {
        $LIST = $this->config->getAll();

        if (isset($LIST[10])){
            $countryName = $this->config->getNameCountryIso($LIST[10]['value']);

            $LIST[10]['value'] = $countryName != null ? $countryName['country_name'] : null;
        }

        return view('admin::config.config-general.index', [
            'LIST' => $LIST
        ]);
    }

    /**
     * Chi tiết cấu hình chung
     *
     * @param $id
     * @return array
     */
    public function detailConfigGeneral($id)
    {
        $data = $this->config->dataViewEdit($id);
        return view('admin::config.config-general.detail-new', $data);
    }

    /**
     * Chỉnh sửa cấu hình chung
     *
     * @param $id
     * @return array
     */
    public function editConfigGeneral($id)
    {
        $data = $this->config->dataViewEdit($id);
        return view('admin::config.config-general.edit-new', $data);
    }

    /**
     * Cập nhật cấu hình chung
     *
     * @param Request $request
     * @return mixed
     */
    public function editPostConfigGeneral(Request $request)
    {
        $data = $request->all();
//        return $this->config->updatekey($data);
        return $this->config->updateConfigGeneral($data);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ZNS\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\ZNS\Repositories\CampaignFollower\CampaignFollowerRepositoryInterface;


class CampaignFollowerController extends Controller
{
    protected $campaignFollower;

    public function __construct(CampaignFollowerRepositoryInterface $campaignFollower)
    {
        $this->campaignFollower = $campaignFollower;
    }

    public function list(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'status', 'campaign_type', 'created_at', 'time_sent', 'created_by', 'updated_at', 'updated_by']);
        return view('zns::campaign_follower.index', $this->campaignFollower->list($filters));
    }

    public function add(Request $request)
    {
        $params = $request->all();
        return view('zns::campaign_follower.add', $this->campaignFollower->addView($params));
    }

    public function addAction(Request $request)
    {
        $rule = [
            // 'oa' => 'required',
            'zns_template_id' => 'required',
            'name' => 'required|max:191',
            'branch_id' => 'required',
        ];

        $mess = [
            // 'oa.required' => __('Vui lòng chọn OA'),
            'zns_template_id.required' => __('Vui lòng chọn chiến dịch'),
            'name.required' => __('Vui lòng nhập tên chiến dịch'),
            'name.max' => __('Tên chiến dịch không quá 191 ký tự'),
            'branch_id.required' => __('Vui lòng chọn chi nhánh'),
        ];

        // gửi Ngay check type = 1
        $error_time = false;
        $mess_err_time = [__("Thời gian chỉ gửi từ 7h tới 22h")];
        $params = $request->all();
        if ($request->check_type == 0) {
            $rule['time_send'] = 'required';
            $mess['time_send.required'] = __('Vui lòng chọn thời gian gửi');

            if($request->time_send != null){
                $time = Carbon::createFromFormat('d/m/Y H:i', $params['time_send'])->format('H');
                if (in_array($time, [00, 01, 02, 03, 04, 05, 06, 23])) {
                    $error_time = true;
                }
                $package_expire_date = $params['time_send'];
                if ($package_expire_date != null) {
                    $package_expire_date = Carbon::createFromFormat('d/m/Y H:i', $package_expire_date)->format('Y-m-d H:i:s');
                    $package_expire_date = Carbon::createFromFormat('Y-m-d H:i:s', $package_expire_date);
                    if ($package_expire_date->lt(Carbon::now())) {
                        $error_time = true;
                        $mess_err_time = [__("Thời gian gửi lớn hơn thời gian hiện tại")];
                    }
                }
            }
        }

        $validated = $request->validate($rule, $mess);

        if (isset($validated->message)) {
            return $validated->message;
        } elseif ($error_time == true) {
            return response()->json([
                "errors" => [
                    'time_send' => $mess_err_time
                ]
            ], 400);
        }elseif ($request->customer_id == null){
            return response()->json([
                "errors" => [
                    'customer_id' => [__("Vui lòng chọn khách hàng")]
                ]
            ], 400);
        }

        if ($request->check_type == 0) {
            $params['time_send'] = Carbon::createFromFormat("d/m/Y H:i", $request->time_send)->format("Y-m-d H:i:s");
        } else {
            $params['time_send'] = Carbon::now()->format("Y-m-d H:i:s");

        }
        return $this->campaignFollower->addAction($params);
    }

    public function edit($id)
    {
        $data = $this->campaignFollower->editView($id);
        if ($data == 0) {
            return redirect()->route('zns.campaign');
        }
        return view('zns::campaign_follower.edit', $data);
    }

    public function cloneView($id)
    {
        return view('zns::campaign_follower.clone', $this->campaignFollower->cloneView($id));
    }

    public function cloneAction(Request $request)
    {
        return $this->campaignFollower->cloneAction($request->id);
    }

    public function editAction(Request $request)
    {
        $id = $request->zns_campaign_follower_id;
        $rule = [
            // 'oa' => 'required',
            'zns_template_id' => 'required',
            'name' => 'required|max:191',
            'branch_id' => 'required',
        ];

        $mess = [
            // 'oa.required' => __('Vui lòng chọn OA'),
            'zns_template_id.required' => __('Vui lòng chọn chiến dịch'),
            'name.required' => __('Vui lòng nhập tên chiến dịch'),
            'name.max' => __('Tên chiến dịch không quá 191 ký tự'),
            'branch_id.required' => __('Vui lòng chọn chi nhánh'),
        ];

        // gửi Ngay check type = 1
        $error_time = false;
        $mess_err_time = [__("Thời gian chỉ gửi từ 7h tới 22h")];
        $params = $request->all();
        if ($request->check_type == 0) {
            $rule['time_send'] = 'required';
            $mess['time_send.required'] = __('Vui lòng chọn thời gian gửi');

            if($request->time_send != null){
                $time = Carbon::createFromFormat('d/m/Y H:i', $params['time_send'])->format('H');
                if (in_array($time, [00, 01, 02, 03, 04, 05, 06, 23])) {
                    $error_time = true;
                }
                $package_expire_date = $params['time_send'];
                if ($package_expire_date != null) {
                    $package_expire_date = Carbon::createFromFormat('d/m/Y H:i', $package_expire_date)->format('Y-m-d H:i:s');
                    $package_expire_date = Carbon::createFromFormat('Y-m-d H:i:s', $package_expire_date);
                    if ($package_expire_date->lt(Carbon::now())) {
                        $error_time = true;
                        $mess_err_time = [__("Thời gian gửi lớn hơn thời gian hiện tại")];
                    }
                }
            }
        }

        $validated = $request->validate($rule, $mess);
        if (isset($validated->message)) {
            return $validated->message;
        } elseif ($error_time == true) {
            return response()->json([
                "errors" => [
                    'time_send' => $mess_err_time
                ]
            ], 400);
        }elseif ($request->customer_id == null){
            return response()->json([
                "errors" => [
                    'customer_id' => [__("Vui lòng chọn khách hàng")]
                ]
            ], 400);
        }

        if ($request->check_type == 0) {
            $params['time_send'] = Carbon::createFromFormat("d/m/Y H:i", $request->time_send)->format("Y-m-d H:i:s");
        } else {
            $params['time_send'] = Carbon::now()->format("Y-m-d H:i:s");

        }
        return $this->campaignFollower->editAction($params);
    }

    public function view($id)
    {
        return view('zns::campaign_follower.view', $this->campaignFollower->editView($id, true));
    }

    public function showListCustomer(Request $request)
    {
        $params = $request->all();
        return $this->campaignFollower->showListCustomer($params);
    }

    public function confirmPopup(Request $request)
    {
        $rule = [
            // 'oa' => 'required',
            'zns_template_id' => 'required',
            'name' => 'required|max:191',
            'branch_id' => 'required',
        ];

        $mess = [
            // 'oa.required' => __('Vui lòng chọn OA'),
            'zns_template_id.required' => __('Vui lòng chọn chiến dịch'),
            'name.required' => __('Vui lòng nhập tên chiến dịch'),
            'name.max' => __('Tên chiến dịch không quá 191 ký tự'),
            'branch_id.required' => __('Vui lòng chọn chi nhánh'),
        ];

        // gửi Ngay check type = 1
        $error_time = false;
        $params = $request->all();
        if ($request->check_type == 0) {
            $rule['time_send'] = 'required';
            $mess['time_send.required'] = __('Vui lòng chọn thời gian gửi');
            if($request->time_send != null){
                $time = Carbon::createFromFormat('d/m/Y H:i', $params['time_send'])->format('H');
                $package_expire_date = $params['time_send'];
                if ($package_expire_date != null) {
                    $package_expire_date = Carbon::createFromFormat('d/m/Y H:i', $package_expire_date)->format('Y-m-d H:i:s');
                    $package_expire_date = Carbon::createFromFormat('Y-m-d H:i:s', $package_expire_date);
                    if ($package_expire_date->lt(Carbon::now())) {
                        $error_time = true;
                        $mess_err_time = [__("Thời gian gửi lớn hơn thời gian hiện tại")];
                    }
                }
            }
        }

        $validated = $request->validate($rule, $mess);

        if (isset($validated->message)) {
            return $validated->message;
        } elseif ($error_time == true) {
            return response()->json([
                "errors" => [
                    'time_send' => $mess_err_time
                ]
            ], 400);
        }elseif ($request->customer_id == null){
            return response()->json([
                "errors" => [
                    'customer_id' => [__("Vui lòng chọn khách hàng")]
                ]
            ], 400);
        }
        return [
            'sattus' => 1,
            'html' => view('zns::campaign_follower.modal.confim_send_mess', $params)->render()
        ];
    }

    public function removeAction($id)
    {
        $status = 0;
        if ($this->campaignFollower->removeAction($id)) {
            $status = 1;
        }
        return redirect()->route('zns.campaign', ['remove_status' => $status]);
    }

    public function removeCampaignAction(Request $request)
    {
        $id = $request->only('id');
        return response()->json([
            'status' => $this->campaignFollower->removeAction($id)
        ]);
    }

    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $status = 1;
        $item = $this->campaignFollower->getItem($change['id']);
        /*
         * kiểm tra thời gian hiện tại
         * phải lớn hơn thời gian gửi mới cho đổi trạng thái
         * */
        if (Carbon::now()->lte($item->time_sent)) {
            if (isset($item->is_now) && $item->is_now == 1) {
                $status = 2;
            }
            $this->campaignFollower->edit($data, $change['id']);
            return response()->json([
                'status' => $status,
                'message' => __('Cập nhật trạng thái thành công')
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => __('Thời gian gửi phải lớn hơn thời gian hiện tại')
        ]);

    }
}
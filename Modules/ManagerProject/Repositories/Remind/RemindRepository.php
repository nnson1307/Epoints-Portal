<?php


namespace Modules\ManagerProject\Repositories\Remind;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Managerproject\Http\Api\SendNotificationApi;
use Modules\ManagerProject\Models\ManageProjectHistoryTable;
use Modules\ManagerProject\Models\ManageProjectStaffTable;
use Modules\ManagerProject\Models\ManageProjectTable;
use Modules\ManagerProject\Models\ManageRedmindTable;
use Modules\ManagerProject\Models\StaffsTable;

class RemindRepository implements RemindRepositoryInterface
{
    public function showPopupRemindPopup($data){
        try {
            $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
            $listStaff = $mManageProjectStaff->getAllByProjectId($data['manage_project_id']);
            $view = view('manager-project::remind.remind-work',[
                'listStaff' => $listStaff,
                'data' => $data
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => false,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    public function addRemindWork($data)
    {
        try {
            $mManageRemind = new ManageRedmindTable();
            $mManageProject = app()->get(ManageProjectTable::class);
            if (isset($data['time_remind']) && $data['time_remind'] == 'selected') {
                unset($data['time_remind']);
            }
            if (isset($data['time_remind'])) {
                $data['time_remind'] = str_replace(',', '', $data['time_remind']);
            }

            $mStaff = app()->get(StaffsTable::class);

            if (!isset($data['staff'])) {
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn nhân viên')
                ];
            }

            $dataRemind = [];
            foreach ($data['staff'] as $item) {
                if (isset($data['manage_remind_id'])) {
                    $dataRemind = [
                        'date_remind' => Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->format('Y-m-d H:i:00'),
                        'time' => isset($data['time_remind']) ? $data['time_remind'] : null,
                        'time_type' => isset($data['time_remind']) ? $data['time_type_remind'] : null,
                        'description' => strip_tags($data['description_remind']),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                } else {

                    $created_by = $mStaff->getStaffId(Auth::id());
                    $staff_id = $mStaff->getStaffId($item);
                    if (isset($data['popup_manage_project_id'])) {
                        $detailProject = $mManageProject->getDetail($data['popup_manage_project_id']);
                        $title = $created_by['staff_name'] . ' ' . __('tạo nhắc nhở cho dự án :manage_project_title', ['manage_project_title' => $detailProject['manage_project_name']]) . ' ' . $staff_id['staff_name'];
                    } else {
                        $title = $created_by['staff_name'] . ' ' . __('tạo nhắc nhở dự án cho') . ' ' . $staff_id['staff_name'];
                    }

                    $dataRemind[] = [
                        'title' => isset($data['title']) ? $data['title'] : $title,
                        'staff_id' => $item,
                        'manage_project_id' => $data['popup_manage_project_id'],
                        'date_remind' => Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->format('Y-m-d H:i:00'),
                        'time' => isset($data['time_remind']) ? $data['time_remind'] : null,
                        'time_type' => isset($data['time_remind']) ? $data['time_type_remind'] : null,
                        'description' => strip_tags($data['description_remind']),
                        'is_sent' => 0,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }
            }

            $mManageProjectHistory = app()->get(ManageProjectHistoryTable::class);
            if (isset($data['manage_remind_id'])) {
                $mManageRemind->updateRemind($dataRemind, $data['manage_remind_id']);

                $dataHistory = [
                    'manage_project_id' => $data['popup_manage_project_id'],
                    'staff_id' => Auth::id(),
                    'message' => __(' đã cập nhật thành công nhắc nhở'),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $mManageProjectHistory->addHistory($dataHistory);
            } else {
                if (count($dataRemind) != 0) {
                    $idRemind = $mManageRemind->insertRemind($dataRemind[0]);
                    unset($dataRemind[0]);
                    if (count($dataRemind) != 0) {
                        $mManageRemind->insertArrayRemind($dataRemind);
                    }
                }

//                $sendNoti = new SendNotificationApi();
//
//                $dataNoti = [
//                    'key' => 'project_remind',
//                    'object_id' => $idRemind,
//                    'manage_project_id' => $data['popup_manage_project_id'],
//                    'staff_id' => Auth::id(), //Nhân viên tạo nhắc nhở
//                    'type_module' => 'project'
//                ];
//                $sendNoti->sendStaffNotification($dataNoti);

                $dataHistory = [
                    'manage_project_id' => $data['popup_manage_project_id'],
                    'staff_id' => Auth::id(),
                    'message' => __(' đã tạo nhắc nhở thành công'),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $mManageProjectHistory->addHistory($dataHistory);
            }

            return [
                'error' => false,
                'message' => __('Lưu nhắc nhở thành công'),
//                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lưu nhắc nhở thất bại ' . $e->getMessage())
            ];
        }
    }
}
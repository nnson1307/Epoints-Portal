<?php

namespace Modules\Admin\Repositories\StaffCommission;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\OrderCommissionTable;
use Modules\Admin\Models\StaffCommissionLogTable;
use Modules\Admin\Models\StaffTable;

class StaffCommissionRepo implements StaffCommissionRepoInterface
{
    protected $staffCommission;
    public function __construct(OrderCommissionTable $orderCommission) {
        $this->staffCommission = $orderCommission;
    }
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    public function getList(array $filters = [])
    {
        $list = $this->staffCommission->getList($filters);
        return [
            "list" => $list,
        ];
    }

    /**
     * Popup thêm hoa hồng nhân viên
     *
     * @param $input
     * @return array
     */
    public function dataViewCreate($input)
    {
        $mStaff = new StaffTable();
        $optionStaff = $mStaff->getOption();
        $html = \View::make('admin::staff-commission.popup-create', [
            'optionStaff' => $optionStaff,
            'staffAvailable' => isset($input['staff_available']) ? $input['staff_available'] : ''
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Thêm hoa hồng nhân viên
     *
     * @param $input
     * @return array
     */
    public function store($input)
    {
        try {
            $mStaff = new StaffTable();
            $staffInfo = $mStaff->getDetail($input['staff_id']);
            $dataInsert = [
                'staff_id' => (int)$input['staff_id'],
                'staff_money' => str_replace(',', '', $input['staff_money']),
                'staff_commission_rate' => $staffInfo != null ? $staffInfo['commission_rate'] : null,
                'note' => isset($input['note']) ? $input['note'] : '',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->staffCommission->add($dataInsert);

            return [
                'error' => false,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại')
            ];
        }
    }

    /**
     * popup view chỉnh sửa hoa hồng nhân viên
     *
     * @param $input
     * @return array
     */
    public function dataViewEdit($input)
    {
        $mStaff = new StaffTable();
        $optionStaff = $mStaff->getOption();
        $staffCommissionInfo = $this->staffCommission->getDetail($input['id']);
        $html = \View::make('admin::staff-commission.popup-edit', [
            'optionStaff' => $optionStaff,
            'item' => $staffCommissionInfo
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Cập nhật hoa hồng nhân viên
     *
     * @param $input
     * @return array
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mStaff = new StaffTable();
            $mStaffCommissionLog = new StaffCommissionLogTable();
            if (isset($input['id']) && $input['id'] != null) {
                // Lấy thông tin trước khi chỉnh sửa, lưu log
                $commissionInfo = $this->staffCommission->getDetail($input['id']);
                $content = '';
                if ($commissionInfo['staff_id'] != $input['staff_id'] && $commissionInfo['staff_money'] != $input['staff_money']) {
                    $content = 'Cập nhật nhân viên và tiền';
                } elseif ($commissionInfo['staff_id'] == $input['staff_id'] && $commissionInfo['staff_money'] != $input['staff_money']) {
                    $content = 'Cập nhật tiền';
                } elseif ($commissionInfo['staff_id'] != $input['staff_id'] && $commissionInfo['staff_money'] == $input['staff_money']) {
                    $content = 'Cập nhật nhân viên';
                }
                $dataLog = [
                    'order_commission_id' => $input['id'],
                    'action_type' => self::ACTION_UPDATE,
                    'content' => $content,
                    'staff_id' => $commissionInfo['staff_id'],
                    'staff_money' => $commissionInfo['staff_money'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id()
                ];
                $mStaffCommissionLog->add($dataLog);
                // Update info
                $staffInfo = $mStaff->getDetail($input['staff_id']);
                $dataUpdate = [
                    'staff_id' => (int)$input['staff_id'],
                    'staff_money' => str_replace(',', '', $input['staff_money']),
                    'staff_commission_rate' => $staffInfo != null ? $staffInfo['commission_rate'] : null,
                    'updated_by' => Auth::id(),
                    'note' => isset($input['note']) ? $input['note'] : ''
                ];
                $this->staffCommission->edit($dataUpdate, $input['id']);
            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }

    /**
     * Xoá commission
     *
     * @param $input
     * @return array
     */
    public function delete($input)
    {
        DB::beginTransaction();
        try {
            $mStaffCommissionLog = new StaffCommissionLogTable();
            if (isset($input['id']) && $input['id'] != null) {
                // Lấy thông tin trước khi chỉnh sửa, lưu log
                $commissionInfo = $this->staffCommission->getDetail($input['id']);
                $content = 'Xoá commission';
                $dataLog = [
                    'order_commission_id' => $input['id'],
                    'action_type' => self::ACTION_DELETE,
                    'content' => $content,
                    'staff_id' => $commissionInfo['staff_id'],
                    'staff_money' => $commissionInfo['staff_money'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id()
                ];
                $mStaffCommissionLog->add($dataLog);
//                $this->staffCommission->deleteCommission($input['id']);
                // cập nhật staff_money = 0
                $this->staffCommission->edit(['staff_money' => 0], $input['id']);
            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('Xoá thành công')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Xoá thất bại')
            ];
        }
    }
}
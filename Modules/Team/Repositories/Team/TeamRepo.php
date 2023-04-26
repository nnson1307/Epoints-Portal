<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/07/2022
 * Time: 15:03
 */

namespace Modules\Team\Repositories\Team;


use Modules\Team\Models\StaffTable;
use Modules\Team\Models\StaffTitleTable;
use Modules\Team\Models\DepartmentTable;
use Modules\Team\Models\TeamTable;

class TeamRepo implements TeamRepoInterface
{
    protected $team;

    public function __construct(
        TeamTable $team
    ) {
        $this->team = $team;
    }

    /**
     * Danh sách nhóm
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->team->getList($filters);

        return [
            'list' => $list
        ];
    }

    /**
     * Lấy data view tạo
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataCreate()
    {
        $mDepartment = app()->get(DepartmentTable::class);
        $mStaffTitle = app()->get(StaffTitleTable::class);

        //Lấy option phòng ban
        $optionDepartment = $mDepartment->getOption();
        //Lấy option chức vụ
        $optionTitle = $mStaffTitle->getOption();

        return [
            'optionDepartment' => $optionDepartment,
            'optionTitle' => $optionTitle
        ];
    }

    /**
     * Đổi chức vụ load nhân viên
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function changeTitle($input)
    {
        $mStaff = app()->get(StaffTable::class);

        //Load nhân viên theo chức vụ
        $optionStaff = $mStaff->getOptionByTitle($input['staff_title_id']);

        return [
            'optionStaff' => $optionStaff
        ];
    }

    /**
     * Thêm nhóm
     *
     * @param $input
     * @return array|mixed
     */
    public function store($input)
    {
        try {
            //Thêm nhóm
            $idTeam = $this->team->add([
                'team_name' => $input['team_name'],
                'staff_title_id' => $input['staff_title_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id']
            ]);

            //Cập nhật mã nhóm
            $teamCode = 'TEAM_' . date('dmY') . sprintf("%02d", $idTeam);
            $this->team->edit([
                'team_code' => $teamCode
            ], $idTeam);

            return ([
                'error' => false,
                'message' => __('Thêm thành công')
            ]);
        } catch (\Exception $e) {
            return ([
                'error' => true,
                'message' => __('Thêm thất bại')
            ]);
        }
    }

    /**
     * Lấy dữ liệu view chỉnh sửa
     *
     * @param $id
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataEdit($id)
    {
        $mDepartment = app()->get(DepartmentTable::class);
        $mStaffTitle = app()->get(StaffTitleTable::class);
        $mStaff = app()->get(StaffTable::class);

        //Lấy option phòng ban
        $optionDepartment = $mDepartment->getOption();
        //Lấy option chức vụ
        $optionTitle = $mStaffTitle->getOption();
        //Lấy thông tin nhóm
        $info = $this->team->getInfo($id);
        //Load nhân viên theo chức vụ
        $optionStaff = $mStaff->getOptionByTitle($info['staff_title_id']);

        return [
            'item' => $info,
            'optionDepartment' => $optionDepartment,
            'optionTitle' => $optionTitle,
            'optionStaff' => $optionStaff
        ];
    }

    /**
     * Chỉnh sửa nhóm
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            //Chỉnh sửa nhóm nhóm
            $this->team->edit([
                'team_name' => $input['team_name'],
                'staff_title_id' => $input['staff_title_id'],
                'department_id' => $input['department_id'],
                'staff_id' => $input['staff_id'],
                'is_actived' => $input['is_actived']
            ], $input['team_id']);

            return ([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            return ([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại')
            ]);
        }
    }

    /**
     * Xoá nhóm
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            $this->team->edit([
                'is_deleted' => 1
            ], $input['team_id']);

            return [
                'error' => false,
                'message' => __('Xóa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xóa thất bại')
            ];
        }
    }
}
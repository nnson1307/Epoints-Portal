<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:48 CH
 */

namespace Modules\Admin\Repositories\Staffs;

use App\Exports\ExportFile;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\DepartmentTable;
use Modules\Admin\Models\MapRoleGroupStaffTable;
use Modules\Admin\Models\OrderCommissionTable;
use Modules\Admin\Models\RoleGroupTable;
use Modules\Admin\Models\StaffAccountTable;
use Modules\Admin\Models\StaffsTable;
use Modules\Admin\Models\StaffTitleTable;
use Modules\Admin\Models\TeamTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;


class StaffRepository implements StaffRepositoryInterface
{

    /**
     * @var staffTable
     */
    protected $staff;
    protected $timestamps = true;
    public function __construct(StaffsTable $staffs)
    {
        $this->staff = $staffs;
    }
    /**
     *get list customer Group
     */
    public function list(array $filters = [])
    {
        return $this->staff->getList($filters);
    }
    /**
     * delete customer Group
     */
    public function remove($id)
    {
        $this->staff->remove($id);
    }

    /**
     * add customer Group
     */
    public function add(array $data)
    {
        return $this->staff->add($data);
    }
    /*
     * edit customer Group
     */
    public function edit(array $data ,$id)
    {

        return $this->staff->edit($data,$id);
    }
    /*
     *  update or add
     */

    public function getItem($id)
    {
        return $this->staff->getItem($id);
    }

    public function getNameStaff($id)
    {
        return $this->staff->getNameStaff($id);
    }

    public function testUserName($userName, $id)
    {
        return $this->staff->testUserName($userName,$id);
    }

    /**
     * @return array|mixed
     */
    public function getStaffOption()
    {
        $array=array();
        foreach ($this->staff->getStaffOption() as $item)
        {
            $array[$item['staff_id']]=$item['full_name'];

        }
        return $array;
    }

    public function getStaffOptionWithMoney()
    {
        $array = [];
        foreach ($this->staff->getStaffOption() as $item)
        {
            $array[$item['staff_id']] = [
                'name' => $item['full_name'],
                'money' => 0
            ];

        }
        return $array;
    }

    /**
     * @return array|mixed
     */
    public function getStaffTechnician()
    {
        $array=array();
        foreach ($this->staff->getStaffTechnician() as $item)
        {
            $array[$item['staff_id']]=$item['full_name'];

        }
        return $array;
    }

    /**
     * Export thông tin tất cả nv
     *
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportAll()
    {
        $heading = [
            __('TÊN NHÂN VIÊN'),
            __('SỐ ĐIỆN THOẠI'),
            __('ĐỊA CHỈ'),
            __('CHI NHÁNH'),
            __('CHỨC VỤ'),
            __('LƯƠNG'),
            __('TRỢ CẤP'),
            __('TỈ LỆ HOA HỒNG')
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        //Lấy thông tin tất cả nv
        $staffAll = $this->staff->getAllStaff();

        $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;

        if (count($staffAll) > 0) {
            foreach ($staffAll as $v) {
                $data [] = [
                    $v['full_name'],
                    $v['phone'],
                    $v['address'],
                    $v['branch_name'],
                    $v['staff_title_name'],
                    number_format($v['salary'], $decimal),
                    number_format($v['subsidize'], $decimal),
                    number_format($v['commission_rate'], $decimal)
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-staff.xlsx');
    }

    public function dataViewDetail($id)
    {
        $mBranch = new BranchTable();
        $mDepartment = new DepartmentTable();
        $mStaffTitle = new StaffTitleTable();
        $mRoleGroup = new RoleGroupTable();
        $mMapRoleGroupStaff = new MapRoleGroupStaffTable();
        $mStaff = new StaffsTable();
        $mOrderCommission = new OrderCommissionTable();

        //Lấy thông tin nhân viên
        $staffInfo = $mStaff->getItem($id);

        if ($staffInfo['birthday'] != null) {
            $birthday = explode('/', date("d/m/Y", strtotime($staffInfo['birthday'])));
            $day = $birthday[0];
            $month = $birthday[1];
            $year = $birthday[2];
        } else {
            $day = null;
            $month = null;
            $year = null;
        }

        $optionBranch = $mBranch->getBranchOption();
        $optionDepartment = $mDepartment->getStaffDepartmentOption();
        $optionTitle = $mStaffTitle->getStaffTitleOption();
        $roleGroup = $mRoleGroup->getOptionActive();
        $mapGroupStaff = $mMapRoleGroupStaff->getRoleGroupByStaffId($id);

        $arrayMapRoleGroupStaff = [];
        if (count($mapGroupStaff) > 0) {
            foreach ($mapGroupStaff as $values) {
                $arrayMapRoleGroupStaff[] = $values['role_group_id'];
            }
        }

        // List staff commission
        $listCommission = $mOrderCommission->getListStaffCommissionByStaffId($id)->toArray();

        $mTeam = app()->get(TeamTable::class);
        //Lấy option nhóm theo phòng ban
        $optionTeam = $mTeam->getTeamByDepartment($staffInfo['department_id']);

        return [
            'item' => $staffInfo,
            'optionBranch' => $optionBranch,
            'optionDepartment' => $optionDepartment,
            'optionTitle' => $optionTitle,
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'roleGroup' => $roleGroup,
            'arrayMapRoleGroupStaff' => $arrayMapRoleGroupStaff,
            'listCommission' => $listCommission,
            'optionTeam' => $optionTeam
        ];
    }

    public function getStaffByBranch($branchId){
    
        return $this->staff->getStaffByBranch($branchId);
    }

    /**
     * Thay đổi phòng ban
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function changeDepartment($input)
    {
        $mTeam = app()->get(TeamTable::class);

        //Lấy option nhóm
        $optionTeam = $mTeam->getTeamByDepartment($input['department_id']);

        return [
            'optionTeam' => $optionTeam
        ];
    }
}
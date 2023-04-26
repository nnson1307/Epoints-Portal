<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/24/2018
 * Time: 2:28 PM
 */

namespace Modules\Admin\Repositories\Department;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\DepartmentTable;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;
use Modules\Admin\Models\StaffsTable;
use Modules\Admin\Models\StaffTable;
use Modules\Admin\Models\StaffTitleTable;


class DepartmentRepository implements DepartmentRepositoryInterface
{
    protected $department;
    protected $timestamps = true;

    public function __construct(DepartmentTable $department)
    {
        $this->department = $department;
    }

    /**
     * get list department
     */
    public function list(array $filterts = [])
    {
        return $this->department->getList($filterts);
    }

    /**
     * add department.
     */
    public function add(array $data)
    {
        //Thêm phòng ban
        $adddepartment = $this->department->add([
            'department_name' => strip_tags($data['department_name']),
//            'branch_id' => $data['branch_id'],
//            'staff_title_id' => $data['staff_title_id'],
//            'staff_id' => $data['staff_id'],
            'is_inactive' => 1,
            'created_by' => Auth()->id(),
            'updated_by' => Auth()->id()
        ]);

        return [
            'error' => false,
            'message' => __('Tạo phòng ban thành công')
        ];
    }

    /**
     * edit department
     */
    public function edit(array $data, $id)
    {
        return $this->department->edit($data, $id);
    }

    /**
     * delete department
     */
    public function remove($id)
    {
        return $this->department->remove($id);
    }

    /**
     * Get item
     */
    public function getItem($id)
    {
        return $this->department->getItem($id);
    }

    //Get option
    public function getstaffDepartmentOption()
    {
        $array = [];
        foreach ($this->department->getstaffDepartmentOption() as $item) {
            $array[$item['department_id']] = $item['department_name'];
        }
        return $array;
    }

    /*
     * check unique department
     */
    public function check($name)
    {
        return $this->department->check($name);
    }

    /*
   * check unique department edit
   */
    public function checkEdit($id, $name)
    {
        return $this->department->checkEdit($id, $name);
    }
    /*
     * test is deleted
     */
    public function testIsDeleted($name)
    {
        return $this->department->testIsDeleted($name);
    }

    /*
     * edit by department name
     */
    public function editByName($name)
    {
        return $this->department->editByName($name);
    }

    public function adddepartment($data)
    {
        $dataadd['department_name'] = strip_tags($data['department_name']);
        $dataadd['created_by'] = Auth::id();
        $dataadd['is_inactive'] = $data['is_inactive'];
        $dataadd['updated_by'] = Auth::id();
        $dataadd['created_at'] = Carbon::now();
        $dataadd['updated_at'] = Carbon::now();
        //check bank name
        $adddepartment = $this->department->add($dataadd);
        return [
            'error' => false,
            'message' => __('Tạo phòng ban thành công')
        ];
    }

    /**
     * Lấy data view thêm
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataCreate()
    {
        $mBranch = app()->get(BranchTable::class);
        $mStaffTitle = app()->get(StaffTitleTable::class);

        //Lấy option chi nhánh
        $optionBranch = $mBranch->getBranchOption();
        //Lấy option chức vụ
        $optionTitle = $mStaffTitle->getOption();

        return [
            'optionBranch' => $optionBranch,
            'optionTitle' => $optionTitle
        ];
    }

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $id
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataEdit($id)
    {
        $mBranch = app()->get(BranchTable::class);
        $mStaffTitle = app()->get(StaffTitleTable::class);
        $mStaff = app()->get(StaffsTable::class);

        //Lấy option chi nhánh
        $optionBranch = $mBranch->getBranchOption();
        //Lấy option chức vụ
        $optionTitle = $mStaffTitle->getOption();
        //Lấy thông tin phòng ban
        $info = $this->department->getItem($id);
        //Load nhân viên theo chức vụ
        $optionStaff = $mStaff->getOptionByTitle($info['staff_title_id']);

        return [
            'optionBranch' => $optionBranch,
            'optionTitle' => $optionTitle,
            'item' => $info,
            'optionStaff' => $optionStaff
        ];
    }

    /**
     * Chỉnh sửa phòng ban
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            //Chỉnh sửa phòng ban
            $this->department->edit([
                'department_name' => strip_tags($input['department_name']),
//                'branch_id' => $input['branch_id'],
//                'staff_title_id' => $input['staff_title_id'],
//                'staff_id' => $input['staff_id'],
                'is_inactive' => $input['is_inactive'],
                'updated_by' => Auth()->id()
            ], $input['department_id']);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa phòng ban thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                'message' => __('Chỉnh sửa phòng ban thất bại')
            ];
        }
    }
}
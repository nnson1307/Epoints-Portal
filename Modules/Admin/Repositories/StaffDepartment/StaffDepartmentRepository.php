<?php

namespace Modules\Admin\Repositories\StaffDepartment;

use Modules\Admin\Models\StaffDepartmentTable;

class StaffDepartmentRepository implements StaffDepartmentRepositoryInterface
{
    protected $staffDepartment;
    protected $timestamps=true;

    public function __construct(StaffDepartmentTable $staffDepartment)
    {
        $this->staffDepartment=$staffDepartment;
    }

    /**
     * Lấy danh sách product label
     */
    public function list(array $filterts = [])
    {
        return $this->staffDepartment->getList($filterts);
    }
    /**
     * Thêm product label.
     */
    public function add(array $data)
    {
        return $this->staffDepartment->add($data);
    }
    /**
     * Sửa product label
     */
    public function edit(array $data ,$id)
    {
        try{
            if ($this->staffDepartment->edit($data ,$id) === false) throw new \Exception() ;
            return $id;
        }
        catch(\Exception  $e){
            $e->getMessage();
        }
        return false;
    }
    /**
     * Xóa product label
     */
    public function remove($id)
    {
        return $this->staffDepartment->remove($id);
    }
    /**
     * Get item
     */
    public function getItem($id)
    {
     return $this->staffDepartment->getItem($id);
    }

    public function getstaffDepartmentOption()
    {
        // TODO: Implement getstaffDepartmentOption() method.
        $array = [];
        foreach ($this->staffDepartment->getstaffDepartmentOption() as $item){
            $array[$item['staff_department_id']] = $item['staff_department_name'] ;
        }
        return $array ;
    }
}
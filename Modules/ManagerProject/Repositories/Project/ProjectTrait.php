<?php


namespace Modules\ManagerProject\Repositories\Project;

use Modules\ManagerWork\Models\StaffsTable;
use Modules\ManagerWork\Models\ProjectTable;
use Modules\ManagerWork\Models\DepartmentTable;
use Modules\ManagerWork\Models\ManageTagsTable;
use Modules\ManagerWork\Models\ProjectStatusTable;
use Modules\ManagerWork\Models\ManagerConfigListTable;


trait ProjectTrait
{
    /**
     *  Bộ lọc tìm kiếm dự án định
     * @return array
     */
    public function  listColumPrjectDeafault()
    {
        // Danh nhân viên (người quản trị)
        $mStaffs = app()->get(StaffsTable::class);
        $listStaffs = $mStaffs->getAll()->pluck('full_name', 'staff_id');
        // Danh sách tất cả nhân viên 
        $listAllStaffs = $mStaffs->getAllStaffs()->pluck('full_name', 'staff_id');
        // Danh sách trạng thái dự án
        $mStatusProject = app()->get(ProjectStatusTable::class);
        $listStatus = $mStatusProject->getAll()->pluck('manage_project_status_name', 'manage_project_status_id')->toArray();
        // Danh sách phòng ban
        $mDepartment = app()->get(DepartmentTable::class);
        $listDepartment = $mDepartment->getAll()->pluck('department_name', 'department_id')->toArray();
        // Danh sách tags
        $mTags = app()->get(ManageTagsTable::class);
        $listTag = $mTags->getAll()->pluck('manage_tag_name', 'manage_tag_id')->toArray();
        $listColumPrjectSearch = [
            0 => [
                "active" => 1,
                "placeholder" => __("Nhập tên dự án"),
                "type" => "text_group",
                "class" => "form-control",
                "id" => "manage_project_name",
                "icon" => "",
                "nameConfig" => __("Tên dự án"),
            ],
            1 => [
                "active" => 1,
                "placeholder" => __("Chọn trạng thái"),
                "type" => "select2",
                "class" => "form-control",
                "id" => "manage_project_status_id",
                "data" => $listStatus,
                "nameConfig" => __("Trạng thái dự án"),
            ],
            2 => [
                "active" => 1,
                "placeholder" => __("Chọn người quản trị"),
                "type" => "select2",
                "class" => "form-control",
                "id" => "manager_id",
                "data" => $listStaffs,
                "nameConfig" => __("Người quản trị"),
            ],
            3 => [
                "active" => 1,
                "type" => "date",
                "class" => "form-control m-input daterange-picker",
                "id" => "date_between",
                "nameConfig" => __("Ngày bắt đầu - kết thúc"),
                "placeholder" => __('Chọn thời gian')
            ],
            4 => [
                "active" => 1,
                "placeholder" => __("Nhập tiến độ"),
                "type" => "text_group",
                "class" => "form-control",
                "id" => "progress",
                "icon" => "",
                "group" => "%",
                "nameConfig" => __("Tiến độ"),
            ],
            5 => [
                "active" => 1,
                "placeholder" => __("Chọn quyền truy cập"),
                "type" => "select2",
                "class" => "form-control select2 select2-active",
                "id" => "permission",
                "data" => [
                    'private' => __("Nội bộ"),
                    'public' => __("Công khai")
                ],
                "nameConfig" => __("Quyền truy cập"),
            ],
            6 => [
                "active" => 1,
                "placeholder" => __("Chọn phòng ban trực thuộc"),
                "type" => "select2",
                "id" => "department_id",
                "class" => "form-control select2 select2-active",
                "data" => $listDepartment,
                "nameConfig" => __("Phòng ban trực thuộc"),
            ],
            7 => [
                "active" => 1,
                "type" => "date",
                "id" => "date_complete",
                "placeholder" =>  __("Ngày hoàn thành"),
                "nameConfig" => __("Ngày hoàn thành"),
                "class" => "form-control m-input m_selectpicker"
            ],
            8 => [
                "active" => 1,
                "placeholder" => __("Chọn người tạo dự án"),
                "type" => "select2",
                "class" => "form-control",
                "id" => "created_by",
                "data" => $listAllStaffs,
                "nameConfig" => __("Người tạo dự án"),
            ],
            9 => [
                "active" => 1,
                "type" => "date",
                "id" => "created_at",
                "placeholder" => __("Ngày tạo"),
                "nameConfig" => __("Ngày tạo"),
                "class" => "form-control m-input m_selectpicker"
            ],
            10 => [
                "active" => 1,
                "type" => "date",
                "id" => "updated_at",
                "placeholder" => __("Ngày cập nhật"),
                "nameConfig" => __("Ngày cập nhật"),
                "class" => "form-control m-input m_selectpicker"
            ],
            11 => [
                "active" => 1,
                "placeholder" => __("Chọn người cập nhật"),
                "type" => "select2",
                "class" => "form-control",
                "id" => "updated_by",
                "data" => $listAllStaffs,
                "nameConfig" => __("Người cập nhật"),
            ],
            12 => [
                "active" => 1,
                "placeholder" => __("Loại khách hàng"),
                "type" => "select2",
                "class" => "form-control",
                "id" => "customer_type",
                "data" => [
                    "personal" => __('Khách hàng'),
                    "bussiness" => __('Doanh nghiệp')
                ],
                "nameConfig" => __("Chọn loại khách hàng"),
                "function" => "Project.getCustomerDynamic(this)",
                "event" => "onchange"
            ],
            13 => [
                "active" => 1,
                "placeholder" => __("Khách hàng"),
                "type" => "select2",
                "class" => "form-control",
                "id" => "customer_id",
                "data" => [],
                "nameConfig" => __("Chọn khách hàng")
            ],
            14 => [
                "active" => 1,
                "placeholder" => __("Tags"),
                "type" => "select2",
                "class" => "form-control",
                "id" => "tags",
                "data" => $listTag,
                "attr" => "multiple",
                "nameConfig" => __("Chọn tags"),
            ]

        ];
        return $listColumPrjectSearch;
    }

    /**
     * Cột hiển thị dự án mặt định
     * @return array
     */
    public function listColumProjectShowDefault()
    {
        $listColumProjectShow = [

            1 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tên dự án"),
                "column_name" => "manage_project_name",
            ],
            2 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Trạng thái"),
                "relationship" => "status",
                "column_name" => "manage_project_status_name",
            ],
            3 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Quyền truy cập"),
                "column_name" => "permission",
            ],
            4 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Người quản trị"),
                "relationship" => "manager",
                "column_name" => "full_name"
            ],
            5 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Ngày bắt đầu - kết thúc"),
                "column_name" => "date_start_and_end",
            ],
            6 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Phòng ban"),
                "relationship" => "department",
                "column_name" => "department_name",
            ],
            7 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tiến độ"),
                "column_name" => "progress",
            ],
            8 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Ngày hoàn thành"),
                "column_name" => "date_complete",
            ],
            9 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Người tạo"),
                "relationship" => "staff_created",
                "column_name" => "full_name",
            ],
            10 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Ngày tạo"),
                "column_name" => "created_at",
            ],
            11 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Ngày cập nhật"),
                "column_name" => "updated_at",
            ],
            12 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Người cập nhật"),
                "relationship" => "staff_updated",
                "column_name" => "full_name",
            ],
            13 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Khách hàng"),
                "relationship" => "customer",
                "column_name" => "full_name",
            ],
            14 => [
                "class" => "",
                "active" => 1,
                "nameConfig" => __("Tag"),
                "relationship" => "tags",
                "column_name" => "manage_tag_name",
            ],
        ];
        return $listColumProjectShow;
    }

    /**
     * lấy danh sách bộ lọc tìm kiếm theo cấu hình
     * @return array
     */
    public function getColumSearchConfig()
    {
        $listColumnSearch = [];
        // danh sách bộ lọc mặc định
        $listColumSearchDefault = $this->listColumPrjectDeafault();
        // danh sách bộ lọc theo cấu hình
        $listColumSearchConfig = $this->getConfigShowListProject();
        if (collect($listColumSearchConfig)->count() > 0) {
            $listColumSearchConfig = $listColumSearchConfig->search;
            foreach ($listColumSearchDefault as $key => $item) {
                if (in_array($key, $listColumSearchConfig)) {
                    $listColumnSearch[$key] = $item;
                }
            }
        }

        return $listColumnSearch;
    }

    /**
     * Lấy danh sách cột hiển thị theo cấu hình
     * @return array
     */

    public function getColumShowConfig()
    {
        $listColumnShow = [];
        // danh sách bộ lọc mặc định
        $listColumShowDefault = $this->listColumProjectShowDefault();
        // danh sách cột hiển thị theo cấu hình
        $listColumShowConfig = $this->getConfigShowListProject();
        if (collect($listColumShowConfig)->count() > 0) {
            $listColumSearchConfig = $listColumShowConfig->column;
            foreach ($listColumShowDefault as $key => $item) {
                if (in_array($key, $listColumSearchConfig)) {
                    $listColumnShow[$key] = $item;
                }
            }
        }
        return $listColumnShow;
    }

    /**
     * Lấy danh sách câu hình hiển thị 
     * @return array
     */

    public function getConfigShowListProject()
    {
        $result = (object)[];
        $routeProjectList =  ProjectTable::PROJECT_ROUTE_LIST;
        $mManagerConfigList = app()->get(ManagerConfigListTable::class);
        $listConfigProjectList = $mManagerConfigList->getItemByRoute($routeProjectList);
        if ($listConfigProjectList) {
            $result = json_decode($listConfigProjectList->value);
        }
        return $result;
    }
}

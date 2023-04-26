<?php

namespace Modules\Commission\Repositories;

use Carbon\Carbon;
use Modules\Commission\Models\StaffCommissionEveryDayTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function GuzzleHttp\default_ca_bundle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\StaffTable;
use Modules\Commission\Models\BranchesTable;
use Modules\Commission\Models\CommissionObjectMapTable;
use Modules\Commission\Models\CommissionTable;
use Modules\Commission\Models\CommissionConfigTable;
use Modules\Commission\Models\CommissionTags;
use Modules\Commission\Models\CommissionAllocation;
use Modules\Commission\Models\ContractCategoryTable;
use Modules\Commission\Models\DepartmentsTable;
use Modules\Commission\Models\KpiCriteriaTable;
use Modules\Commission\Models\ProductCategoryTable;
use Modules\Commission\Models\ProductChildTable;
use Modules\Commission\Models\ServiceCardGroupTable;
use Modules\Commission\Models\ServiceCardTable;
use Modules\Commission\Models\ServiceCategoryTable;
use Modules\Commission\Models\ServiceTable;
use Modules\Commission\Models\StaffsTable;
use Modules\Commission\Models\StaffTitleTable;
use Modules\Commission\Models\StaffTypeTable;
use Modules\Commission\Models\TagsTable;
use Modules\Commission\Repositories\CommissionRepoInterface;

/**
 * Class EstimateBranchTimeRepo
 * @author HaoNMN
 * @since May 2022
 */
class CommissionRepo implements CommissionRepoInterface
{
    protected $commissionTag;
    protected $commission;
    protected $commissionConfig;
    protected $tags;
    protected $staff;
    protected $commissionAllocation;
    protected $timestamps = true;


    /**
     * Construct function
     */
    public function __construct(
        CommissionTags $commissionTag,
        CommissionTable $commission,
        CommissionConfigTable $commissionConfig,
        TagsTable $tags,
        StaffsTable $staff,
        CommissionAllocation $commissionAllocation
    ) {
        $this->commissionTag        = $commissionTag;
        $this->commission           = $commission;
        $this->commissionConfig     = $commissionConfig;
        $this->tags                 = $tags;
        $this->staff                = $staff;
        $this->commissionAllocation = $commissionAllocation;
    }

    /**
     * Lấy danh sách hoa hồng
     */
    public function listCommission(array $filter = [])
    {
        $data = $this->commission->listCommission($filter);

        foreach ($data as $item) {
            $staffTable = new StaffsTable();
            $createdBy = $staffTable->getStaffById($item['created_by']);

            $item['created_by'] = $createdBy['user_name'];

            $commissionTag = new CommissionTags();
            $tagsId        = $commissionTag->listTagByCommissionId($item['commission_id']);
            $item['tags']  = $tagsId;

            $item['count_staff'] = $this->commission->countStaffCommission($item['commission_id']);
        }

        return $data;
    }

    /**
     * Chi tiết hoa hồng
     */
    public function getDetailCommission($id)
    {
        //Lấy thong tin hoa hồng
        $data = $this->commission->getDetailCommision($id);

        if ($data != null) {
            switch ($data['commission_type']) {
                case 'order':
                    //Lấy thông tin nhóm áp dụng đơn hàng
                    $getGoods = $this->_getGoodsOrder($data);

                    $data['order_group_name'] = $getGoods['order_group_name'];
                    $data['order_object_name'] = $getGoods['order_object_name'];
                    break;
                case 'kpi':
                    break;
                case 'contract':
                    break;
            }
        }

        $commissionTag = new CommissionTags();
        //Lấy thông tin tags của hoa hồng
        $data['tag'] = $commissionTag->getTagByCommissionId($id) ? $commissionTag->getTagByCommissionId($id)->toArray() : $commissionTag->getTagByCommissionId($id);
        //Lấy thông tin điều kiện
        $data['commissionConfig'] = $this->commissionConfig->getConfigCommissionById($id);

        return $data;
    }

    /**
     * Lấy hàng hoá áp dụng đơn hàng
     *
     * @param $info
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function _getGoodsOrder($info)
    {
        $orderGroupName = $info['order_commission_type'] == 'all' || $info['order_commission_group_type'] == 0 ? __('Tất cả'): "";
        $orderObjectName = $info['order_commission_group_type'] == 0 || $info['order_commission_object_type'] == 'all' ? __('Tất cả'): "";

        switch ($info['order_commission_type']) {
            case 'product':
                $mProductCategory = app()->get(ProductCategoryTable::class);

                //Lấy tên nhóm sản phẩm
                $infoCategory = $mProductCategory->getInfoCategory($info['order_commission_group_type']);

                if ($infoCategory != null) {
                    $orderGroupName = $infoCategory['category_name'];
                }

                break;
            case 'service':
                $mServiceCategory = app()->get(ServiceCategoryTable::class);

                //Lấy tên nhóm dịch vụ
                $infoCategory = $mServiceCategory->getInfoCategory($info['order_commission_group_type']);

                if ($infoCategory != null) {
                    $orderGroupName = $infoCategory['name'];
                }

                break;
            case 'service_card':
                $mServiceCardGroup = app()->get(ServiceCardGroupTable::class);

                //Lấy tên nhóm thẻ dịch vụ
                $infoCategory = $mServiceCardGroup->getInfoGroup($info['order_commission_group_type']);

                if ($infoCategory != null) {
                    $orderGroupName = $infoCategory['name'];
                }

                break;
        }

        $mObjectMap = app()->get(CommissionObjectMapTable::class);

        //Lấy tên đối tượng của hoa hồng
        $getObjectMap = $mObjectMap->getObjectMap($info['commission_id']);

        if (count($getObjectMap) > 0) {
            foreach ($getObjectMap as $k => $v) {
                $space = $k + 1 < count($getObjectMap) ? ', ': '';

                switch ($v['object_type']) {
                    case 'product':
                        $orderObjectName .= $v['product_child_name'] . $space;
                        break;
                    case 'service':
                        $orderObjectName .= $v['service_name'] . $space;
                        break;
                    case 'service_card':
                        $orderObjectName .= $v['service_card_name'] . $space;
                        break;
                }
            }
        }

        return [
            'order_group_name' => $orderGroupName,
            'order_object_name' => $orderObjectName
        ];
    }

    /**
     * Lấy danh sách hoa hồng thực nhận
     */
    public function listCommissionReceived(array $filter = [])
    {
        $data = $this->staff->getListStaffReceived($filter);

        if (count($data->items()) > 0) {
            $mCommissionEveryDay = app()->get(StaffCommissionEveryDayTable::class);

            foreach ($data->items() as $v) {
                //Lấy hoa hồng của nhân viên
                $getCommission = $mCommissionEveryDay->getCommissionByStaff(
                    $v['staff_id'],
                    isset($filter['commission_day']) ? $filter['commission_day'] : null
                );

                $v['total_commission_money'] = $getCommission != null ? $getCommission['total_commission_money'] : 0;
            }
        }

        return $data;
    }

    /**
     * Thêm hoa hồng
     *
     * @param $data
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function saveCommission($data)
    {
        DB::beginTransaction();
        try {
            //Check start time > end time
            if (isset($data['end_effect_time']) && $data['end_effect_time'] != null &&
                Carbon::createFromFormat('d/m/Y', $data['start_effect_time'])->format('Y-m-d') >= Carbon::createFromFormat('d/m/Y', $data['end_effect_time'])->format('Y-m-d')) {
                return [
                    'error' => true,
                    'message' => __('Ngày bắt đầu phải lớn hơn ngày kết thúc'),
                ];
            }

            if (!isset($data['tableData']) || count($data['tableData']) == 0) {
                return [
                    'error' => true,
                    'message' => __('Hãy thêm điều kiện tính tối thiểu là 1 điều kiện'),
                ];
            }

            $dataAdd = [
                'commission_name' => $data['commission_name'],
                'status' => $data['status'],
//                'apply_time' => $data['apply_time'],
//                'calc_apply_time' => $data['calc_apply_time'],
//                'start_effect_time' => Carbon::createFromFormat('d/m/Y', $data['start_effect_time'])->format('Y-m-d'),
//                'end_effect_time' => isset($data['end_effect_time']) ? Carbon::createFromFormat('d/m/Y', $data['end_effect_time'])->format('Y-m-d') : null,
                'description' => $data['description'],
                'commission_type' => $data['commission_type'],
                'commission_calc_by' => $data['commission_calc_by'],
                'commission_scope' => $data['commission_scope'],
                'order_commission_type' => isset($data['order_commission_type']) ? $data['order_commission_type'] : null,
                'order_commission_group_type' => isset($data['order_commission_group_type']) ? $data['order_commission_group_type'] : null,
                'order_commission_calc_by' => isset($data['order_commission_calc_by']) ? $data['order_commission_calc_by'] : null,
                'contract_commission_calc_by' => isset($data['contract_commission_calc_by']) ? $data['contract_commission_calc_by'] : null,
                'contract_commission_condition' => isset($data['contract_commission_condition']) ? $data['contract_commission_condition'] : null,
                'contract_commission_operation' => isset($data['contract_commission_operation']) ? $data['contract_commission_operation'] : null,
                'contract_commission_time' => isset($data['contract_commission_time']) ? $data['contract_commission_time'] : null,
                'contract_commission_apply' => isset($data['contract_commission_apply']) ? $data['contract_commission_apply'] : null,
                'created_by' => Auth()->id()
            ];

            if (isset($data['order_commission_group_type'])) {
                switch ($data['order_commission_group_type']) {
                    case 'all':
                        $dataAdd['order_commission_group_type'] = 0;
                        break;
                    default:
                        $dataAdd['order_commission_group_type'] = $data['order_commission_group_type'];
                        break;
                }
            }

            if (isset($data['kpi_commission_calc_by'])) {
                switch ($data['kpi_commission_calc_by']) {
                    case 'all':
                        $dataAdd['kpi_commission_calc_by'] = 0;
                        break;
                    default:
                        $dataAdd['kpi_commission_calc_by'] = $data['kpi_commission_calc_by'];
                        break;
                }
            }

            if (isset($data['contract_commission_type'])) {
                switch ($data['contract_commission_type']) {
                    case 'all':
                        $dataAdd['contract_commission_type'] = 0;
                        break;
                    default:
                        $dataAdd['contract_commission_type'] = $data['contract_commission_type'];
                        break;
                }
            }

            $arrObjectMap = [];

            if (isset($data['order_commission_object_type']) && count($data['order_commission_object_type']) > 0) {
                switch (count($data['order_commission_object_type'])) {
                    case 1:
                        if ($data['order_commission_object_type'][0] != 'all') {
                            $dataAdd['order_commission_object_type'] = 'object';

                            $arrObjectMap [] = [
                                'object_type' => $data['order_commission_type'],
                                'object_id' => $data['order_commission_object_type'][0]
                            ];
                        } else {
                            $dataAdd['order_commission_object_type'] = 'all';
                        }

                        break;
                    default:
                        $dataAdd['order_commission_object_type'] = 'object';

                        foreach ($data['order_commission_object_type'] as $v) {
                            $arrObjectMap [] = [
                                'object_type' => $data['order_commission_type'],
                                'object_id' => $v
                            ];
                        }

                        break;
                }
            } else {
                $dataAdd['order_commission_object_type'] = 'all';
            }

            //Thêm hoa hồng
            $commissionId = $this->commission->add($dataAdd);

            $arrTag = [];

            if (isset($data['tags_id']) && count($data['tags_id']) > 0) {
                foreach ($data['tags_id'] as $v) {
                    $arrTag [] = [
                        'commission_id' => $commissionId,
                        'tags_id' => $v
                    ];
                }
            }

            $mTag = app()->get(CommissionTags::class);
            $mCommissionConfig = app()->get(CommissionConfigTable::class);
            $mObjectMap = app()->get(CommissionObjectMapTable::class);

            //Thêm tag của hoa hồng
            $mTag->insert($arrTag);

            $arrConfig = [];

            if (isset($data['tableData']) && count($data['tableData']) > 0) {
                foreach ($data['tableData'] as $v) {
                    $arrConfig [] = [
                        'commission_id' => $commissionId,
                        'min_value' => $v['min_value'],
                        'max_value' => $v['max_value'] != 0 ? $v['max_value'] : null,
                        'commission_value' => $v['commission_value'],
                        'config_operation' => $v['config_operation']
                    ];
                }
            }

            //Thêm cấu hình hoa hồng
            $mCommissionConfig->insert($arrConfig);

            $arrInsertMap = [];

            if (count($arrObjectMap) > 0) {
                foreach ($arrObjectMap as $v) {
                    $v['commission_id'] = $commissionId;
                    $v['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $v['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

                    $arrInsertMap [] = $v;
                }
            }

            //Thêm object map
            $mObjectMap->insert($arrInsertMap);

            DB::commit();

            return [
                'error' => false,
                'message' => __('Thêm thành công')
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'error' => true,
                'message' => __('Thêm thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Soft delete hoa hồng
     */
    public function removeCommission($id)
    {
        $this->commission->removeCommission($id);
        $this->commissionAllocation->removeAllocation(($id));
    }

    /**
     * Lấy danh sách loại hợp đồng
     */
    public function getListCategory()
    {
        $category = new ProductCategoryTable();
        return $category->getListCategory();
    }

    /**
     * Lấy danh sách tag
     */
    public function listTag()
    {
        $data = $this->tags->listTag();
        return $data;
    }

    /**
     * Thêm mới tag
     *
     * @param $data
     * @return mixed
     */
    public function addTag($data)
    {
        return $this->tags->addTag($data);
    }

    /**
     * Kiểm tra tag đã tồn tại hay chưa
     * Nếu chưa thì thêm mới vô database và trả về id tag mới
     * Nếu có thì trả về id tag ban đầu
     */
    public function checkTag($tags_id)
    {
        $tag = TagsTable::where('tags_id', $tags_id)->first();

        if ($tag === null) {
            $id = TagsTable::insertGetId(['tags_name' => $tags_id]);
            return $id;
        }

        return $tags_id;
    }

    /**
     * Lấy danh sách nhân viên
     */
    public function getListStaff(array $filter = [])
    {
        $data = $this->staff->getListStaff($filter);
        return $data;
    }

    /**
     * Lấy danh sách loại nhân viên
     */
    public function getListType()
    {
        $type = new StaffTypeTable();
        return $type->getListType();
    }

    /**
     * Lấy danh sách chi nhánh
     */
    public function getListBranch()
    {
        $branch = new BranchesTable();
        return $branch->getListBranch();
    }

    /**
     * Lấy danh sách phòng ban
     */
    public function getListDepartment()
    {
        $department = new DepartmentsTable();
        return $department->getListDepartment();
    }

    /**
     * Lấy danh sách chức vụ
     */
    public function getListTitle()
    {
        $title = new StaffTitleTable();
        return $title->getListTitle();
    }

    /**
     * Lấy danh sách loại hoa hồng
     */
    public function getListTypeCommission()
    {
        $data = $this->commission->getListTypeCommission();
        return $data;
    }

    /**
     * Lưu phân bổ vào database
     */
    public function saveCommissionAllocation($data)
    {
        try {
            $mAllocation = app()->get(CommissionAllocation::class);

            if (isset($data['arrCoefficient']) && count($data['arrCoefficient']) > 0) {
                foreach ($data['arrCoefficient'] as $v) {
                    if ($v == null || $v <= 0) {
                        return [
                            'error' => true,
                            'message' => __('Hệ số hoa hồng phải lớn hơn 0')
                        ];
                    }
                }
            }

            $dataInsert = [];

            if (isset($data['arrCommission']) && count($data['arrCommission']) > 0) {
                foreach ($data['arrCommission'] as $v) {
                    foreach ($v['arrayStaff'] as $v1) {
                        $dataInsert [] = [
                            'commission_id' => $v['commission_id'],
                            'staff_id' => $v1['staff_id'],
                            'commission_coefficient' => $data['arrCoefficient'][$v1['staff_id']]
                        ];
                    }
                }
            }

            //Insert bảng phân bổ
            $mAllocation->insert($dataInsert);

            return [
                'error' => false,
                'message' => __('Phân bổ thành công'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Phân bổ thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách nhân viên theo hoa hồng
     */
    public function getStaffByCommission($id)
    {
        $data = $this->commissionAllocation->getStaffByCommission($id);
        return $data;
    }

    /**
     * Lấy data nhóm hàng hoá theo loại hàng hoá
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataOrderGroupByType($input)
    {
        $data = [];

        switch ($input['order_commission_type']) {
            case 'product':
                $mProductCategory = app()->get(ProductCategoryTable::class);

                //Lấy nhóm sản phẩm
                $data = $mProductCategory->getListCategory();

                break;
            case 'service':
                $mServiceCategory = app()->get(ServiceCategoryTable::class);

                //Lấy nhóm dịch vụ
                $data = $mServiceCategory->getOptionCategory();
                break;
            case 'service_card':
                $mServiceCardGroup = app()->get(ServiceCardGroupTable::class);

                //Lấy nhóm thẻ dịch vụ
                $data = $mServiceCardGroup->getOptionGroup();
                break;
        }

        return $data;
    }

    /**
     * Option hàng hoá
     *
     * @param $filter
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function listOptionOrderObject($filter)
    {
        $mProduct = app()->get(ProductChildTable::class);
        $mService = app()->get(ServiceTable::class);
        $mServiceCard = app()->get(ServiceCardTable::class);

        if ($filter['type'] == 'product') {
            //Lấy ds sản phẩm
            $data = $mProduct->getListChildPaginate($filter);

            return [
                'items' => $data->items(),
                'pagination' => range($data->currentPage(),
                    $data->lastPage()) ? true : false
            ];
        } else if ($filter['type'] == 'service') {
            //Lây ds dịch vụ
            $data = $mService->getListChildPaginate($filter);

            return [
                'items' => $data->items(),
                'pagination' => range($data->currentPage(),
                    $data->lastPage()) ? true : false
            ];
        } else if ($filter['type'] == 'service_card') {
            //Lây ds thẻ dịch vụ
            $data = $mServiceCard->getListChildPaginate($filter);

            return [
                'items' => $data->items(),
                'pagination' => range($data->currentPage(),
                    $data->lastPage()) ? true : false
            ];
        }
    }

    /**
     * Lấy option tiêu chí kpi
     *
     * @param $kpiCriteriaType
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getOptionCriteria($kpiCriteriaType)
    {
        $mKpiCriteria = app()->get(KpiCriteriaTable::class);

        //Lấy option tiêu chí kpi
        return  $mKpiCriteria->getOptionCriteria($kpiCriteriaType);
    }

    /**
     * Lấy option loại hợp đồng
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getOptionContractCategory()
    {
        $mContractCategory = app()->get(ContractCategoryTable::class);

        //Lấy option loại hợp đồng
        return $mContractCategory->getOptionCategory();
    }

    /**
     * Danh sách nhân viên (phân trang)
     *
     * @param array $filter
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function listStaff($filter = [])
    {
        $mStaff = app()->get(StaffsTable::class);

        //Lấy ds nhân viên
        return $mStaff->getList($filter);
    }

    /**
     * Lấy hoa hồng được phân bổ cho nhân viên
     *
     * @param $idStaff
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getAllocationByStaff($idStaff)
    {
        $mAllocation = app()->get(CommissionAllocation::class);

        //Lấy hoa hồng phân bổ cho nhân viên
        return $mAllocation->getAllocationByStaff($idStaff);
    }

    /**
     * Chỉnh sửa hoa hồng được phân bổ cho nhân viên
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function editReceived($input)
    {
        try {
            $dataInsert = [];

            if (isset($input['arrData']) && count($input['arrData']) > 0) {
                foreach ($input['arrData'] as $v) {
                    if ($v['commission_coefficient'] == null || $v['commission_coefficient'] <= 0) {
                        return [
                            'error' => true,
                            'message' => __('Hệ số hoa hồng phải lớn hơn 0')
                        ];
                    }

                    $dataInsert [] = [
                        'staff_id' => $input['staff_id'],
                        'commission_id' => $v['commission_id'],
                        'commission_coefficient' => $v['commission_coefficient']
                    ];
                }
            }

            $mAllocation = app()->get(CommissionAllocation::class);

            //Xoá hoa hồng được phân bổ cho nhân viên
            $mAllocation->removeAllocationByStaff($input['staff_id']);
            //Insert lại hoa hồng được phân bổ cho nhân viên
            $mAllocation->insert($dataInsert);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy chi tiết hoa hồng nhân viên
     *
     * @param $idStaff
     * @return mixed|void
     */
    public function getDataDetailReceived($idStaff)
    {
        $mStaff = app()->get(StaffsTable::class);
        $mCommissionEveryDay = app()->get(StaffCommissionEveryDayTable::class);
        $mCommissionAllocation = app()->get(CommissionAllocation::class);

        //Lấy thông tin nhân viên
        $item = $mStaff->getInfo($idStaff);
        //Lấy tiền đã nhận hoa hồng của nhân viên
        $getCommission = $mCommissionEveryDay->getCommissionByStaff($idStaff, null);

        $total_commission_money = 0;

        if ($getCommission != null) {
            $total_commission_money = $getCommission['total_commission_money'];
        }

        $item['total_commission_money'] = $total_commission_money;

        //Lấy ds hoa hồng đã được phân bổ cho nhân viên
        $getAllocation = $mCommissionAllocation->getAllocationByStaff($idStaff);
        //Lấy ds hoa hồng đã nhân của nhân viên
        $listStaffCommission = $this->listStaffCommission([
            'staff_id' => $idStaff
        ]);

        return [
            'item' => $item,
            'allocation' => $getAllocation,
            'LIST' => $listStaffCommission
        ];
    }

    /**
     * Danh sách hoa hồng của nhân viên
     *
     * @param $filter
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function listStaffCommission($filter = [])
    {
        $mCommissionEveryDay = app()->get(StaffCommissionEveryDayTable::class);

        return $mCommissionEveryDay->getList($filter);
    }

    /**
     * Thêm nhanh tags
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createTag($input)
    {
        try {
            $mTag = app()->get(TagsTable::class);

            //Insert tag
            $tagId = $mTag->insertGetId([
                'tags_name' => $input['tag_name'],
            ]);

            return [
                'error' => false,
                'tag_id' => $tagId,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật trạng thái hoa hồng
     *
     * @param $input
     * @return array
     */
    public function changeStatus($input)
    {
        try {
            //Update pipeline category
            $this->commission->edit([
                'status' => $input['status']
            ], $input['commission_id']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ];
        }
    }
}

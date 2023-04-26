<?php


namespace Modules\Warranty\Repository\MaintenanceCostType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Warranty\Models\MaintenanceCostTypeTable;
use MyCore\Models\Traits\ListTableTrait;

class MaintenanceCostTypeRepo implements MaintenanceCostTypeRepoInterface
{
    protected $maintenanceCostType;

    public function __construct(MaintenanceCostTypeTable $maintenanceCostType)
    {
        $this->maintenanceCostType = $maintenanceCostType;
    }

    public function getList(array $filters = [])
    {
        $list = $this->maintenanceCostType->getList($filters);
        return [
            "list" => $list,
        ];
    }

    /**
     * Lưu 1 loại chi phí phát sinh mới
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($input)
    {
        try {
            $dataInsert = [
                'maintenance_cost_type_name_vi' => $input["maintenance_cost_type_name_vi"],
                'maintenance_cost_type_name_en' => $input["maintenance_cost_type_name_en"],
                'created_by' => Auth::id(),
            ];
            $this->maintenanceCostType->add($dataInsert);
            return response()->json([
                'error' => false,
                'message' => __('Thêm loại chi phí phát sinh thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thêm loại chi phí phát sinh thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Dữ liệu của View chỉnh sửa khi chỉnh sửa
     *
     * @param $warrantyPackageId
     * @return array|mixed
     */
    public function dataViewEdit($warrantyPackageId)
    {
        $data = $this->maintenanceCostType->getInfo($warrantyPackageId);
        if($data == null){
            return [];
        }
        else{
            return $data;
        }
    }

    /**
     * Cập nhật 1 chi phí phát sinh
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($input)
    {
        try {
            $dataUpdate = [
                'maintenance_cost_type_id' => $input["maintenance_cost_type_id"],
                'maintenance_cost_type_name_vi' => $input["maintenance_cost_type_name_vi"],
                'maintenance_cost_type_name_en' => $input["maintenance_cost_type_name_en"],
                'is_active' => $input["is_active"],
                'updated_by' => Auth::id(),
            ];
            $this->maintenanceCostType->edit($dataUpdate,$input["maintenance_cost_type_id"]);
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa loại chi phí phát sinh thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa loại chi phí phát sinh thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xoá chi phí phát sinh
     *
     * @param $input
     * @return mixed
     */
    public function delete($input)
    {
        return $this->maintenanceCostType->deleteType($input);
    }
}
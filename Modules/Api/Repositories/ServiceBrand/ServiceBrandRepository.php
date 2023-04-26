<?php


namespace Modules\Api\Repositories\ServiceBrand;

use Illuminate\Support\Facades\DB;
use Modules\Api\Models\AdminServiceBrandFeatureChildTable;
use Modules\Api\Models\AdminServiceBrandFeatureTable;
use Modules\Api\Models\AdminServiceBrandTable;

class ServiceBrandRepository implements ServiceBrandRepositoryInterface
{
    protected $serviceBrandTable;
    protected $serviceBrandFeatureTable;

    public function __construct(
        AdminServiceBrandTable $serviceBrandTable,
        AdminServiceBrandFeatureTable $serviceBrandFeatureTable
    ) {
        $this->serviceBrandTable = $serviceBrandTable;
        $this->serviceBrandFeatureTable = $serviceBrandFeatureTable;
    }

    /**
     * Tạo phân quyền cho brand
     *
     * @param $data
     * @throws \Exception
     */
    public function createServiceBrand($data)
    {
        try {
            //Tạo service cho brand
            $this->serviceBrandTable->createBrand($data['service']);
            //Tạo service_feature cho brand
            $this->serviceBrandFeatureTable->createBrandFeature($data['feature']);
            //Tạo service_feature_child cho brand
            $mFeatureChild = app()->get(AdminServiceBrandFeatureChildTable::class);
            $mFeatureChild->createBrandFeatureChild($data['feature_child']);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Xoá các quyền của brand
     *
     * @param $data
     * @throws \Exception
     */
    public function deleteServiceBrand($data)
    {
        try {
            //Xoá service cho brand
            $this->serviceBrandTable->deleteByBrandId($data['brand_id']);
            //Xoá service_feature cho brand
            $this->serviceBrandFeatureTable->deleteByBrandId($data['brand_id']);
            //Xoá service_feature_child cho brand
            $mFeatureChild = app()->get(AdminServiceBrandFeatureChildTable::class);
            $mFeatureChild->deleteByBrandId($data['brand_id']);

            DB::table('admin_service_brand')->delete();
            DB::table('admin_service_brand_feature')->delete();
            DB::table('admin_service_brand_feature_child')->delete();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}

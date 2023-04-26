<?php


namespace Modules\Api\Repositories\ServiceBrand;

interface ServiceBrandRepositoryInterface
{
//    Tạo danh sách service cho brand
    public function createServiceBrand($data);

//    Xóa danh sách service theo Brand
    public function deleteServiceBrand($data);
}

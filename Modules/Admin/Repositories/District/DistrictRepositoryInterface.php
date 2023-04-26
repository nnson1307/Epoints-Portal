<?php
namespace Modules\Admin\Repositories\District;

interface DistrictRepositoryInterface
{

    public function getOptionDistrict(array $filters = []);

    /**
     * Lấy thông tin quận huyện
     *
     * @param $districtId
     * @return mixed
     */
    public function getItem($districtId);
}
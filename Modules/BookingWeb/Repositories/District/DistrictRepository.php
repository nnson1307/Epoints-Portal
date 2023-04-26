<?php


namespace Modules\BookingWeb\Repositories\District;


use Modules\BookingWeb\Models\DistrictTable;

class DistrictRepository implements DistrictRepositoryInterface
{
    protected $district;

    public function __construct(DistrictTable $district)
    {
        $this->district = $district;
    }

    public function getDistrictOption($id_province)
    {
        // TODO: Implement getDistrictOption() method.
        return $this->district->getDistrictOption($id_province);
    }
}
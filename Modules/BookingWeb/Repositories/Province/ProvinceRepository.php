<?php


namespace Modules\BookingWeb\Repositories\Province;


use Modules\BookingWeb\Models\ProvinceTable;

class ProvinceRepository implements ProvinceRepositoryInterface
{
    protected $province;

    public function __construct(ProvinceTable $province)
    {
        $this->province = $province;
    }

    public function getProvinceOption()
    {
        // TODO: Implement getProvinceOption() method.
        return $this->province->getProvinceOption();
    }
}
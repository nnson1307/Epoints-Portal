<?php


namespace Modules\FNB\Repositories\Province;


use Modules\FNB\Models\ProvinceTable;

class ProvinceRepository implements ProvinceRepositoryInterface
{
    private $province;

    public function __construct(ProvinceTable $province)
    {
        $this->province = $province;
    }

    public function getOptionProvince()
    {
        return $this->province->getOptionProvince();
    }

}
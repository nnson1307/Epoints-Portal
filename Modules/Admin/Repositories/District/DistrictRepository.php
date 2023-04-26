<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 12:44 PM
 */

namespace Modules\Admin\Repositories\District;
use Modules\Admin\Models\DistrictTable;

class DistrictRepository implements DistrictRepositoryInterface
{
    protected $district;
    protected $timestamp=true;

    public function __construct(DistrictTable $district)
    {
        $this->district=$district;
    }

    public function getOptionDistrict(array $filters = [])
    {
//        $listData=array();
//        $data = $this->district->getList($filters);
//        foreach ($data as $key=>$value)
//        {
//            $listData[]=[
//                'id'=>$value['districtid'],
//                'name'=>$value['name'],
//                'type'=>$value['type']
//            ];
//        }
//            return $listData;
//        if (!isset($filters['page'])) {
//            $filters['page'] = 1;
//        }

        if (!isset($filters['id_province'])) {
            $filters['id_province'] = '';
        }

        return $this->district->getOptionDistrict($filters['id_province']);

    }

    /**
     * Lấy thông tin quận huyện
     *
     * @param $districtId
     * @return mixed
     */
    public function getItem($districtId)
    {
        return $this->district->getItem($districtId);
    }
}
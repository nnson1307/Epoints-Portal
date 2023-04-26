<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 12:16 PM
 */

namespace Modules\Admin\Repositories\Province;
use Modules\Admin\Models\ProvinceTable;


class ProvinceRepository implements ProvinceRepositoryInterface
{
    protected $province;
    protected $timestamps=true;

    public function __construct(ProvinceTable $province)
    {
        $this->province=$province;
    }

    public function getOptionProvince()
    {
        // TODO: Implement getOptionProvince() method.
        $listData=array();
        foreach ($this->province->getOptionProvince() as $value){
            $listData[$value['provinceid']]=$value['name'];
        }
        return $listData;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/29/2019
 * Time: 2:52 PM
 */

namespace Modules\Admin\Repositories\BrandName;


use Modules\Admin\Models\BrandNameTable;

class BrandNameRepository implements BrandNameRepositoryInterFace
{
    protected $brandName;
    protected $timestamps = true;

    public function __construct(BrandNameTable $brandName)
    {
        $this->brandName = $brandName;
    }

    public function getOption()
    {
        $data = $this->brandName->getOption();
        $array = [];
        foreach ($data as $value) {
            $array[$value['id']] = $value['name'];
        }
        return $array;
    }

    public function getItem($id)
    {
        return $this->brandName->getItem($id);
    }
}
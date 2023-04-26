<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 5:40 PM
 */

namespace Modules\Admin\Repositories\Ward;
use Modules\Admin\Models\WardTable;


class WardRepository implements WardRepositoryInterface
{
    protected $ward;
    protected $timestamps=true;

    public function __construct(WardTable $wardTable)
    {
        $this->ward=$wardTable;
    }

    public function getOptionWard($id)
    {
        $listData=array();
        foreach ($this->ward->getOptionWard($id) as $key=>$value)
        {
            $listData[$value['wardid']]=$value['name'];
        }
        return $listData;
    }
}
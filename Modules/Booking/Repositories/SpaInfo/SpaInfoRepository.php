<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/4/2019
 * Time: 12:04 PM
 */

namespace Modules\Booking\Repositories\SpaInfo;


use Modules\Booking\Models\SpaInfoTable;

class SpaInfoRepository implements SpaInfoRepositoryInterface
{
    protected $spaInfo;

    public function __construct(SpaInfoTable $spaInfo)
    {
        $this->spaInfo = $spaInfo;
    }

    public function getItem($id)
    {
        return $this->spaInfo->getItem($id);
    }

    public function getIntroduction()
    {
        return $this->spaInfo->getIntroduction();
    }
}
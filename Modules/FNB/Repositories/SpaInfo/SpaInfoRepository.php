<?php


namespace Modules\FNB\Repositories\SpaInfo;


use Modules\Admin\Models\SpaInfoTable;

class SpaInfoRepository implements SpaInfoRepositoryInterface
{
    protected $spa_info;
    protected $timestamps = true;

    public function __construct(SpaInfoTable $spa_info)
    {
        $this->spa_info = $spa_info;
    }
    
    public function getInfoSpa(){
        return $this->spa_info->getInfoSpa();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/07/2022
 * Time: 15:16
 */

namespace Modules\Kpi\Repositories\CalculateKpi;


interface CalculateKpiRepoInterface
{
    /**
     * Chạy job tính kpi
     *
     * @return mixed
     */
    public function jobCalculateKpi();
}
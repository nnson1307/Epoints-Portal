<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/07/2022
 * Time: 15:17
 */

namespace Modules\Kpi\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Kpi\Repositories\CalculateKpi\CalculateKpiRepoInterface;

class CalculateKpiController extends Controller
{
    protected $calculateKpi;

    public function __construct(
        CalculateKpiRepoInterface $calculateKpi
    ) {
        $this->calculateKpi = $calculateKpi;
    }

    public function calculate()
    {
        return $this->calculateKpi->jobCalculateKpi();
    }
}
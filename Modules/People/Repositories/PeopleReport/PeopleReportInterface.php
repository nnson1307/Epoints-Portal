<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\People\Repositories\PeopleReport;


interface PeopleReportInterface
{

    public function list($year);

    public function export($filter);

}

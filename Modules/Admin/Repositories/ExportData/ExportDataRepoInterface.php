<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/05/2022
 * Time: 14:44
 */

namespace Modules\Admin\Repositories\ExportData;


interface ExportDataRepoInterface
{
    /**
     * Xuất dữ liệu cho khách sie
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelSie($input);
}
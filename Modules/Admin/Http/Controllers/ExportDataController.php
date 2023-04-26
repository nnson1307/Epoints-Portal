<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/05/2022
 * Time: 14:44
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\ExportData\ExportDataRepoInterface;

class ExportDataController extends Controller
{
    protected $exportData;

    public function __construct(
        ExportDataRepoInterface $exportData
    ) {
        $this->exportData = $exportData;
    }

    /**
     * Xuất dữ liệu excel
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelAction(Request $request)
    {
        return $this->exportData->exportExcelSie($request->all());
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Huniel
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\People\Repositories\PeopleReport;

use App\Exports\ExportFile;
use App\Exports\ExportReportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\People\Models\EducationalLevelTable;
use Modules\People\Models\PeopleFamilyTable;
use Modules\People\Models\PeopleObjectGroupTable;
use Modules\People\Models\PeopleObjectTable;
use Modules\People\Models\PeopleVerifyTable;
use Modules\People\Models\ExcelExport;
use function Symfony\Component\HttpKernel\Debug\format;

class PeopleReportRepo implements PeopleReportInterface
{
    protected $_mPeopleObject;
    protected $_mPeopleVerify;
    protected $_mPeopleEducation;
    public $peopleObjectGroup;

    public function __constructor(
        PeopleObjectTable $objectTable,
        PeopleVerifyTable $peopleVerifyTable,
        EducationalLevelTable $educationalLevelTable,
        PeopleObjectGroupTable $peopleObjectGroup
    )
    {

        $this->_mPeopleObject = $objectTable;
        $this->_mPeopleVerify = $peopleVerifyTable;
        $this->_mPeopleEducation = $educationalLevelTable;
        $this->peopleObjectGroup = $peopleObjectGroup;
    }

    public function list($param)
    {
        $mPeopleVerify = new PeopleVerifyTable();
        $data['list'] = $mPeopleVerify->listReport($param, true);

        $report = $this->_report($param);

        $mPeopleEducation = new EducationalLevelTable();
        $data['edu'] = $mPeopleEducation->getListByReport();
        $data['report'] = $report['report'];
        $data['list_year'] = $report['list_year'];

        return $data;
    }

    protected function _report($year)
    {
        $mPeopleVerify = new PeopleVerifyTable();
        $report = $mPeopleVerify->listReport($year);

        $arrListReport = [];
        $arrListYear = [];
        foreach ($report as $itemReport) {
            if (isset($arrListReport[$itemReport['people_object_group_id']])) {
                $arrListReport[$itemReport['people_object_group_id']]['data']['total'] += 1;
            } else {
                $arrListReport[$itemReport['people_object_group_id']]['data'] = [
                    'people_object_group_id' => $itemReport['people_object_group_id'],
                    'people_object_group_name' => $itemReport['people_object_group_name'],
                    'total' => 1
                ];
            }
            $year = Carbon::createFromFormat('Y-m-d', $itemReport['birthday'])->year;
            $arrListYear[$year] = $year;

            if (isset($arrListReport[$itemReport['people_object_group_id']]['child'][$itemReport['people_object_id']])) {
                $arrListReport[$itemReport['people_object_group_id']]['child'][$itemReport['people_object_id']]['data']['total'] += 1;
            } else {
                $arrListReport[$itemReport['people_object_group_id']]['child'][$itemReport['people_object_id']]['data'] = [
                    'people_object_id' => $itemReport['people_object_id'],
                    'people_object_name' => $itemReport['people_object_name'],
                    'people_object_code' => $itemReport['people_object_code'],
                    'total' => 1
                ];
            }

            if (isset($arrListReport[$itemReport['people_object_group_id']]['child'][$itemReport['people_object_id']]['year'][$year])) {
                $arrListReport[$itemReport['people_object_group_id']]['child'][$itemReport['people_object_id']]['year'][$year] += 1;
            } else {
                $arrListReport[$itemReport['people_object_group_id']]['child'][$itemReport['people_object_id']]['year'][$year] = 1;
            }

            if (isset($arrListReport[$itemReport['people_object_group_id']]['child'][$itemReport['people_object_id']]['edu'][$itemReport['educational_level_id']])) {
                $arrListReport[$itemReport['people_object_group_id']]['child'][$itemReport['people_object_id']]['edu'][$itemReport['educational_level_id']] += 1;
            } else {
                $arrListReport[$itemReport['people_object_group_id']]['child'][$itemReport['people_object_id']]['edu'][$itemReport['educational_level_id']] = 1;
            }

        }

        //dd($arrListReport);

        return ['report' => $arrListReport, 'list_year' => $arrListYear];
    }

    public function export($filter)
    {
        $mPeopleVerify = new PeopleVerifyTable();
        $list = $mPeopleVerify->listReport($filter);

        $param = $filter;
        if ($param['type'] == 'people') {
            $mPeopleFamily = app()->get(PeopleFamilyTable::class);

            $dataList = [];

            if (count($list) > 0) {
                foreach ($list as $v) {
                    //Lấy thông tin cha
                    $v['info_father'] = $mPeopleFamily->getFamilyPeople($v['people_id'], 1);
                    //Lấy thông tin mẹ
                    $v['info_mother'] = $mPeopleFamily->getFamilyPeople($v['people_id'], 2);
                    //Lấy thông tin vợ/chồng
                    $v['info_partner'] = $mPeopleFamily->getFamilyPeople($v['people_id'], 5);
                    if(!$v['info_partner']){
                        $v['info_partner'] = $mPeopleFamily->getFamilyPeople($v['people_id'], 6);
                    }

                    $dataList [] = $v;
                }
            }

            $data['list'] = $dataList;

            $peopleObjectGroup = new PeopleObjectGroupTable();

            if ($param['people_object_group_id'] ?? false) {
                $result = $peopleObjectGroup->objectGroup(['people_object_group_id' => $param['people_object_group_id']]);
                $data['people_object_group_name'] = $result->toArray()['name'];
            }

            $data['people_verification_year'] = $param['people_verification_year'];

            if (ob_get_level() > 0) {
                ob_clean();
            }

            //return view('People::report.export', $data);
            return Excel::download(new ExcelExport('People::report.export', $data), 'bao_cao_cong_dan.xlsx');
        }

        $data = $this->_report($filter);
        //dd($data);
        $totalHeader = 4;
        $totalYear = 0;
        $totalEdu = 0;
        $heading = [
            __('STT'),
            __('Tên danh sách'),
            __('Mã số'),
            __('Số lượng công dân'),
//            __('Tổng số'),
//            __('TRÌNH ĐỘ HỌC VẤN'),
        ];

        foreach ($data['list_year'] as $year) {
            $heading[] = 'Năm sinh : ' . $year;
            $totalHeader++;
            $totalYear++;
        }

//        $heading[] = 'Tổng số';

        $mPeopleEducation = new EducationalLevelTable();
        $edu = $mPeopleEducation->getListByReport();

        foreach ($edu as $itemEdu) {
            $heading[] = 'TRÌNH ĐỘ HỌC VẤN : ' . $itemEdu['name'];
            $totalHeader++;
            $totalEdu++;
        }

        if (ob_get_level() > 0) {
            ob_clean();
        }

        $parse_column = [];
        if (count($data['report']) > 0) {
            $i = 1;

            foreach ($data['report'] as $itemObjectGroup) {
                foreach ($itemObjectGroup['child'] as $objectId => $itemObject) {
                    $parse_column[$i] = [
                        'stt' => $i,
                        'object' => ($itemObjectGroup['data']['people_object_group_name'] == $itemObject['data']['people_object_name']) ? ($itemObjectGroup['data']['people_object_group_name']) : ($itemObjectGroup['data']['people_object_group_name'] . ' || ' . $itemObject['data']['people_object_name']),
                        'code' => $itemObject['data']['people_object_code'],
                        'total_object' => $itemObject['data']['total']
                    ];

                    foreach ($data['list_year'] as $itemYear) {
                        if (isset($itemObject['year'][$itemYear])) {
                            $parse_column[$i]['year_' . $itemYear] = $itemObject['year'][$itemYear];
                        } else {
                            $parse_column[$i]['year_' . $itemYear] = '0';
                        }
                    }

//                    $parse_column[$i]['total_group'] = $itemObjectGroup['data']['total'];

                    foreach ($edu as $itemEdu) {
                        if (isset($itemObject['edu'][$itemEdu['educational_level_id']])) {
                            $parse_column[$i]['edu_' . $itemEdu['educational_level_id']] = $itemObject['edu'][$itemEdu['educational_level_id']];
                        } else {
                            $parse_column[$i]['edu_' . $itemEdu['educational_level_id']] = '0';
                        }
                    }

                    $i++;
                }

            }
        }

        //return view('People::report.report-excel');
        //return Excel::download(new ExcelExport('People::report.report-excel'), 'export-people-report.xlsx');
        return Excel::download(new ExportReportFile($heading, $parse_column, $totalHeader, $totalYear, $totalEdu), "export-people-report.xlsx");
    }


}

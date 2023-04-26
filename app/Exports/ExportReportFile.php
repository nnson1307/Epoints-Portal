<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
//https://viblo.asia/p/export-file-excel-voi-laravel-excel-31-djeZ1y9JZWz
class ExportReportFile extends DefaultValueBinder implements
    WithHeadings,
    FromArray,
    WithCustomValueBinder,
    ShouldAutoSize,
    WithStyles
{
    protected $heading;
    protected $data;
    protected $totalHeader;
    protected $totalYear;
    protected $totalEdu;

    public function __construct(array $heading, array $data, $totalHeader, $totalYear, $totalEdu)
    {
        $this->heading = $heading;
        $this->data = $data;
        $this->totalHeader = $totalHeader;
        $this->totalYear = $totalYear;
        $this->totalEdu = $totalEdu;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->heading;
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function styles(Worksheet $sheet)
    {
//        $alphabet = range('A', 'Z');
//
//        $line1A='';
//        $header = $this->totalHeader;
//        if($header > 24){
//            $header = $header - 24;
//            $line1A = 'A';
//        }
//        $line1A = $line1A.$alphabet[$header+1];
//
//
//        $line2E = '';
//        $year = $this->totalYear + 3;
//        if($year > 24){
//            $year = $year - 24;
//            $line2E = 'A';
//        }
//        $line2E = $line2E.$alphabet[$year+1];
//
//        $line2Edu = '';
//        $edu = $this->totalEdu + 3;
//        if($edu > 24){
//            $edu = $edu - 24;
//            $line2E = 'A';
//        }
//        $line2E = $line2E.$alphabet[$edu+1];
//
//        $sheet->mergeCells('A1:'.$line1A.'1');
//        $sheet->mergeCells('A2:A3');
//        $sheet->mergeCells('B2:B3');
//        $sheet->mergeCells('C2:C3');
//        $sheet->mergeCells('D2:D3');
//        $sheet->mergeCells('E2:'.$line2E.'2');
//        $sheet->mergeCells($alphabet[$year+2].'2:'.$alphabet[$year+2].'3');
////        $sheet->mergeCells('C2:D2');
////        $sheet->mergeCells('C3:D3');
////        $sheet->mergeCells('C4:D4');
////        $sheet->mergeCells('C5:D5');
////        $sheet->mergeCells('C6:D6');
////        $sheet->mergeCells('A7:D7');
//
//        $sheet->setCellValue('A1', 'BÁO CÁO CÔNG DÂN THEO ĐỘ TUỔI, TRÌNH ĐỘ HỌC VẤN');
//        $sheet->setCellValue('A2', 'STT');
//        $sheet->setCellValue('B2', 'Tên danh sách');
//        $sheet->setCellValue('C2', 'Mã số');
//        $sheet->setCellValue('D2', 'Số lượng công dân');
//        $sheet->setCellValue('E2', 'Năm sinh');
//        $sheet->setCellValue($alphabet[$year+2].'2', 'Tổng số');
////        $sheet->setCellValue('C3', 'Xin chao');
////        $sheet->setCellValue('C4', 'Xin chao');
////        $sheet->setCellValue('C5', 'Xin chao');
////        $sheet->setCellValue('C6', 'Xin chao');
//
////        foreach (range(1, 7) as $number) {
////            $sheet->getStyle('C' . $number)->getAlignment()->applyFromArray(
////                array('horizontal' => 'left')
////            );
////        }
    }
}

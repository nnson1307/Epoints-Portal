<?php

namespace Modules\People\Models;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ExcelExport implements FromView,ShouldAutoSize,WithEvents
{

	public $view;
	public $data;

	function __construct( $string,$data=array() ){
		$this->view = $string;
		$this->data = $data;
	}

    public function view(): View
    {
//        echo view($this->view , $this->data);
//        die;
        $response = view( $this->view , $this->data);

        return $response;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $styleArray = array(
                    'font'  => array(
                        'name'  => 'Verdana'
                    ));
                $event->getSheet()->getStyle('A1:ZZ100')->applyFromArray($styleArray);

            },
        ];
    }

}
<?php
namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Modules\Report\Models\ChatbotHistoryTable;
use Modules\Report\Models\ChatbotKeywordLogTable;

class WordCloudController extends Controller
{
    protected $history;
    protected $request;

    public function __construct(
        ChatbotHistoryTable $history,
        Request $request
    ) {
        $this->history = $history;
        $this->request = $request;
    }

    /**
     * Page Total Message Attribute Not Reponse
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        return view('report::word-cloud.index');
    }

    /**
     * Load Chart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartAction()
    {
        $param = $this->request->all();
        $mKeyword = new ChatbotKeywordLogTable();
        
        $keyword = $mKeyword->chartKeyword(isset($param['date_range']) ? $param['date_range'] : '');

        $arrKeyword = [];
        foreach ($keyword as $item) {
            $arrKeyword [] = [
                'name' => $item['keyword'],
                'weight' => $item['total'],
            ];
        }
        //dd($arrKeyword);
        return response()->json([
            'key_word' => $arrKeyword
        ]);
    }

    /**
     * Chart keyword
     *
     * @param $date_range
     * @return array
     */
    public function keyWord()
    {
        $param = $this->request->all();
        $mKeyword = new ChatbotKeywordLogTable();
        $keyword = $mKeyword->chartKeyword(isset($param['date_range']) ? $param['date_range'] : '');
        $arr = [];
        foreach ($keyword as $item) {
            $arr [] = [
                'name' => $item['keyword'],
                'weight' => $item['total'],
            ];
        }
        return $arr;
    }
    /**
     * Check number chart keyword trả về font size text
     *
     * @param $number
     * @return int
     */
    private function checkNumber($number)
    {
        switch ($number) {
            case $number < 10:
                return 4;
                break;
            case $number > 10 && $number < 20  :
                return 5;
                break;
            case $number > 20 && $number < 30  :
                return 6;
                break;
            case $number > 30 && $number < 40  :
                return 7;
                break;
            case $number > 40 && $number < 50  :
                return 8;
                break;
            case $number > 50 && $number < 60  :
                return 9;
                break;
            case $number > 60:
                return 10;
                break;
        }
    }
    public function exportWordCloud()
    {
        $param = $this->request->all();
        $mKeyword = new ChatbotKeywordLogTable();
        $keyword = $mKeyword->chartKeywordExport(isset($param['date-range']) ? $param['date-range'] : '');
        $data = [];
        $i=0;
        foreach ($keyword as $item) {
            $data [] = [
                'STT' => $i++,
                'weight' => $item['total'],
                'name' => $item['keyword'],
            ];
        }
        Excel::create('keyword', function ($excel) use ($data) {
            $excel->sheet('SheetName', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xlsx');
    }
}
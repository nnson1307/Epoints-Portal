<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-14
 * Time: 2:31 PM
 * @author SonDepTrai
 */

namespace Modules\Report\Http\Controllers;


namespace Modules\Report\Http\Controllers;

use App\Exports\ExportFile;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\ChatbotHistoryTable;

class MessageCompletionController extends Controller
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
     * View total message completion
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        return view('report::message-completion.index');
    }

    public function chartAction()
    {
        $param = $this->request->all();
        //Chart total confution attribute chưa có response
        $totalMessageNotResponse = $this->history->totalMessageChartCompletion(isset($param['date_range']) ? $param['date_range'] : '', true);
        $arr_total_message_not_response = [];
        foreach ($totalMessageNotResponse as $item) {
            $arr_total_message_not_response [] = [
                $item['attribute_name'],
                $item['total'],
                ''
            ];
        }
        return response()->json([
            'total_message_not_response' => $arr_total_message_not_response,
        ]);
    }

    public function exportMessageCompletion()
    {
        $param = $this->request->all();

        //Chart total confution attribute chưa có response
        $totalMessageCompletion = $this->history->totalMessageChartCompletion(isset($param['date-range']) ? $param['date-range'] : '', true, true);
        $data = [];
        foreach ($totalMessageCompletion as $key => $item) {
            $type_mess = 'Khác';
            if ($item['type'] == null && $item['response_forward'] == 0) {
                $type_mess = 'Message có response và config';
            } else if ($item['type'] == null && $item['response_forward'] == 1) {
                $type_mess = 'Điều hướng message chồng chép';
            } else if ($item['type'] == 'config_on_bot' || $item['type'] == 'config_off_bot') {
                $type_mess = 'Điều hướng menu';
            } else if ($item['attr_type'] == 'not_have_response') {
                $type_mess = 'Message có nhận diện attribute nhưng chưa có response';
            }

            $data [] = [
                 $key+1,
                 $item['query'],
                 $item['response_content'],
                 $item['request_time'],
                 $item['brand'],
                 $item['sku'],
                 $item['attribute'],
                 $item['brand_name'],
                 $item['attribute_name'],
                 $item['sku_name'],
                 $type_mess,
                 $item['ib_type']=='comment'? 'Inbox comment': 'Inbox message'
            ];
        }

        $heading = [
            'STT',
            'Nội dung Message',
            'Nội dung Response',
            'Thời gian',
            'Brand Entities',
            'SKU Entities',
            'Attribute Entities',
            'Keyword Brand',
            'Keyword Sku',
            'Keyword Attribute',
            'Loại Message',
            'Type Inbox'
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($heading, $data), 'attr-completion.xlsx');
    }
}
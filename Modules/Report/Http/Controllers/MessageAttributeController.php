<?php


namespace Modules\Report\Http\Controllers;


use App\Exports\ExportFile;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\ChatbotHistoryTable;

class MessageAttributeController
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
        return view('report::message-attribute.index');
    }

    /**
     * Load Chart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartAction()
    {
        $param = $this->request->all();
        //Chart total confution attribute chưa có response
        $totalMessageNotResponse = $this->history->totalMessageChartConfusion($param['date_range'], true);
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

    public function exportMessageAttributeNotResponseAction()
    {
        $param = $this->request->all();
        $totalMessageNotResponse = $this->history->totalMessageChartConfusion(isset($param['date-range']) ? $param['date-range'] : '', true, true)->toArray();
        $data = [];
        foreach ($totalMessageNotResponse as $key => $item) {
            if ($item['type'] == null && $item['response_forward'] == 0) {
                $type_mess = 'Completed Message';
            } else {
                $type_mess = 'Confusion Message';
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
                $type_mess
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
            'Loại Message'
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($heading, $data), 'attr-not-response.xlsx');
    }
}
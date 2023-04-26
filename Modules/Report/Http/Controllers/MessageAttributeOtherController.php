<?php


namespace Modules\Report\Http\Controllers;


use App\Exports\ExportFile;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\ChatbotHistoryTable;

class MessageAttributeOtherController
{
    protected $history;

    public function __construct(
        ChatbotHistoryTable $history
    ) {
        $this->history = $history;
    }

    /**
     * Page Total Message Attribute Other
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        return view('report::message-attribute-other.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartAction(Request $request)
    {
        //Chart total confution attribute chưa có response
        $totalMessageAttrOther = $this->history->totalMessageAttributeOther('');
        $arr_total_message_other = [];
        foreach ($totalMessageAttrOther as $item) {
            $arr_total_message_other [] = [
                $item['query'],
                $item['total'],
                ''
            ];
        }
        return response()->json([
            'total_message_other' => $arr_total_message_other,
        ]);
    }

    public function exportMessageAttributeOther()
    {
        $totalMessageAttrOther = $this->history->totalMessageAttributeOther('', true)->toArray();
        $data = [];
        foreach ($totalMessageAttrOther as $key => $item) {
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

        return Excel::download(new ExportFile($heading, $data), 'attr-other.xlsx');
    }
}
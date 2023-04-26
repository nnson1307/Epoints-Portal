<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\Ticket;

use App\Exports\ExportFile;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Ticket\Http\Api\SendNotificationApi;
use Modules\Ticket\Models\TicketAcceptanceTable;
use Modules\Ticket\Models\TicketLocationTable;
use Modules\Ticket\Models\TicketOperaterTable;
use Modules\Ticket\Models\TicketProcessorTable;
use Modules\Ticket\Models\TicketCommentTable;
use Modules\Ticket\Models\TicketTable;
use Modules\Ticket\Models\TicketHistoryTable;
use App\Http\Middleware\S3UploadsRedirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * @var TicketTable
     */
    protected $ticket;
    protected $timestamps = true;
    protected $s3Disk;

    public function __construct(TicketTable $ticket, S3UploadsRedirect $_s3)
    {
        $this->ticket = $ticket;
        $this->s3Disk = $_s3;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->ticket->getList($filters);
    }

    public function getTicketList(array $filters = [])
    {
        return $this->ticket->getTicketList($filters);
    }

    public function getTicketCreatedByMe(array $filters = [])
    {
        return $this->ticket->getTicketCreatedByMe($filters);
    }

    public function getTicketAssignMe(array $filters = [])
    {
        return $this->ticket->getTicketAssignMe($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->ticket->getAll($filters);
    }

    public function getName()
    {
        return $this->ticket->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->ticket->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->ticket->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->ticket->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->ticket->getItem($id);
    }

    // lấy số lượng ticket bằng status
    public function getTicketByStatus($status, $filters = [])
    {
        return $this->ticket->getTicketByStatus($status, $filters);
    }

    // số lượng ticket ass me
    public function getNumberTicketAssignMe()
    {
        return $this->ticket->getNumberTicketAssignMe();
    }

    // số lượng ticket tôi tạo
    public function getNumberTicketCreatedByMe()
    {
        return $this->ticket->getNumberTicketCreatedByMe();
    }

    // lấy danh sách ticket group theo queue + status
    public function getTicketProcessingList()
    {
        return $this->ticket->getTicketProcessingList();

    }

    //h sách ticket group theo queue + status quá hạn
    public function getTicketProcessingListExpired()
    {
        return $this->ticket->getTicketProcessingListExpired();
    }

    // lấy danh sách ticket chưa phân công
    public function getTicketUnAssign($queue_process_id)
    {
        return $this->ticket->getTicketUnAssign($queue_process_id);
    }

    // lấy danh sách mã ticket
    public function getTicketCode()
    {
        return $this->ticket->getTicketCode();
    }

//    Lấy danh sách biên bản nghiệm thu
    public function getListAcceptance($ticketId)
    {
        $mTicketAcceptance = new TicketAcceptanceTable();
        return $mTicketAcceptance->getListAcceptance($ticketId);
    }

    // lấy danh sách mã status
    public function dataSeries($data = [])
    {
        return $this->ticket->dataSeries($data);
    }

    public function getKPITicket($filters = [])
    {
        return $this->ticket->getKPITicket($filters);
    }

    public function countTicketByProcessor($filters = [])
    {
        return $this->ticket->countTicketByProcessor($filters);
    }

    public function getKPITicketTable($filters = [])
    {
        return $this->ticket->getKPITicketTable($filters);
    }

    /**
     * upload file
     * @param $data
     * @return mixed|void
     */
    public function uploadFile($input)
    {
        try {
            if ($input['file'] != null) {
                $fileName = $this->uploadImageS3($input['file'], '.');

                return [
                    'error' => false,
                    'file' => $fileName
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Tải file thất bại'
            ];
        }
    }


    /**
     * Lưu ảnh vào folder temp
     *
     * @param $file
     * @param $link
     * @return string
     */
    private function uploadImageS3($file, $link)
    {
        $time = Carbon::now();
        $idTenant = session()->get('idTenant');

        $to = $idTenant . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';

        $file_name =
            str_random(5) .
            rand(0, 9) .
            time() .
            date_format($time, 'd') .
            date_format($time, 'm') .
            date_format($time, 'Y') .
            $link .
            $file->getClientOriginalExtension();

        Storage::disk('public')->put($to . $file_name, file_get_contents($file), 'public');
        //Lấy real path trên s3
        return $this->s3Disk->getRealPath($to . $file_name);
    }

    /**
     * Lấy danh sách nhân viên
     * @param $ticketId
     * @return array
     */
    public function getListStaff($ticketId)
    {
        $mTicket = new TicketTable();
        $mProcessor = new TicketProcessorTable();

//        Lấy danh sách người chủ trì
        $getOperater = $mTicket->ticketDetailByTicket($ticketId);

        if ($getOperater != null && $getOperater['operate_by'] != null) {
            $listOperater = [$getOperater['operate_by']];
        } else {
            $listOperater = [];
        }
        $listProcessor = $mProcessor->getListProcessor($ticketId);
        if (count($listProcessor) != 0) {
            $listProcessor = collect($listProcessor)->pluck('staff_id');
        }

        $listArr = collect($listOperater)->merge($listProcessor)->toArray();
        $listArr = array_unique($listArr);
        $key = array_search(Auth::id(), $listArr);
        if ($key !== false) {
            unset($listArr[$key]);
        }
        return $listArr;
    }

    /**
     * Export excel ticket
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function exportExcel($input)
    {
        $filter = [];

        if (session()->get('array_filter')) {
            $filter = session()->get('array_filter');
        }

        $heading = [
            "#",
            __('Mã ticket'),
            __('Tiêu đề'),
            __('Khách hàng'),
            __('Số điện thoại'),
            __('Địa chỉ'),
            __('Độ ưu tiên'),
            __('Loại yêu cầu'),
            __('Yêu cầu'),
            __('Cấp độ yêu cầu'),
            __('Thời gian phát sinh'),
            __('Thời gian bắt buộc hoàn thành'),
            __('Queue'),
            __('Nhân viên chủ trì'),
            __('Nhân viên xử lý'),
            __('Nội dung'),
            __('Trạng thái')
        ];

        $data = [];

        //Lấy ds ticket
        $getTicket = $this->ticket->getDataExportExcel($filter);

        if (count($getTicket) > 0) {
            $mTicketProcessor = app()->get(TicketProcessorTable::class);

            foreach ($getTicket as $k => $v) {
                $priorityName = "";
                //Lấy độ ưu tiên
                switch ($v['priority']) {
                    case 'L':
                        $priorityName = __('Bình thường');
                        break;
                    case  'H':
                        $priorityName = __('Cao');
                        break;
                    case 'N':
                        $priorityName = __('Thấp');
                        break;
                }

                //Lấy nhân viên xử lý
                $getProcessor = $mTicketProcessor->getListProcessorByTicket($v['ticket_id']);

                $processor = '';

                if (count($getProcessor) > 0) {
                    foreach ($getProcessor as $k1 => $v1) {
                        $comma = ($k1 + 1) < count($getProcessor) ? ', ': '';

                        $processor .= $v1['staff_name'] . $comma;
                    }
                }

                $data [] = [
                    $k + 1,
                    $v['ticket_code'],
                    $v['title'],
                    $v['customer_name'],
                    $v['customer_phone'],
                    $v['customer_address'],
                    $priorityName,
                    $v['issue_group_name'],
                    $v['issue_name'],
                    $v['issue_level'],
                    Carbon::parse($v['date_issue'])->format('d/m/Y H:i'),
                    Carbon::parse($v['date_estimated'])->format('d/m/Y H:i'),
                    $v['queue_name'],
                    $v['operate_name'],
                    $processor,
                    $v['description'],
                    $v['status_name']
                ];
            }
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($heading, $data), 'ticket.xlsx');
    }

    /**
     * Lấy vị trí của ticket
     *
     * @param $idTicket
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function loadLocation($idTicket)
    {
        $mTicketLocation = app()->get(TicketLocationTable::class);

        //Lấy vị trí của ticket
        return $mTicketLocation->getLocationByTicket($idTicket);
    }


     /**
     * Thêm bình luận
     * @param $data
     * @return mixed|void
     */
    public function addComment($data)
    {
        try {
            $mTicketComment = new TicketCommentTable();
            $mMTicketHistory = new TicketHistoryTable();
            $comment = [
                'message' => $data['description'],
                'ticket_id' => $data['ticket_id'],
                'ticket_parent_comment_id' => isset($data['ticket_comment_id']) ? $data['ticket_comment_id'] : null,
                'staff_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];
            //Thêm bình luận ticket
            $idComment = $mTicketComment->createdComment($comment);

            $detailComment = $mTicketComment->getDetail($idComment);

            //Gửi notify bình luận ticket
            $listCustomer = $this->getListStaff($data['ticket_id']);

            $mNoti = new SendNotificationApi();

            foreach ($listCustomer as $item) {
                if ($item != Auth()->id()) {
                    $mNoti->sendStaffNotification([
                        'key' => 'ticket_comment_new',
                        'customer_id' => Auth()->id(),
                        'object_id' => $data['ticket_id']
                    ]);
                }
            }

            $view = view('manager-work::managerWork.append.append-message', ['detail' => $detailComment, 'data' => $data])->render();

              // tạo lịch sử
            //   $note = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' đã bình luận ' );
            //   $note_en = createATag(route('admin.staff.show', Auth::id()), Auth::user()->full_name) . ' approved the material requisition form ');
            //   $mMTicketHistory($note,$note_en, $data['ticket_id']);

            return [
                'error' => false,
                'message' => __('Thêm bình luận thành công'),
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm bình luận thất bại')
            ];
        }
    }

    /**
     * hiển thị form comment
     * @param $data
     * @return mixed|void
     */
    public function showFormComment($data)
    {
        try {

            $view = $view = view('ticket::ticket.append.append-form-chat', ['ticket_comment_id' => $data['ticket_comment_id']])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị form trả lời thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách comment
     * @param $id
     * @return mixed|void
     */
    public function getListComment($id)
    {
        $mManageComment = new TicketCommentTable();
        $listComment = $mManageComment->getListCommentTicket($id);
        foreach ($listComment as $key => $item) {
            $listComment[$key]['child_comment'] = $mManageComment->getListCommentTicket($id, $item['ticket_comment_id']);
        }
        return $listComment;
    }

}

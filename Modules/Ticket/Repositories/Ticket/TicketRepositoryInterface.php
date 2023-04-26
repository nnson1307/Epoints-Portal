<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\Ticket;


interface TicketRepositoryInterface
{
    /**
     * Get ticket list
     *
     * @param array $filters
     */
    public function list(array $filters = []);

     /**
     * Get all
     *
     * @param array $all
     */
    public function getAll(array $filters = []);

     /**
     * Get lisst option
     *
     * @param null
     */
    public function getName();

     /**
     * Get all
     *
     * @param array $all
     */
    public function getTicketList(array $filters = []);
    
    /**
     * Get getTicketCreatedByMe
     *
     * @param array $getTicketCreatedByMe
     */
    public function getTicketCreatedByMe(array $filters = []);
    
    /**
     * Get getTicketAssignMe
     *
     * @param array $getTicketAssignMe
     */
    public function getTicketAssignMe(array $filters = []);

    /**
     * Delete ticket
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add ticket
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update ticket
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);

    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);

    // lấy số lượng ticket bằng status
    public function getTicketByStatus($status,$filters);

    // số lượng ticket ass me
    public function getNumberTicketAssignMe();

    // số lượng ticket tôi tạo
    public function getNumberTicketCreatedByMe();
    
    // lấy danh sách ticket group theo theo queue + status
    public function getTicketProcessingList();

    //danh sách ticket group theo theo queue + status quá hạn
    public function getTicketProcessingListExpired();

    // lấy danh sách ticket chưa phân công
    public function getTicketUnAssign($queue_process_id);
    
    // lấy danh sách mã ticket
    public function getTicketCode();

//    Lấy danh sách biên bản nghiệm thu
    public function getListAcceptance($ticketId);

    public function dataSeries($data = []);
    // lấy danh sách kpi của nv
    public function getKPITicket($filters = []);
    // đếm số ticket xử lý sự cố & triển khai của người chủ trì
    public function countTicketByProcessor($filters = []);
    // lấy danh sách kpi nhân viên qua bảng
    public function getKPITicketTable($filters = []);
    /**
     * Upload file
     * @param $data
     * @return mixed
     */
    public function uploadFile($data);


    /**
     * lấy danh sách nhân viên
     * @param $ticketId
     * @return mixed
     */
    public function getListStaff($ticketId);

    /**
     * Export excel ticket
     *
     * @param $input
     * @return mixed
     */
    public function exportExcel($input);

    /**
     * Lấy vị trí của ticket
     *
     * @param $idTicket
     * @return mixed
     */
    public function loadLocation($idTicket);
}
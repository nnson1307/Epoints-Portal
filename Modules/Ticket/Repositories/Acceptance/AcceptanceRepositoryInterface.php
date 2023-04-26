<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:35 PM
 */

namespace Modules\Ticket\Repositories\Acceptance;


interface AcceptanceRepositoryInterface
{
    /**
     * Get Acceptance list
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
     * Get all
     *
     * @param array $all
     */
    public function getName();

    /**
     * Delete Acceptance
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Add Acceptance
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Update Acceptance
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

    /**
     * Lấy danh sách ticket chưa có biên bản nghiệm thu
     * @return mixed
     */
    public function getListTicketNotAcceptance($ticket_acceptance_id = null);

    /**
     * Lấy thông tin khi thay đổi ticket
     * @param $data
     * @return mixed
     */
    public function changeTicket($data);

    /**
     * Show popup thêm vật tư phát sinh
     * @return mixed
     */
    public function showPopupAddProduct($data);

    /**
     * Lưu sản phẩm đã chọn vào danh sách
     * @param $data
     * @return mixed
     */
    public function addProductIncurredList($data);

    /**
     * Select lấy danh sách vậy tư
     * @param $data
     * @return mixed
     */
    public function listProductSelect($data);

    /**
     * Tạo biên bản nghiệm thu
     * @param $data
     * @return mixed
     */
    public function createAcceptance($data);

    /**
     * Chỉnh sửa biên bản nghiệm thu
     * @param $data
     * @return mixed
     */
    public function editAcceptance($data);

    /**
     * Lấy danh sách vật tư phát sinh
     * @return mixed
     */
    public function listIncurred($ticket_acceptance_id);

    /**
     * Lấy danh sách file đính kèm
     * @param $ticketId
     * @return mixed
     */
    public function getListFile($ticketId);

    /**
     * Lấy danh sách khách hàng để search
     * @return mixed
     */
    public function getListCustomerSelect();

}
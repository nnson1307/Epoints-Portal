<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 2:24 PM
 */

namespace Modules\CallCenter\Repositories\CallCenter;


interface CallCenterRepoInterface
{
     /*
    *Lấy danh sách tiếp nhận
    */
    public function getListCustomerRequest(array $filters = []);

    /*
    *Lấy thông tin tiếp nhận
    */
    public function getInfoCustomerRequest($id);

    /*
    *Tìm kiếm khách hàng
    */
    public function searchCustomer($keyWord);
    
    /*
    *Lây dánh sách tỉnh thành phố
    */
    public function getOptionProvince();

    /*
    *lấy danh sách pipeline
    */
    public function getOptionPipeline();

    /*
    *lấy danh sách nhân viên
    */
    public function getOptionStaff();

    /*
    *Lấy danh sách hành trình 
    */
    public function loadOptionJourney($pipelineCode);

    /*
    *Lấy danh sách nguồn khách hàng
    */
    public function loadCustomerSource();

    /*
    *Tạo yêu cầu khách hàng với trường hợp không có thông tin khách hàng
    */
    public function createCustomerRequestNotInfo($input);

    /*
    *Tạo yêu cầu khách hàng
    */
    public function createCustomerRequest($input);

    /*
    *Lấy thông tin khách hàng tiềm năng
    */
    public function getInfoCustomerLead($id);

    /*
    *Lấy thông tin khách hàng
    */
    public function getInfoCustomer($id);

    /*
    *Lấy dánh sách deal
    */
    public function getListDealLeadDetail(array $filters = []);

    /*
    *Lấy dánh sách hợp đồng
    */
    public function getListContract($customerId);

    /**
     * Lấy tổng tiếp nhận theo tháng
     */
    public function getTotalByMonth($month, $years);

    /**
     * Lấy tổng tiếp nhận từng nhân viên theo tháng
     */
    public function getTotalStaffByMonth($month, $years);
}
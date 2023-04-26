<?php


namespace Modules\CustomerLead\Repositories\Report;


interface ReportRepoInterface
{

    /**
     * lấy dữ liệu cho báo cáo chất lượng lead theo nguồn khách hàng
     *
     * @param $pipeCatCode
     * @return mixed
     */
    public function getListPipeline($pipeCatCode);

    // LEAD

    /**
     * Render view table báo cáo lead theo nguồn khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function dataViewLeadReportCS($input);

    /**
     * Export excel view lead report cs
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelViewLeadReportCs($input);

    /**
     * Export excel view lead report staff
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelViewLeadReportStaff($input);

    /**
     * Popup ds khách hàng báo cáo lead theo nguồn khách hàng
     *
     * @param $filter
     * @return mixed
     */
    public function dataPopupLeadReportCS(&$filter);

    /**
     * Export excel popup list lead report
     *
     * @param $filter
     * @return mixed
     */
    public function ExportExcelPopupLeadReportCS($filter);

    /**
     * Export view deal report staff
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelViewDealReportStaff($input);
    /**
     * Render view table báo cáo lead theo nguồn nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function dataViewLeadReportStaff($input);

    // DEAL
    /**
     * Render view table báo cáo deal theo nguồn nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function dataViewDealReportStaff($input);

    /**
     * data popup list detail deal of deal report staff
     *
     * @param $filter
     * @return mixed
     */
    public function dataPopupDealReportStaff(&$filter);

    /**
     * Export excel pop
     *
     * @param $filter
     * @return mixed
     */
    public function ExportExcelPopupDealReportStaff($filter);
    // CONVERT

    /**
     * Render view table báo cáo chuyển đổi
     *
     * @param $input
     * @return mixed
     */
    public function dataViewReportConvert($input);

    /**
     * Export view report convert
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelViewReportConvert($input);

    /**
     * Vẽ chart lead
     * @param $input
     * @return mixed
     */
    public function getDataChartLead($input);

    public function getDataChartDeal($input);

    /**
     * Phân trang danh sách lead
     * @param $input
     * @return mixed
     */
    public function tableLeadSearch($input);

    public function tableDealSearch($input);

    /**
     * Phân trang danh sách nguồn
     * @param $input
     * @return mixed
     */
    public function tableSourceSearch($input);

    /**
     * Thay đổi phòng ban lấy dánh sách nhân viên
     * @param $input
     * @return mixed
     */
    public function changeDepartment($input);

}
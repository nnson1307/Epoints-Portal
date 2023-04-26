<?php


namespace Modules\CustomerLead\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\DepartmentTable;
use Modules\CustomerLead\Models\StaffsTable;
use Modules\CustomerLead\Repositories\Report\ReportRepoInterface;

class ReportController extends Controller
{
    protected $report;
    public function __construct(
        ReportRepoInterface $report
    ) {
        $this->report = $report;
    }

    /**
     * View báo cáo chất lượng LEAD theo nguồn khách hàng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function leadReportCustomerSource()
    {
        $optionPipeline = $this->report->getListPipeline('CUSTOMER');
        $mCustomerSource = new CustomerSourceTable();
        $optionCs = $mCustomerSource->getOption();
        $data = [
          'optionPipeline' => $optionPipeline,
          'optionCs' => $optionCs
        ];
        return view('customer-lead::report.lead-report-cs', $data);
    }

    /**
     * Render table báo cáo LEAD theo nguồn khách hàng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderViewLeadReportCS(Request $request)
    {
        $data = $this->report->dataViewLeadReportCS($request->all());

        return response()->json($data);
    }

    /**
     * Render popup list lead
     *
     * @param Request $request
     * @return mixed
     */
    public function renderPopupLeadReportCS(Request $request)
    {
        $filter = $request->all();
        $list = $this->report->dataPopupLeadReportCS($filter);
        return \View::make('customer-lead::report.modal.modal-lead-report-cs', [
            'LIST' => $list,
            'FILTER' => $filter,
        ]);
    }

    /**
     * Data popup list lead
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listRenderPopupLeadReportCS(Request $request)
    {
        $filter = $request->all();
        $list = $this->report->dataPopupLeadReportCS($filter);
        return view('customer-lead::report.modal.list-customer-lead-report-cs', [
            'LIST' => $list,
            'FILTER' => $filter,
            'page' => $filter['page']
        ]);
    }
    /**
     * Export excel view lead report cs
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelViewLeadReportCs(Request $request)
    {
        return $this->report->exportExcelViewLeadReportCs($request->all());
    }

    /**
     * Export excel view lead report staff
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelViewLeadReportStaff(Request $request)
    {
        return $this->report->exportExcelViewLeadReportStaff($request->all());
    }

    /**
     * Export pop lead report cs
     *
     * @param Request $request
     * @return mixed
     */
    public function ExportExcelPopupLeadReportCS(Request $request)
    {
        return $this->report->ExportExcelPopupLeadReportCS($request->all());
    }

    /**
     * Export view deal report staff
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelViewDealReportStaff(Request $request)
    {
        return $this->report->exportExcelViewDealReportStaff($request->all());
    }
    /**
     * View báo cáo chất lượng LEAD theo nhân viên
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function leadReportStaff()
    {
        $optionPipeline = $this->report->getListPipeline('CUSTOMER');
        $mStaff = new StaffsTable();
        $optionStaff = $mStaff->getStaffOption();
        $data = [
            'optionPipeline' => $optionPipeline,
            'optionStaff' => $optionStaff
        ];
        return view('customer-lead::report.lead-report-staff', $data);
    }

    /**
     * Render table báo cáo LEAD theo nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderViewLeadReportStaff(Request $request)
    {
        $data = $this->report->dataViewLeadReportStaff($request->all());

        return response()->json($data);
    }

    /**
     * View báo cáo chất lượng DEAL theo nhân viên
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function dealReportStaff()
    {
        $optionPipeline = $this->report->getListPipeline('DEAL');
        $mStaff = new StaffsTable();
        $optionStaff = $mStaff->getStaffOption();
        $data = [
            'optionPipeline' => $optionPipeline,
            'optionStaff' => $optionStaff
        ];
        return view('customer-lead::report.deal-report-staffs', $data);
    }

    /**
     * Render table báo cáo DEAL theo nhân viên
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderViewDealReportStaff(Request $request)
    {
        $data = $this->report->dataViewDealReportStaff($request->all());

        return response()->json($data);
    }

    /**
     * render popup list detail deal
     *
     * @param Request $request
     * @return mixed
     */
    public function renderPopupDealReportStaff(Request $request)
    {
        $filter = $request->all();
        $list = $this->report->dataPopupDealReportStaff($filter);
        return \View::make('customer-lead::report.modal.modal-deal-report-staff', [
            'LIST' => $list,
            'FILTER' => $filter,
        ]);
    }

    /**
     * data of popup list detail deal
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listRenderPopupDealReportStaff(Request $request)
    {
        $filter = $request->all();
        $list = $this->report->dataPopupDealReportStaff($filter);
        return view('customer-lead::report.modal.list-customer-deal-report-staff', [
            'LIST' => $list,
            'FILTER' => $filter,
            'page' => $filter['page']
        ]);
    }

    /**
     * Popup deal report staff khi click con số
     *
     * @param Request $request
     * @return mixed
     */
    public function ExportExcelPopupDealReportStaff(Request $request)
    {
        return $this->report->ExportExcelPopupDealReportStaff($request->all());
    }
    /**
     * Báo cáo tỉ lể chuyển đổi từ LEAD sang deal theo nguồn kinh doanh
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function reportConvert()
    {
        $optionPipeline = $this->report->getListPipeline('CUSTOMER');

        $mCustomerSource = new CustomerSourceTable();
        $optionCs = $mCustomerSource->getOption();
        $data = [
            'optionPipeline' => $optionPipeline,
            'optionCs' => $optionCs
        ];
        return view('customer-lead::report.report-convert', $data);
    }

    /**
     * Render view table báo cáo chuyển đổi
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderViewConvert(Request $request)
    {
        $data = $this->report->dataViewReportConvert($request->all());

        return response()->json($data);
    }

    /**
     * Popup report convert khi click con số
     *
     * @param Request $request
     * @return mixed
     */
    public function renderPopupReportConvert(Request $request)
    {
        $filter = $request->all();
        $filter['is_convert'] = 1;
        $list = $this->report->dataPopupLeadReportCS($filter);
        return \View::make('customer-lead::report.modal.modal-lead-report-cs', [
            'LIST' => $list,
            'FILTER' => $filter,
        ]);
    }

    /**
     * Ds dữ liệu report covnert của popup khi click con số
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listRenderPopupReportConvert(Request $request)
    {
        $filter = $request->all();
        $filter['is_convert'] = 1;
        $list = $this->report->dataPopupLeadReportCS($filter);
        return view('customer-lead::report.modal.list-customer-lead-report-cs', [
            'LIST' => $list,
            'FILTER' => $filter,
            'page' => $filter['page']
        ]);
    }

    /**
     * Export view báo cáo phễu chuyển đổi
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelViewReportConvert(Request $request)
    {
        return $this->report->exportExcelViewReportConvert($request->all());
    }

    /**
     * Báo cáo phiễu KH
     */
    public function reportFunnel(){
        $optionPipeline = $this->report->getListPipeline('CUSTOMER');
        $mCustomerSource = new CustomerSourceTable();
        $mDepartment = app()->get(DepartmentTable::class);
        $mStaff = app()->get(StaffsTable::class);
        $optionCs = $mCustomerSource->getOption();
        $optionDepartment = $mDepartment->getOption();
        $optionStaff = $mStaff->getStaffOption();

        $data = [
            'optionPipeline' => $optionPipeline,
            'optionCs' => $optionCs,
            'optionDepartment'=> $optionDepartment,
            'optionStaff' => $optionStaff
        ];

        return view('customer-lead::report.funnel-index', $data);
    }

    /**
     * Lấy data chart lead
     * @param Request $request
     */
    public function getDataChartLead(Request $request){
        $param = $request->all();

        $data = $this->report->getDataChartLead($param);
        return response()->json($data);
    }

    /**
     * @param Request $request
     */
    public function tableLeadSearch(Request $request){
        $param = $request->all();

        $data = $this->report->tableLeadSearch($param);
        return response()->json($data);
    }

    public function tableSourceSearch(Request $request){
        $param = $request->all();

        $data = $this->report->tableSourceSearch($param);
        return response()->json($data);
    }

//    ---------------------------------------------------------------------------------------

    /**
     * Báo cáo phiễu KH
     */
    public function reportFunnelDeal(){
        $optionPipeline = $this->report->getListPipeline('DEAL');
        $mDepartment = app()->get(DepartmentTable::class);
        $mStaff = app()->get(StaffsTable::class);
        $optionDepartment = $mDepartment->getOption();
        $optionStaff = $mStaff->getStaffOption();

        $data = [
            'optionPipeline' => $optionPipeline,
            'optionDepartment'=> $optionDepartment,
            'optionStaff' => $optionStaff
        ];

        return view('customer-lead::report.funnel-deal', $data);
    }

    /**
     * Lấy data chart lead
     * @param Request $request
     */
    public function getDataChartDeal(Request $request){
        $param = $request->all();

        $data = $this->report->getDataChartDeal($param);
        return response()->json($data);
    }

    /**
     * @param Request $request
     */
    public function tableDealSearch(Request $request){
        $param = $request->all();

        $data = $this->report->tableDealSearch($param);
        return response()->json($data);
    }

    public function changeDepartment(Request $request){
        $param = $request->all();
        $data = $this->report->changeDepartment($param);
        return response()->json($data);
    }
}
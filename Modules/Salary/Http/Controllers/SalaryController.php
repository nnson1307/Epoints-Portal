<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\Salary\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Salary\Http\Requests\EditSalaryRequest;
use Modules\Salary\Http\Requests\EditSalarySaveRequest;
use Modules\Salary\Repositories\Salary\SalaryInterface;
use Modules\Salary\Http\Requests\Salary\StoreRequest;
// use Modules\Ticket\Http\Requests\Refund\UpdateRequest;

class SalaryController extends Controller
{
    protected $salaryRepo;
    public function __construct(SalaryInterface $salaryInterface)
    {
        $this->salaryRepo = $salaryInterface;
    }

    public function testJob(){
        $this->salaryRepo->createSalary(60);
    }

    public function indexAction(Request $request)
    {
        $filters = $request->only(['page','salary_period','department_id','created_at','created_by','updated_at','updated_by']);
        return view('Salary::salary.index', $this->salaryRepo->list($filters));
    }

    public function tableSalaryDetail($id,Request $request)
    {
        $filters = $request->only(['page','department_id','created_at','created_by','updated_at','updated_by','staff_id']);
        return view('Salary::salary.detail_salary', $this->salaryRepo->tableSalaryDetail($id,$filters));
    }

    public function addAction(StoreRequest $request)
    {
        $params = $request->all();
        $data = $this->salaryRepo->addAction($params);
        return response()->json($data);
    }
    
    /**
     * Link tạm
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function export()
    {
        return view('Salary::salary.index-export');
    }

    /**
     * Xuất excel bảng lương
     */
    public function exportExcelSalary(){
        return $this->salaryRepo->exportExcelSalary();
    }

    /**
     * Import excel
     * @param Request $request
     * @return mixed
     */
    public function importExcelSalary(Request $request){
        return $this->salaryRepo->importExcelSalary($request->all());
    }

    /**
     * Khoá lương
     */
    public function lockSalary(Request $request){
        $data = $this->salaryRepo->lockSalary($request->all());
        return response()->json($data);
    }

    /**
     * Hiển thị popup cập nhật lương
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalEditSalary(Request $request){
        $data = $this->salaryRepo->showModalEditSalary($request->all());
        return response()->json($data);
    }

    /**
     * Cập nhật tên bảng lương
     * @param EditSalaryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editSalary(EditSalaryRequest $request){
        $data = $this->salaryRepo->editSalary($request->all());
        return response()->json($data);
    }

    /**
     * Trang cập nhật thông tin lương
     * @param Request $request
     */
    public function salaryEdit($id){
        $data = $this->salaryRepo->getDetailSalaryStaff($id);

        if (isset($data['error'])){
            return redirect()->route('salary');
        }

        return view('Salary::salary.salary_staff.salary_edit',$data);
    }

    /**
     * Lưu bảng lương
     */
    public function editSalarySave(EditSalarySaveRequest $request){
        $data = $this->salaryRepo->editSalarySave($request->all());
        return response()->json($data);
    }

    /**
     * Hiển thị table hợp đồng
     * @param Request $request
     */
    public function showTableCommission(Request $request){
        $data = $this->salaryRepo->showTableCommission($request->all());
        return response()->json($data);
    }

    /**
     * Export 
     * @param Request $request
     */
    public function exportExcelSalaryCommission(Request $request){
        return $this->salaryRepo->exportExcelSalaryCommission($request->all());
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 018/04/2022
 * Time: 10:46
 */

namespace Modules\StaffSalary\Http\Controllers;

use Modules\StaffSalary\Repositories\SalaryAllowance\SalaryAllowanceRepoInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffSalaryAllowanceController extends Controller
{
    protected $staffSalaryAllowance;

    public function __construct(SalaryAllowanceRepoInterface $staffSalaryAllowance)
    {
        $this->staffSalaryAllowance = $staffSalaryAllowance;
    }

    public function index(Request $request)
    {
        $list = $this->staffSalaryAllowance->getList();
        return view('staff-salary::staff-salary-allowance.index', [
            'LIST' => $list,
        ]);
    }


    /**
     * Show modal add allowance
     *
     * @return mixed
     */
    public function showModalAddAllowanceAction()
    {
        $html = \View::make('staff-salary::staff-salary-allowance.add')->render();
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Show modal add allowance
     *
     * @return mixed
     */
    public function showModalEditAllowanceAction(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->staffSalaryAllowance->getDetail($request->staff_holiday_id);
            $html = \View::make('staff-salary::staff-salary-allowance.edit', ['data' => $data])->render();
            return response()->json([
                'html' => $html
            ]);
        }
       
    }

    /**
     * add allowance
     *
     * @return mixed
     */
    public function addAction(Request $request)
    {
        if ($request->ajax()) {

            $data = [
                'salary_allowance_name' => $request->salary_allowance_name,
            ];
           
            $id = $this->staffSalaryAllowance->add($data);
            if($id > 0){
                return response()->json(
                    [
                        'status'   => 1,
                        'message'  => 'Thêm mới thành công'
                    ]
                );
            }
            return response()->json(
                [
                    'status'   => 0,
                    'message'  => 'Thêm mới thất bại'
                ]
            );
        }
    }

    /**
     * edit allowance
     *
     * @return mixed
     */
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            
            $data = [
                'salary_allowance_name' => $request->salary_allowance_name,
            ];
            $id = $this->staffSalaryAllowance->edit($data, $request->salary_allowance_id);
            if($id > 0){
                return response()->json(
                    [
                        'status'   => 1,
                        'message'  => 'Chỉnh sửa thành công'
                    ]
                );
            }
            return response()->json(
                [
                    'status'   => 0,
                    'message'  => 'Chỉnh sửa thất bại'
                ]
            );
        }
    }
}
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
use Modules\Salary\Repositories\SalaryCommissionConfig\SalaryCommissionConfigInterface;

class SalaryCommissionConfigController extends Controller
{
    protected $salary_commission_config;

    public function __construct(SalaryCommissionConfigInterface $salary_commission_config)
    {
        $this->salary_commission_config = $salary_commission_config;
    }

    public function indexAction(Request $request)
    {
        $filters = $request->only(['page','department_id','created_at','created_by','updated_at','updated_by']);
        return view('Salary::salary_commission_config.index',$this->salary_commission_config->list($filters));
    }

    public function addAction(Request $request)
    {
        $params = $request->all();
        $data = $this->salary_commission_config->addAction($params);
        return response()->json($data);
    }
    public function addView()
    {
        $data = $this->salary_commission_config->addView();
        return response()->json($data);
    }

    public function editAction(Request $request)
    {
        $params = $request->all();
        $data = $this->salary_commission_config->editAction($params);
        return response()->json($data);
    }
    
    public function submitAction(Request $request)
    {
        $params = $request->all();
        $data = $this->salary_commission_config->submitAction($params);
        return response()->json($data);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->salary_commission_config->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
}
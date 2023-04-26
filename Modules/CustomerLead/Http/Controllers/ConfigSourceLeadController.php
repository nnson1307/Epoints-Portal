<?php

namespace Modules\CustomerLead\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CustomerLead\Http\Requests\Config\SaveRequest;
use Modules\CustomerLead\Repositories\ConfigSourceLead\ConfigSourceLeadRepoInterface;

class ConfigSourceLeadController extends Controller
{
    private $configSourceLead;

    public function __construct(ConfigSourceLeadRepoInterface $configSourceLead){
        $this->configSourceLead = $configSourceLead;
    }

    public function index(){
       
        $data = $this->configSourceLead->getList([]);
        $listDepartment = $this->configSourceLead->listDepartment();
        $listTeam = $this->configSourceLead->listTeam();
        return view('customer-lead::config-source-lead.index', [
            'LIST' => $data['list'],
            'department' => $listDepartment['listDepartment'],
            'listTeam' => $listTeam

        ]);
    }

    public function list(Request $request){
        $param = $request->all();
        $data = $this->configSourceLead->getList($param);
        return view('customer-lead::config-source-lead.list', [
            'LIST' => $data['list'],
        ]);
    }

    public function showPopup(Request $request){
        $param = $request->all();
        $data = $this->configSourceLead->showPopup($param);
        return response()->json($data);
    }

    /**
     * Lưu cấu hình
     * @param Request $request
     */
    public function saveConfig(SaveRequest $request){
        $param = $request->all();
        $data = $this->configSourceLead->saveConfig($param);
        return response()->json($data);
    }

    public function destroy(Request $request){
        $param = $request->all();
        $data = $this->configSourceLead->destroy($param);
        return response()->json($data);
    }
}
<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/15/2021
 * Time: 3:26 PM
 * @author nhandt
 */


namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Repositories\RoleData\ContractRoleDataRepoInterface;

class ContractRoleDataController extends Controller
{
    private $roleData;
    public function __construct(ContractRoleDataRepoInterface $roleData)
    {
        $this->roleData = $roleData;
    }
    public function indexAction(Request $request)
    {
        $data = $this->roleData->dataViewIndex();
        return view('contract::role-data.index', $data);
    }
    public function submitConfigAction(Request $request)
    {
        return $this->roleData->submitConfigAction($request->all());
    }
}
<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/15/2021
 * Time: 3:32 PM
 * @author nhandt
 */


namespace Modules\Contract\Repositories\RoleData;



use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\RoleGroupTable;
use Modules\Contract\Models\ContractRoleDataConfigTable;

class ContractRoleDataRepo implements ContractRoleDataRepoInterface
{
    private $roleData;
    public function __construct(ContractRoleDataConfigTable $roleData)
    {
        $this->roleData = $roleData;
    }

    public function dataViewIndex()
    {
        $mRoleGroup = new RoleGroupTable();
        $optionRoleGroup = $mRoleGroup->getOptionRoleContractActive();
        return [
          "optionRoleGroup" => $optionRoleGroup
        ];
        // TODO: Implement dataViewIndex() method.
    }

    public function submitConfigAction($data)
    {
        try{
            $this->roleData->deleteData();
            if(isset($data['listConfig'])){
                if(count($data['listConfig']) > 0){
                    $dataInsert = [];
                    foreach ($data['listConfig'] as $key => $value){
                        $dataInsert[] = [
                            'role_group_id' => $value['role_group_id'],
                            'role_data_type' => $value['role_data_type'],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'created_at' => date('Y-m-d H:i'),
                            'updated_at' => date('Y-m-d H:i'),
                        ];
                    }
                    $this->roleData->createData($dataInsert);
                }
            }
            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        }
        catch (\Exception $ex){
            return [
                'error' => false,
                'message' => $ex->getMessage()
            ];
        }
    }
}
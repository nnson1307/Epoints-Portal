<?php


namespace Modules\ManagerProject\Repositories\ManagePhase;


use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\ManagerProject\Models\ManagePhaseTable;
use Modules\ManagerProject\Models\ManageProjectPhareTable;

class ManagePhaseRepository implements ManagePhaseInterfaceRepository
{
    private $mManagePhase;
    public function __construct(ManageProjectPhareTable $mManagePhase)
    {
        $this->mManagePhase = $mManagePhase;
    }

    /**
     * Lấy danh sách nhóm template mẫu
     * @return mixed|void
     */
    public function getListGroupPhase()
    {
        return $this->mManagePhase->getAllGroup();
    }

    /**
     * lấy danh sách theo code
     * @param $code
     * @return mixed|void
     */
    public function getListPhase($code)
    {
        return $this->mManagePhase->getAllByCode($code);
    }

    /**
     * Thay đổi template
     * @param $data
     * @return mixed|void
     */
    public function changeSample($data)
    {
        try {
            $list = $this->mManagePhase->getAllByCode($data['manage_phase_group_code']);
            $view = view('manager-project::phase.append.list-phase',['listPhase' => $list])->render();

            return [
                'error' => false,
                'message' => __('Thay đổi mẫu thất bại'),
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Thay đổi mẫu thất bại')
            ];
        }
    }
}

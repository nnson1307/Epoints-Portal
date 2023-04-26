<?php


namespace Modules\ManagerProject\Repositories\ManagePhase;


interface ManagePhaseInterfaceRepository
{
    /**
     * Lấy danh sách template mẫu
     * @return mixed
     */
    public function getListGroupPhase();

    /**
     * lấy danh sách theo code
     * @return mixed
     */
    public function getListPhase($code);

    /**
     * Thay đổi template
     * @param $data
     * @return mixed
     */
    public function changeSample($data);
}
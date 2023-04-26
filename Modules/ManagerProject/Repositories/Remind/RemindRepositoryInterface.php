<?php


namespace Modules\ManagerProject\Repositories\Remind;


interface RemindRepositoryInterface
{
    /**
     * Hiển thị popup nhắc nhở
     * @param $data
     * @return mixed
     */
    public function showPopupRemindPopup($data);

    /**
     * Thêm nhắc nhở
     * @param $data
     * @return mixed
     */
    public function addRemindWork($data);
}
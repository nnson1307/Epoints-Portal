<?php


namespace Modules\ManagerProject\Repositories\ManageHistory;


interface ManageHistoryRepoInterface
{

    /**
     * lấy danh sách nhân viên theo dự án
     * @param $data
     * @return mixed
     */
    public function getListStaff($data);

    /**
     * Tìm kiếm lịch sử
     * @param $data
     * @return mixed
     */
    public function searchHistory($data);
}
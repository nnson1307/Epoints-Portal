<?php


namespace Modules\CustomerLead\Repositories\ConfigSourceLead;


interface ConfigSourceLeadRepoInterface
{
    /**
     * Lấy danh sách cấu hình
     * @param array $filter
     * @return mixed
     */
    public function getList($filter = []);

    /**
     * Lấy danh sách phòng ban
     * @return mixed
     */
    public function listDepartment();

    /**
     * Danh sách nhóm marketing
     * @return mixed
     */
    public function listTeam();

    /**
     * Hiẻn thị popup
     * @param $input
     * @return mixed
     */
    public function showPopup($input);

    /**
     * Lưu cấu hình
     * @param $input
     * @return mixed
     */
    public function saveConfig($input);

    /**
     * Xóa cấu hình
     * @param $input
     * @return mixed
     */
    public function destroy($input);
}
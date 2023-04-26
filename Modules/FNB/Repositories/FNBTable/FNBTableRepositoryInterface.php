<?php


namespace Modules\FNB\Repositories\FNBTable;


interface FNBTableRepositoryInterface
{
    public function getList(array $filter = []);
    /**
     * the ban
     * @param $dataTable
     * @return mixed
     */
    public function showPopup($data);
    public function createTable($dataTable);

    /**
     * chinh sua ban
     * @param $dataEditTable
     * @return mixed
     */
    public function editTable($dataEditTable);

    /**
     * Lấy danh sách phân trang custom
     * @param array $filter
     * @return mixed
     */
    public function getListPagination(array $filter = []);

    /**
     * Lấy danh sách theo template
     * @param $idCodeTemplate
     * @return mixed
     */
    public function getListTableByTemplate($idCodeTemplate,$apply_for);
    /**
     * xóa bàn
     * @param $input
     * @return mixed
     */
    public function deleteTable($input);

    /**
     * xuất dữ liệu bàn
     * @param $data
     * @return mixed
     */
    public function export($data);
}
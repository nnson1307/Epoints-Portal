<?php


namespace Modules\FNB\Repositories\FNBAreas;


interface FNBAreasRepositoryInterface
{
    public function getList(array $filter = []);

    /**
     * them khu vuc
     * @param $dataAreas
     * @return mixed
     */
    public function createAreas($input);

    /**
     * chinh sua khu vuc
     * @param $dataEditAreas
     * @return mixed
     */
    public function editAreas($input);

    /**
     * Custom phân trang
     * @param array $filter
     * @return mixed
     */
    public function getListPagination(array $filter = []);

    /**
     * Lấy danh sách
     * @return mixed
     */
    public function getAll();

    public function showPopup($data);

    /**
     * xóa khu vực
     *
     * @param $input
     * @return mixed
     */
    public function deleteAreas($input);

    public function getAllAreas();


    /**
     * export data
     * @param $data
     * @return mixed
     */
    public function export($data);


}

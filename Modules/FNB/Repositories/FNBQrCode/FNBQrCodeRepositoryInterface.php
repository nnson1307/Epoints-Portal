<?php


namespace Modules\FNB\Repositories\FNBQrCode;


interface FNBQrCodeRepositoryInterface
{
    /**
     * Lấy danh sách có phân trang
     * @param array $filter
     * @return mixed
     */
    public function getList(array $filter = []);

    /**
     * Lưu cấu hình QR Code
     * @param $data
     * @return mixed
     */
    public function submitQrCode($data);

    /**
     * Lấy thông tin tọa độ
     * @return mixed
     */
    public function getClientIp();

    /**
     * lấy chi tiết qr code
     * @param $id
     * @return mixed
     */
    public function getDetail($id);

    /**
     * Xuất dữ liệu
     * @param $data
     * @return mixed
     */
    public function export($data);

    /**
     * Render qr code
     * @param $data
     * @return mixed
     */
    public function viewQrCode($data);

    public function preview($data);

    /**
     * Lấy danh sách mã QR
     * @param $idQrTemplate
     * @return mixed
     */
    public function getListQrCode($idQrTemplate);

    /**
     * Xóa template
     * @param $idQrTemplate
     * @return mixed
     */
    public function remove($idQrTemplate);

    /**
     * Cập nhật trạng thái
     * @param $data
     * @return mixed
     */
    public function update($data);

    /**
     * Lấy danh sách table theo template
     * @param $idCodeTemplate
     * @return mixed
     */
    public function getListTableByTemplate($idCodeTemplate);

    public function uploadImage($input);
}
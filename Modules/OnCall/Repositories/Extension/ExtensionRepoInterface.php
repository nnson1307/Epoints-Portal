<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/07/2021
 * Time: 13:46
 */

namespace Modules\OnCall\Repositories\Extension;


interface ExtensionRepoInterface
{
    /**
     * Show modal account
     *
     * @return mixed
     */
    public function showModalAccount();

    /**
     * Cấu hình tài khoản
     *
     * @param $input
     * @return mixed
     */
    public function submitSetting($input);

    /**
     * Đồng bộ dữ liệu extension
     *
     * @return mixed
     */
    public function syncExtension();

    /**
     * Lấy danh sách extension
     *
     * @param $input
     * @return mixed
     */
    public function list($input = []);

    /**
     * Lấy option trang danh sách
     *
     * @return mixed
     */
    public function getOption();

    /**
     * Show modal phân bổ nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function showModalAssign($input);

    /**
     * Phân bổ nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function submitAssign($input);

    /**
     * Cập nhật trạng thái
     *
     * @param $input
     * @return mixed
     */
    public function updateStatus($input);

    public function getModalCalling($input);
    public function submitCareFromOncall($input);
    public function searchWorkLead($input);
    public function getInfoDeal($input);
}
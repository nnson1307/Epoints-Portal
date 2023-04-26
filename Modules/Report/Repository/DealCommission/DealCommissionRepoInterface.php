<?php

namespace Modules\Report\Repository\DealCommission;

interface DealCommissionRepoInterface
{
    /**
     * filter time
     *
     * @param $input
     * @return mixed
     */
    public function filterAction($input);
    /**
     * Danh sách chi tiết hoa hồng cho deal
     *
     * @param $input
     * @return mixed
     */
    public function listDetail($input);
    /**
     * Export excel chi tiết hoa hồng cho deal
     *
     * @param $input
     * @return mixed
     */
    public function exportDetail($input);

    /**
     * Export excel tổng hoa hồng cho deal
     *
     * @param $input
     * @return mixed
     */
    public function exportTotal($input);
}
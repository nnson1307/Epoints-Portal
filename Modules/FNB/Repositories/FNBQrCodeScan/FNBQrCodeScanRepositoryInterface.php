<?php


namespace Modules\FNB\Repositories\FNBQrCodeScan;


interface FNBQrCodeScanRepositoryInterface
{
    public function getListPagination(array $filter = []);

    /**
     * Lấy tổng số scan theo template
     * @param $idTemplate
     * @return mixed
     */
    public function getTotalScan($idTemplate);
}
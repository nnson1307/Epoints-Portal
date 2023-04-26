<?php


namespace Modules\FNB\Repositories\FNBQrCodeScan;


use Modules\FNB\Models\FNBQrCodeScanTable;

class FNBQrCodeScanRepository implements FNBQrCodeScanRepositoryInterface
{
    private $mQrCodeScan;

    public function __contruct(FNBQrCodeScanTable $mQrCodeScan){
        $this->mQrCodeScan = $mQrCodeScan;
    }

    /**
     * Lấy danh sách scan table có phân trang
     * @param array $filter
     */
    public function getListPagination(array $filter = [])
    {
        $mQrCodeScan = app()->get(FNBQrCodeScanTable::class);
        $list = $mQrCodeScan->getList($filter);

        $view = view('fnb::qr-code.append.append-table',[
            'list' => $list
        ])->render();
        return [
            'error' => false,
            'view' => $view
        ];
    }

    /**
     * Tổng số scan theo bàn
     * @param $idTemplate
     * @return mixed|void
     */
    public function getTotalScan($idTemplate)
    {
        $mQrCodeScan = app()->get(FNBQrCodeScanTable::class);
        $totalScan = $mQrCodeScan->getTotalScan($idTemplate);
        return $totalScan;
    }
}
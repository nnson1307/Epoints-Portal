<?php


namespace Modules\FNB\Repositories\FNBQrTemplateLogo;


interface FNBQrTemplateLogoRepositoryInterface
{
    /**
     * Lấy danh sách logo
     * @return mixed
     */
    public function getListLogo();

    /**
     * Thêm logo
     * @param $data
     * @return mixed
     */
    public function insertLogo($data);
}
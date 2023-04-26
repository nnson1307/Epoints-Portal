<?php


namespace Modules\FNB\Repositories\FNBQrTemplateLogo;


use Modules\FNB\Models\FNBQrTemplateLogoTable;

class FNBQrTemplateLogoRepository implements FNBQrTemplateLogoRepositoryInterface
{
    private $mQrTemplateLogo;

    public function __contruct(FNBQrTemplateLogoTable $mQrTemplateLogo){
        $this->mQrTemplateLogo = $mQrTemplateLogo;
    }

    /**
     * Lấy danh sách logo
     * @return mixed|void
     */
    public function getListLogo()
    {
        $mQrTemplateLogo = app()->get(FNBQrTemplateLogoTable::class);
        return $mQrTemplateLogo->getAll();
    }

    /**
     * Thêm logo
     * @param $data
     * @return mixed|void
     */
    public function insertLogo($data)
    {
        $mQrTemplateLogo = app()->get(FNBQrTemplateLogoTable::class);
        return $mQrTemplateLogo->insertLogo($data);
    }
}
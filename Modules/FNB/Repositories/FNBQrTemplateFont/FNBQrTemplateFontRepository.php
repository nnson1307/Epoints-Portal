<?php


namespace Modules\FNB\Repositories\FNBQrTemplateFont;


use Modules\FNB\Models\FNBQrTemplateFontTable;

class FNBQrTemplateFontRepository implements FNBQrTemplateFontRepositoryInterface
{
    private $font;

    public function __contruct(FNBQrTemplateFontTable $fontTable){
        $this->font = $fontTable;
    }

    /**
     * Lấy danh sách font
     * @return mixed|void
     */
    public function getListFont()
    {
        $font = app()->get(FNBQrTemplateFontTable::class);
        return $font->getListFont();
    }
}
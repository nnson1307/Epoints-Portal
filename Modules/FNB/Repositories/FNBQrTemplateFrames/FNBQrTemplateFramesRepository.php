<?php


namespace Modules\FNB\Repositories\FNBQrTemplateFrames;


use Modules\FNB\Models\FNBQrTemplateFramesTable;

class FNBQrTemplateFramesRepository implements FNBQrTemplateFramesRepositoryInterface
{
    private $mQrTemplateFrame;

    public function __contruct(FNBQrTemplateFramesTable $mQrTemplateFrame){
        $this->mQrTemplateFrame = $mQrTemplateFrame;
    }

    public function getListFrames()
    {
        $mQrTemplateFrame = app()->get(FNBQrTemplateFramesTable::class);
        return $mQrTemplateFrame->getAll();
    }
}
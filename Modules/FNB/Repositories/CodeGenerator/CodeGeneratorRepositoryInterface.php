<?php


namespace Modules\FNB\Repositories\CodeGenerator;


interface CodeGeneratorRepositoryInterface
{
    public function codeDMY($string, $stt);

    public function generateCardListCode();
}
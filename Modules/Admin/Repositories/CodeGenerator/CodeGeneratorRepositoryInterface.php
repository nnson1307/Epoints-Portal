<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/12/2018
 * Time: 10:54 AM
 */

namespace Modules\Admin\Repositories\CodeGenerator;


interface CodeGeneratorRepositoryInterface
{
    public function generateServiceCardCode($str_append);

    public function generateCardListCode();

    public function generateCodeRandom($str_append);
    public function codeDMY($string, $stt);
}
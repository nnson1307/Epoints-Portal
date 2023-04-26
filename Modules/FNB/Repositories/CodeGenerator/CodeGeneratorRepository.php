<?php


namespace Modules\FNB\Repositories\CodeGenerator;


class CodeGeneratorRepository implements CodeGeneratorRepositoryInterface
{

    //Code theo chữ cái đầu và stt tự tăng.
    public function codeDMY($string, $stt)
    {
        $time = date("dmY");
        return $string . '_' . $time . $stt;
    }

    public function generateCardListCode()
    {
        // TODO: Implement generateCardListCode() method.
        $text = "";
//        $text_2 = "";
        // TODO: Implement generateServiceCardCode() method.
        $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        for ($i = 0; $i < 3; $i++) {
            $text .= $string[rand(0, (strlen($string) - 1))];

        }
//        for ($i = 0; $i < 2; $i++) {
//            $text_2 .= $string[rand(0, (strlen($string) - 1))];
//        }
        $str_uniq = uniqid($text);
        return strtoupper($str_uniq);
    }
}
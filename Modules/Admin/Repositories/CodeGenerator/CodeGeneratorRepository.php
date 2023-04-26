<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/12/2018
 * Time: 10:54 AM
 */

namespace Modules\Admin\Repositories\CodeGenerator;


class CodeGeneratorRepository implements CodeGeneratorRepositoryInterface
{

    public function generateServiceCardCode($str_append)
    {
        $text = "";
        // TODO: Implement generateServiceCardCode() method.
        $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str_append = str_slug($str_append) . "_";
        $str_uniq = uniqid($str_append);

        for ($i = 0; $i < 5; $i++) {
            $text .= $string[rand(0, (strlen($string) - 1))];
        }

        return strtoupper($str_uniq) . $text;
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

    public function generateCodeRandom($str_append)
    {
        $text = "";
        // TODO: Implement generateServiceCardCode() method.
        $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str_append = str_slug($str_append) . "_";
        $str_uniq = uniqid($str_append);

        for ($i = 0; $i < 15; $i++) {
            $text .= $string[rand(0, (strlen($string) - 1))];
        }
        return strtoupper($str_uniq) . $text;
    }
    //Code theo chữ cái đầu và stt tự tăng.
    public function codeDMY($string, $stt)
    {
        $time = date("dmY");
        return $string . '_' . $time . $stt;
    }
}
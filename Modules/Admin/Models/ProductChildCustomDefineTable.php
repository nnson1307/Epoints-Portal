<?php

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class ProductChildCustomDefineTable extends Model
{
    protected $table = "product_child_custom_define";

    /**
     * Lấy thông tin define
     *
     * @return mixed
     */
    public function getDefine()
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "key",
                "title_$lang as title",
                "type"
            )
            ->get();
    }
    public function getDefineDetail($key){
        $lang = app()->getLocale();
        $data = $this->select(
            "type",
            "title_$lang as title"
        )
            ->where("key","=",$key)->first();
        return $data;
    }
}
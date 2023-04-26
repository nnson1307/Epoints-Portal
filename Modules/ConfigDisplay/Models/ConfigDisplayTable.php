<?php

/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/21/2021
 * Time: 11:11 AM
 * @author nhandt
 */


namespace Modules\ConfigDisplay\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ConfigDisplayTable extends Model
{
    use ListTableTrait;

    protected $table = "config_display";
    protected $primaryKey = "id_config_display";
    protected $fillable = [
        "id_config_display",
        "name_page",
        "position_page",
        "title_page",
        'key_page',
        "type_template",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVE = 1;
    const IS_DELETED = 0;
    const TYPE_TEMPLATE = [
        'slide_banner',
        'slide_product',
        'trade_marketing',
        'card',
        'slide_CTA'
    ];
    // start query //
    public function getListCore(array $filters = [])
    {
        $select = $this->select(
            'id_config_display',
            'name_page',
            'position_page',
            'title_page',
            'type_template',
            'created_at',
            'updated_at'
        );
        return $select;
    }
    // end query //
}

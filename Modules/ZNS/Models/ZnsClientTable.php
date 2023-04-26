<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/09/2022
 * Time: 13:49
 */

namespace Modules\ZNS\Models;


use Illuminate\Database\Eloquent\Model;

class ZnsClientTable extends Model
{
    protected $table = "zns_client";
    protected $primaryKey = "zns_client_id";

    /**
     * Lấy client zns
     *
     * @return mixed
     */
    public function getClient()
    {
        return $this->first();
    }
}
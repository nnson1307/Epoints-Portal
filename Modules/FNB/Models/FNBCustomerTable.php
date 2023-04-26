<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:24 AM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class FNBCustomerTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_customer';
    protected $primaryKey = 'fnb_customer_id';
    protected $fillable
        = [
            'fnb_customer_id',
            'token',
            'customer_id',
            'phone',
            'imei',
            'request_ip',
            'browser',
            'device',
            'created_at'
        ];


}
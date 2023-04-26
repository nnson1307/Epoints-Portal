<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/30/2021
 * Time: 5:04 PM
 * @author nhandt
 */


namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerLogUpdateTable extends Model
{
    protected $table = "customer_log_update";
    protected $primaryKey = "customer_log_update_id";
    protected $fillable = [
        "customer_log_update_id",
        "customer_log_id",
        "key_table",
        "key",
        "value_old",
        "value_new",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    public function insertLog($data)
    {
        return $this->insert($data);
    }

}
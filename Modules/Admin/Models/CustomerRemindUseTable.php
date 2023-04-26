<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/06/2021
 * Time: 14:53
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerRemindUseTable extends Model
{
    protected $table = "customer_remind_use";
    protected $primaryKey = "customer_remind_use_id";
    protected $fillable = [
        "customer_remind_use_id",
        "customer_id",
        "order_id",
        "object_type",
        "object_id",
        "object_code",
        "object_name",
        "sent_at",
        "is_sent",
        "is_queue",
        "created_at",
        "updated_at"
    ];
}
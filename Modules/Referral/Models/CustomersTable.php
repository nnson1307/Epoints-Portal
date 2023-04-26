<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class CustomersTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";


}

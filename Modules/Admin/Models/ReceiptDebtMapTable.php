<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptDebtMapTable extends Model
{
    protected $table = "receipt_debt_maps";
    protected $primaryKey = "receipt_debt_map_id";
    protected $fillable = [
        'receipt_debt_map_id',
        'receipt_id',
        'customer_debt_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
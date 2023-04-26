<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class UserTable extends Model
{
    use ListTableTrait;
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_id',
        'full_name',
        'email',
        'phone1',
    ];

    /**
     * Lấy tất cả
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->get();
    }
}

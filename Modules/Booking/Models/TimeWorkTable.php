<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/4/2019
 * Time: 3:10 PM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class TimeWorkTable extends Model
{
    protected $table = "time_working";
    protected $primaryKey = "id";

    protected $fillable = [
        'id', 'eng_name', 'vi_name', 'is_actived', 'start_time',
        'end_time', 'updated_by', 'created_at', 'updated_at'
    ];

    public function getTimeWork()
    {
        $select = $this->select('id', 'eng_name', 'vi_name', 'is_actived', 'start_time', 'end_time')
            ->get();
        return $select;
    }
}
<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class TimeResetRankTable extends Model
{
    protected $table = 'time_insert_reset_rank';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'value',
        'type',
        'created_at',
        'updated_at'
    ];

    /**
     * @param $type
     * @return mixed
     */
    public function getTimeByType($type)
    {
        $ds = $this
            ->select(
                'id',
                'value',
                'type'
            )
            ->where('type', $type)
            ->first();
        return $ds;
    }
}
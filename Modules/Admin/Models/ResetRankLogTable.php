<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ResetRankLogTable extends Model
{
    use ListTableTrait;
    protected $table = 'reset_rank_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'customer_id',
        'time_reset_rank_id',
        'month_reset',
        'member_level_id',
        'member_level_old_id',
        'created_at',
        'updated_at'
    ];

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }
}
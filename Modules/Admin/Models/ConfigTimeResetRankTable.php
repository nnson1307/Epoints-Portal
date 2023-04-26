<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class ConfigTimeResetRankTable extends Model
{
    use ListTableTrait;
    protected $table = 'config_time_reset_rank';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'value',
        'type',
        'is_deleted',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
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

    /**
     * @return mixed
     */
    public function removeAll()
    {
        return $this->where('is_deleted', 0)->update(['is_deleted' => 1, 'created_by' => Auth::id()]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * @param array $filter
     * @return mixed
     */
    protected function _getList($filter = [])
    {
        $ds = $this
            ->select(
                'id',
                'name',
                'value',
                'type'
            )
            ->where('is_deleted', 0);
        return $ds;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getItemByType($type)
    {
        $ds = $this
            ->select(
                'id',
                'name',
                'value',
                'type'
            )
            ->where('type', $type)
            ->where('is_deleted', 0)
            ->first();
        return $ds;
    }
}
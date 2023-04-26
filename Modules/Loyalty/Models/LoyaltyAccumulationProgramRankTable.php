<?php

namespace Modules\Loyalty\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class LoyaltyAccumulationProgramRankTable extends Model
{
    use ListTableTrait;
    public $timestamps = false;
    protected $table = 'loy_accumulation_program_rank';
    protected $primaryKey = 'accumulation_program_rank_id';
    protected $fillable
        = [
            'accumulation_program_rank_id',
            'accumulation_program_id',
            'rank_id',
            'accumulation_point',
            'available_point',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];

    /**
     * function create new accumulate point
     * @param array $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->accumulation_program_rank_id;
    }

    /**
     * get detail by accumulation_program_id
     * @param int $id
     * @return mixed
    */
    public function getDetailByID($id)
    {
        $select = $this->select(
            $this->table.'.*',
            'loy_rank.rank_name'
        )
            ->where($this->table.'.accumulation_program_id',$id)
            ->where('loy_rank.is_active', 1)
            ->where('loy_rank.is_deleted', 0)
            ->leftjoin('loy_rank', 'loy_rank.rank_id', '=', $this->table.'.rank_id')
            ->get();
        return $select;
    }

    public function removeData($id)
    {
        return $this->where($this->table.'.accumulation_program_id', $id)->delete();
    }

    public function getAllAccumulationProgram()
    {
        $select = $this->select(
            $this->table.'.accumulation_program_id'
        )
            ->get();
        return $select;
    }

    public function createInsertArray($data)
    {
        return $this->insert($data);
    }
}
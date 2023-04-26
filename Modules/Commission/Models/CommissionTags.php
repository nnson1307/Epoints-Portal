<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CommissionTags
 * @author HaoNMN
 * @since Jun 2022
 */
class CommissionTags extends Model
{
    protected $table      = 'commission_tags';
    protected $primaryKey = 'commission_tags_id';
    protected $fillable = [
        'commission_tags_id',
        'commission_id',
        'tags_id'
    ];


    /**
     * Lấy danh sách tag theo id hoa hồng
     * @param $commission_id
     * @return mixed
     */
    public function listTagByCommissionId($commission_id)
    {
        return $this->select('t.tags_name')
                    ->leftJoin('tags as t', 't.tags_id', '=', "{$this->table}.tags_id")
                    ->where("{$this->table}.commission_id", $commission_id)
                    ->pluck('t.tags_name')
                    ->toArray();
    }

    /**
     * Lấy tag theo id hoa hồng
     */
    public function getTagByCommissionId($commission_id)
    {
        return $this->select('t.tags_name')
                    ->leftJoin('tags as t', 't.tags_id', '=', "{$this->table}.tags_id")
                    ->where("{$this->table}.commission_id", $commission_id)
                    ->pluck('t.tags_name');
    }

    /**
     * Thêm mới dòng map hoa hồng và tag
     */
    public function addCommissionTag($data)
    {
        return $this->insert($data);
    }
}

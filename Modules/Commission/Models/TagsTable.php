<?php

namespace Modules\Commission\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TagsTable
 * @author HaoNMN
 * @since Jun 2022
 */
class TagsTable extends Model
{
    protected $table      = 'tags';
    protected $primaryKey = 'tags_id';
    protected $fillable = [
        'tags_id',
        'tags_name'
    ];

    /**
     * Lấy danh sách tag
     */
    public function listTag()
    {
        return $this->get()->toArray();
    }
}

<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class FaqGroupTable extends Model
{
    use ListTableTrait;

    protected $table = 'faq_group';
    protected $primaryKey = 'faq_group_id';
    protected $fillable = [
        'faq_group_id',
        'parent_id',
        'faq_group_title',
        'faq_group_type',
        'faq_group_position',
        'is_actived',
        'is_deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * Lấy danh sách faq group có phân trang
     *
     * @param array $filters
     * @return mixed
     */
    protected function getListCore(array $filters = [])
    {
        $result = $this->leftJoin('faq_group as fgrparent', function ($join) {
            $join->on('fgrparent.faq_group_id', $this->table.'.parent_id')
                ->where('fgrparent.is_deleted', 0);
        })
            ->where($this->table.'.is_deleted', 0)
            ->select(
                $this->table.'.faq_group_id',
                $this->table.'.parent_id',
                $this->table.'.faq_group_title',
                $this->table.'.faq_group_type',
                $this->table.'.faq_group_position',
                $this->table.'.is_actived',
                'fgrparent.faq_group_title as faq_group_parent_title'
            );

        return $result;
    }

    /**
     * Lấy toàn bộ danh sách faq group không phân trang
     *
     * @param array $filters
     * @return mixed
     */
    public function getListAll(array $filters = [])
    {
        $result = $this->getListCore();

        if (count($filters) > 0) {
            foreach ($filters as $column => $value) {
                if ($column == 'notin') {
                    $result->whereNotIn($this->table.'.'.$this->primaryKey, $value);
                } elseif ($column == 'in') {
                    $result->whereIn($this->table.'.'.$this->primaryKey, $value);
                } else {
                    $result->where($this->table.'.'.$column, $value);
                }
            }
        }

        return $result->get();
    }

    /**
     * Lấy thông tin chi tiết faq group
     *
     * @param int $faq_group_id
     * @return mixed
     */
    public function detail($faq_group_id)
    {
        $result = $this->getListCore();
        $result->where($this->table.'.'.$this->primaryKey, $faq_group_id);

        return $result->first();
    }

    /**
     * Thêm faq group
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Chỉnh sửa nhóm nội dung
     *
     * @param array $data
     * @param int|array $condition
     * @return mixed
     */
    public function edit(array $data, $condition)
    {
        if (is_array($condition)) {
            $result = $this->where($condition)->update($data);
        } else {
            $result = $this->where($this->primaryKey, $condition)->update($data);
        }

        return $result;
    }

    /**
     * Đánh dấu xóa nhóm nội dung
     *
     * @param $id
     * @return mixed
     */
    public function remove($id)
    {
        $result = $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);

        return $result;
    }

    /**
     * Kiểm tra có phải danh mục mặc định hay không
     *
     * @param int $id
     * @return bool
     */
    public function checkDefault($id)
    {
        $result = $this->where('faq_group_type', 'default')
            ->where($this->primaryKey, $id)
            ->count();

        return ($result > 0);
    }
}

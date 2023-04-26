<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class FaqTable extends Model
{
    use ListTableTrait;

    protected $table = 'faq';
    protected $primaryKey = 'faq_id';
    protected $fillable = [
        'faq_id',
        'faq_group',
        'faq_type',
        'faq_title',
        'faq_content',
        'faq_position',
        'is_actived',
        'is_deleted',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * Quan hệ 1-N
     *
     * @by Minh
     */
    public function faqGroup()
    {
        return $this->belongsTo('Modules\Admin\Models\FaqGroupTable', 'faq_group', 'faq_group_id');
    }

    /**
     * Lấy danh sách câu hỏi hỗ trợ có phân trang
     *
     * @param array $filters
     * @return mixed
     */
    protected function getListCore(array $filters = [])
    {
        $result = $this->leftJoin('faq_group as fgr', 'fgr.faq_group_id', '=', $this->table.'.faq_group')
            ->where($this->table.'.is_deleted', 0)
            ->select(
                $this->table.'.faq_id',
                $this->table.'.faq_group',
                $this->table.'.faq_type',
                $this->table.'.faq_title',
                $this->table.'.faq_content',
                $this->table.'.faq_position',
                $this->table.'.created_at',
                $this->table.'.is_actived',
                'fgr.faq_group_title'
            );

        return $result;
    }

    /**
     * Lấy danh sách chính sách bảo mật và điều khoản sử dụng
     *
     * @param array $filters
     * @return mixed
     */
    public function getListPolicyTerms(array $filters = [])
    {
        $result = $this->leftJoin('faq_group as fgr', 'fgr.faq_group_id', '=', $this->table.'.faq_group')
            ->where($this->table.'.is_deleted', 0)
            ->where($this->table.'.faq_type', '<>', 'faq')
            ->select(
                $this->table.'.faq_id',
                $this->table.'.faq_group',
                $this->table.'.faq_type',
                $this->table.'.faq_title',
                $this->table.'.faq_content',
                $this->table.'.faq_position',
                $this->table.'.created_at',
                'fgr.faq_group_title'
            );

        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        unset($filters['perpage']);
        unset($filters['page']);

        if ($filters) {
            // filter list
            foreach ($filters as $key => $val) {
                if (trim($val) == ''||trim($val) == null) {
                    continue;
                }
                if (strpos($key, 'keyword_') !== false) {
                    $result->where(str_replace('$', '.', str_replace('keyword_', '', $key)), 'like', '%' . $val . '%');
                } elseif (strpos($key, 'sort_') !== false) {
                    $result->orderBy(str_replace('$', '.', str_replace('sort_', '', $key)), $val);
                } else {
                    $result->where(str_replace('$', '.', $key), $val);
                }
            }
        }

        return $result->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Lấy danh sách câu hỏi hỗ trợ không phân trang
     *
     * @param array $filters
     * @return mixed
     */
    public function getListAll(array $filters = [])
    {
        $result = $this->getListCore();

        if (count($filters) > 0) {
            foreach ($filters as $column => $value) {
                $result->where($this->table.'.'.$column, $value);
            }
        }

        return $result->get();
    }

    /**
     * Thêm câu hỏi hỗ trợ
     *
     * @param array $data
     * @return int
     */
    public function add(array $data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Lấy chi tiết câu hỏi hỗ trợ
     *
     * @param int $id
     * @return mixed
     */
    public function detail($id)
    {
        $result = $this->getListCore();
        $result->where($this->table.'.'.$this->primaryKey, $id);

        return $result->first();
    }

    /**
     * Chỉnh sửa thông tin câu hỏi hỗ trợ
     *
     * @param array $data
     * @param array|int $condition
     * @return boolean
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
     * Đánh dấu xóa câu hỏi hỗ trợ
     *
     * @param array|int $condition
     * @return boolean
     */
    public function remove($condition)
    {
        if (is_array($condition)) {
            $result = $this->where($condition)->update(['is_deleted' => 1]);
        } else {
            $result = $this->where($this->primaryKey, $condition)->update(['is_deleted' => 1]);
        }

        return $result;
    }

    /**
     * Kiểm tra chi tiết nội dung thuộc loại trang đã tồn tại chưa
     *
     * @param $faqType
     * @return mixed
     */
    public function checkFaqType($faqType)
    {
        $result = $this->where('faq_type', $faqType)
            ->where('is_deleted', 0)
            ->get();

        return $result;
    }

    /**
     * Lấy danh sách cho modal notification
     * @by Minh
     * @param $filter
     */
    public function getListModal($filter = null)
    {
        $list = $this->with('faqGroup')->where('is_deleted', 0)->where($this->table.'.faq_type',  'faq');

        if (isset($filter['title']) && $filter['title'] != null) {
            $list->where('faq_title', 'like', '%'.$filter['title'].'%');
        }
        if (isset($filter['group']) && $filter['group'] != null && $filter['group'] != -1) {
            $group = $filter['group'];
            $list->whereHas('faqGroup', function ($query) use ($group) {
                $query->where('faq_group_id', $group);
            });
        }
        if (isset($filter['active']) && $filter['active'] != null && $filter['active'] != -1) {
            $list->where('is_actived', $filter['active']);
        }
        $page = (isset($filter['page'])) ? $filter['page'] : 1;
        $nonPaging = $list->orderBy('created_at', 'desc')->get();
        $paging = $list->orderBy('created_at', 'desc')->paginate(
            END_POINT_PAGING,
            ['*'],
            'page',
            $page
        );

        return ['non_paging' => $nonPaging, 'paging' => $paging];
    }
}

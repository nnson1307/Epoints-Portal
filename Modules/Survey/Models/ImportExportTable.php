<?php


namespace Modules\Survey\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ImportExportTable extends Model
{
    use ListTableTrait;
    protected $table = 'import_export';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable
        = [
            'id', 'code', 'type', 'source', 'description', 'status',
            'link_source', 'link_result', 'is_update_null', 'is_update',
            'created_at', 'created_by', 'upload_type'
        ];

    /**
     * Thêm mới
     *
     * @param array $data
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Danh sách import by status
     *
     * @param $status
     *
     * @return mixed
     */
    public function getByStatus($status, $uploadType)
    {
        $select = $this->select($this->fillable);
        if ($status != null) {
            $select->where($this->table . '.status', $status);
        }
        if ($uploadType != null) {
            $select->where($this->table . '.upload_type', $uploadType);
        }
        return $select->get();
    }

    /**
     * Cập nhật thông tin.
     * @param array $data
     * @param       $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * Số lượng trong ngày
     * @param $dateTime
     * @param $uploadType
     * @return mixed
     */
    public function getByDay($dateTime, $uploadType)
    {
        $select = $this->select($this->fillable)
            ->whereDate($this->table . '.created_at', $dateTime);
        if ($uploadType != null) {
            $select->where($this->table . '.upload_type', $uploadType);
        }
        return $select->get()->count();
    }

    /**
     * Danh sách lịch sử.
     * @param array $filter
     *
     * @return mixed
     */
    public function getListCore(&$filter = [])
    {
        $select = $this->select($this->table . '.*');
        if (isset($filter['created_at']) && count($filter['created_at']) == 2) {
            $select->whereBetween($this->table . '.created_at', $filter['created_at']);
            unset($filter['created_at']);
        }
        if (isset($filter['size_from']) && $filter['size_from'] != null) {
            $select->where('size', '>', $filter['size_from']);
            unset($filter['size_from']);
        }
        if (isset($filter['size_to']) && $filter['size_to'] != null) {
            $select->where('size', '<', $filter['size_to']);
            unset($filter['size_to']);
        }
        if (isset($filter['search_type'])){
            $select->whereIn('type',$filter['search_type']);
        }
        unset($filter['size_from']);
        unset($filter['size_to']);
        unset($filter['search_type']);
        /**
         * User chỉ thấy được lịch sử xuất/ nhập dữ liệu của tài khoản mình, RET 965.
         */
        if (isset($filter['user_id']) && $filter['user_id'] != null) {
            $select->where($this->table . '.created_by', $filter['user_id']);
            unset($filter['user_id']);
        }
        $select->orderBy($this->table . '.created_at', 'desc');
        return $select;
    }
}
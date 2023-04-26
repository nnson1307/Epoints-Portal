<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerProject\Models;
use Carbon\Carbon;
use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class ManagerDocumentFileTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_document_file';
    protected $primaryKey = 'manage_document_file_id';

    protected $fillable = [
        'manage_document_file_id',
        'manage_work_id',
        'file_name',
        'file_type',
        'note',
        'path',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    /**
     * Tổng file đính kèm theo công việc
     * @param $manage_work_id
     */
    public function getTotalFileAttach($manage_work_id){
        return $this->where('manage_work_id',$manage_work_id)->count();
    }

    public function getListFile($filter){

        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.manage_document_file_id',
                $this->table.'.manage_work_id',
                $this->table.'.file_name',
                $this->table.'.file_type',
                $this->table.'.note',
                $this->table.'.path',
                $this->table.'.updated_at',
                'staffs.full_name as staff_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.created_by')
            ->where($this->table.'.manage_work_id',$filter['manage_work_id'])
            ->orderBy($this->table.'.updated_at','DESC');

        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Tạo file hồ sơ
     * @param $data
     */
    public function createdFileDocument($data){
        return $this->insertGetId($data);
    }

    /**
     * Cập nhật file hồ sơ
     * @param $data
     * @param $id
     */
    public function updatedFileDocument($data,$id){
        return $this->where('manage_document_file_id',$id)->update($data);
    }

    /**
     * lấy chi tiết file
     * @param $manage_document_file_id
     * @return mixed
     */
    public function getDetail($manage_document_file_id){
        return $this
            ->select(
                $this->table.'.manage_document_file_id',
                $this->table.'.manage_work_id',
                $this->table.'.file_name',
                $this->table.'.file_type',
                $this->table.'.note',
                $this->table.'.path',
                $this->table.'.updated_at'
            )
            ->where('manage_document_file_id',$manage_document_file_id)
            ->first();
    }

    /**
     * Xoá file hồ sơ
     * @param $manage_document_file_id
     * @return mixed
     */
    public function removeFileDocument($manage_document_file_id){
        return $this->where('manage_document_file_id',$manage_document_file_id)->delete();
    }
}
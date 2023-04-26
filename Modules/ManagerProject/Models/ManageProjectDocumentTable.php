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

class ManageProjectDocumentTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_project_document';
    protected $primaryKey = 'manage_project_document_id';

    protected $fillable = [
        'manage_project_document_id',
        'manage_project_id',
        'file_name',
        'file_type',
        'note',
        'path',
        'type_upload',
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
                $this->table.'.manage_project_document_id',
                $this->table.'.manage_project_id',
                $this->table.'.file_name',
                $this->table.'.type',
                $this->table.'.type_upload',
                $this->table.'.path',
                $this->table.'.updated_at',
                'staffs.full_name as staff_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.created_by')
            ->where($this->table.'.manage_project_id',$filter['manage_project_id'])
            ->orderBy($this->table.'.updated_at','DESC');

        if(isset($filter['keyword']) && $filter['keyword']) {
            $oSelect->where('file_name', 'LIKE', "%{$filter['keyword']}%");
        }

        if(isset($filter['updated_at']) && $filter['updated_at']) {
            $oDate = explode("-", trim($filter['updated_at']));
            
            if( count($oDate) == 2) {
                $fromDate = Carbon::createFromFormat("d/m/Y", trim($oDate[0]))->format('Y-m-d 00:00:00');
                $toDate = Carbon::createFromFormat("d/m/Y", trim($oDate[1]))->format('Y-m-d 23:59:59');
                $oSelect->where('manage_project_document.updated_at', '>=', $fromDate);
                $oSelect->where('manage_project_document.updated_at', '<=', $toDate);
            }
        }

        if(isset($filter['type']) && $filter['type']) {
            if (!in_array($filter['type'],['file','image'])){
                $oSelect->where('type_upload', $filter['type']);
            } else {
                $oSelect
                    ->where('type_upload', '<>','link')
                    ->where('type', $filter['type']);
            }

        }

        if(isset($filter['created_by']) && $filter['created_by']) {
            $oSelect->where('manage_project_document.created_by', $filter['created_by']);
        }

        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Tạo file hồ sơ
     * @param $data
     */
    public function createdFileDocument($data){
        // dd($data);
        return $this->insertGetId($data);
    }

    /**
     * Cập nhật file hồ sơ
     * @param $data
     * @param $id
     */
    public function updatedFileDocument($data,$id){
        return $this->where('manage_project_document_id',$id)->update($data);
    }

    /**
     * lấy chi tiết file
     * @param $manage_document_file_id
     * @return mixed
     */
    public function getDetail($manage_project_document_id){
        return $this
            ->select(
                $this->table.'.manage_project_document_id',
                $this->table.'.manage_project_id',
                $this->table.'.file_name',
                $this->table.'.type',
                $this->table.'.type_upload',
                $this->table.'.path',
                $this->table.'.updated_at'
            )
            ->where('manage_project_document_id',$manage_project_document_id)
            ->first();
    }

    /**
     * Xoá file hồ sơ
     * @param $manage_document_file_id
     * @return mixed
     */
    public function removeFileDocument($manage_project_document_id){
        return $this->where('manage_project_document_id',$manage_project_document_id)->delete();
    }

    /**
     * Thêm nhiều nhiều
     */
    public function insertArr($data){
        return $this->insert($data);
    }
    public function getNumberDocument($filter = [])
    {
        $mSelect = $this
            ->select("{$this->table}.manage_project_id", DB::raw('count(*) as total'))
            ->groupBy('manage_project_id');

        if (isset($filter['arrIdProject']) && $filter['arrIdProject'] != '' && $filter['arrIdProject'] != null) {
            $mSelect = $mSelect->whereIn("{$this->table}.manage_project_id", $filter['arrIdProject']);
        }
        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != '' && $filter['manage_project_id'] != null) {
            $mSelect = $mSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        }
        return $mSelect->get()->toArray();
    }
}
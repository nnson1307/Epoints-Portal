<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;
use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class ManagerHistoryTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_history';
    protected $primaryKey = 'manage_history_id';

    protected $fillable = [
        'manage_history_id',
        'manage_work_id',
        'staff_id',
        'message',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    
    public function createdHistory($data){
        return $this->insert($data);
    }

    /**
     * Lấy danh sách history
     */
    public function getListhistory($data){
        $oSelect = $this
            ->select(
                $this->table.'.manage_history_id',
                $this->table.'.manage_work_id',
                'manage_work.manage_work_title',
                'manage_work.manage_project_id',
                'manage_work.manage_work_code',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.created_at',
                DB::raw('DATE_FORMAT(manage_history.created_at,"%d/%m/%Y") as created_at_format'),
                'staffs.full_name as staff_name'
            )
            ->join('manage_work','manage_work.manage_work_id',$this->table.'.manage_work_id')
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id');

        if (isset($data['staff_id'])){
            $oSelect = $oSelect->where($this->table.'.staff_id',$data['staff_id']);
        }

        if (isset($data['keywork'])){
            $keyWork = $data['keywork'];
            $oSelect = $oSelect
                ->where(function ($sql) use ($keyWork){
                    $sql->where('manage_work.manage_work_title','like','%'.$keyWork.'%')
                        ->orWhere('manage_work.manage_work_code','like','%'.$keyWork.'%');
                });
        }

        if (isset($data['arr_staff_id'])){
            $oSelect = $oSelect->whereIn($this->table.'.staff_id',$data['arr_staff_id']);
        }

        if (isset($data['manage_work_id'])){
            $oSelect = $oSelect->where($this->table.'.manage_work_id',$data['manage_work_id']);
        }

        if (isset($data['manage_project_id'])){
            $oSelect = $oSelect->where('manage_work.manage_project_id',$data['manage_project_id']);
        }

        if (isset($data['created_at'])){

            $date = explode(' - ',$data['created_at']);
            $start = Carbon::createFromFormat('d/m/Y',$date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$date[1])->format('Y-m-d 23:59:59');

            $oSelect = $oSelect->whereBetween($this->table.'.created_at',[$start,$end]);
        }

        if (isset($data['my_report_created_at'])){

            $date = explode(' - ',$data['my_report_created_at']);
            $start = $date[0];
            $end = $date[1];

            $oSelect = $oSelect->whereBetween($this->table.'.created_at',[$start,$end]);
        }

        return $oSelect->get();
    }
}
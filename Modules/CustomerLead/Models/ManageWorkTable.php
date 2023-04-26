<?php


namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class ManageWorkTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_work';
    protected $primaryKey = 'manage_work_id';

    protected $fillable = [
        'manage_work_id',
        'manage_project_id',
        'manage_type_work_id',
        'manage_work_code',
        'manage_work_title',
        'date_start',
        'date_end',
        'date_finish',
        'processor_id',
        'assignor_id',
        'time',
        'time_type',
        'progress',
        'customer_id',
        'customer_name',
        'description',
        'approve_id',
        'parent_id',
        'type_card_work',
        'priority',
        'manage_status_id',
        'repeat_type',
        'repeat_end',
        'repeat_end_time',
        'repeat_end_type',
        'repeat_end_full_time',
        'repeat_time',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_approve_id'
    ];

    const STARTED = 3;
    const FINISH = 6;
    const CANCEL = 7;

    /**
     * Lấy tổng số công việc theo khách hàng
     */
    public function getTotalWorkByCustomer($customerId){
        return $this
            ->where('customer_id',$customerId)
            ->whereNotIn('manage_status_id',[self::FINISH,self::CANCEL])
            ->count();
    }

    public function removeWorkByParent($parentTask){
        return $this
            ->where('parent_id',$parentTask)
            ->delete();
    }
}
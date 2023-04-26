<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ManagerConfigNotificationTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_config_notification';
    protected $primaryKey = 'manage_config_notification_id';


    /**
     * Lấy danh sách noti cấu hình
     */
    public function getAll(){
        return $this
            ->select(
                'manage_config_notification_id',
                'manage_config_notification_key',
                'manage_config_notification_title',
                'is_mail',
                'is_noti',
                'manage_config_notification_message',
                'is_created',
                'is_processor',
                'is_support',
                'is_approve'
            )
            ->where('is_active',1)
            ->orderBy('manage_config_notification_id','ASC')
            ->get();
    }

    /**
     * Chi tiết noti
     * @param $manage_config_notification_id
     * @return mixed
     */
    public function getDetail($manage_config_notification_id){
        return $this
            ->select(
                'manage_config_notification_id',
                'manage_config_notification_key',
                'manage_config_notification_title',
                'is_mail',
                'is_noti',
                'manage_config_notification_message',
                'is_created',
                'is_processor',
                'is_support',
                'is_approve'
            )
            ->where('manage_config_notification_id',$manage_config_notification_id)
            ->first();
    }

    /**
     * Chỉnh sửa noti
     * @param $data
     * @param $id
     */
    public function editNoti($data,$manage_config_notification_id){
        return $this
            ->where('manage_config_notification_id',$manage_config_notification_id)
            ->update($data);
    }

}
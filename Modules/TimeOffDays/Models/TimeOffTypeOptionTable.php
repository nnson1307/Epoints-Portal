<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;

use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class TimeOffTypeOptionTable extends Model
{
    use ListTableTrait;
    protected $table = "time_off_type_option";
    protected $primaryKey = "time_off_type_option_id";
    protected $fillable = [
        "time_off_type_code",
        "time_off_type_option_key",
        "time_off_type_option_value",
        "is_status",
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
    ];

    public function getAll($code)
    {
        
        return $this->select(
            $this->table.'.time_off_type_option_id',
            $this->table.'.time_off_type_option_key',
            $this->table.'.time_off_type_option_value',
            $this->table.'.is_status',
        )
        ->where("{$this->table}.time_off_type_code", "=",  $code)
        ->orderBy("{$this->table}.time_off_type_option_position", "asc")
        ->get();
    }


    public function getByCode($code)
    {
        
        return $this->select(
            $this->table.'.time_off_type_option_id',
            $this->table.'.time_off_type_option_key',
            $this->table.'.time_off_type_option_value',
            $this->table.'.is_status',
        )
        ->where("{$this->table}.time_off_type_code", "=",  $code)
        ->get();
    }

    /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function editConfig($time_off_type_code, $time_off_type_option_key, $time_off_type_option_value)
    {
        $idReturn = $this
                    ->where("time_off_type_code", $time_off_type_code)
                    ->where("time_off_type_option_key", $time_off_type_option_key)
                    ->update([
                        'time_off_type_option_value' => $time_off_type_option_value,
                        'updated_at'  => Carbon::now()->format("Y-m-d H:i:s"),
                        'updated_by' => Auth()->id(),
                    ]);
        if($idReturn <= 0){
            $add = $this->create([
                'time_off_type_code' => $time_off_type_code,
                'time_off_type_option_key' => $time_off_type_option_key,
                'time_off_type_option_value' => $time_off_type_option_value,
                'updated_by' => Auth()->id(),
                'created_by' => Auth()->id(),
                'created_at'  => Carbon::now()->format("Y-m-d H:i:s"),
                'updated_at'  => Carbon::now()->format("Y-m-d H:i:s")
            ]);
            $idReturn = $add->time_off_days_log_id;
        }
        return $idReturn;
    }


     /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function editConfigAll($time_off_type_code)
    {
        return $this
                ->where("time_off_type_code", $time_off_type_code)
                ->update(['time_off_type_option_value' => '']);
    }

    /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where("{$this->primaryKey}", $id)->update($data);
    }

    /**
     * Get danh sách người duyệt
     *
     * @param array $data
     * @return mixed
     */

     public function getLists($timeOffTypeId)
     {
         $oSelect = $this
             ->select(
                 "{$this->table}.time_off_type_option_id",
                 "{$this->table}.time_off_type_code",
                 "{$this->table}.time_off_type_option_key",
                 "{$this->table}.time_off_type_option_value",
                 "{$this->table}.time_off_type_option_position"
             )
             ->join('time_off_type', 'time_off_type.time_off_type_code', "{$this->table}.time_off_type_code")
             ->where("time_off_type.time_off_type_id", $timeOffTypeId)
             ->where(function ($oSelect) {
                 $oSelect
                     ->where("$this->table.time_off_type_option_key", 'approve_level_1')
                     ->orWhere("$this->table.time_off_type_option_key", 'approve_level_2')
                     ->orWhere("$this->table.time_off_type_option_key", 'approve_level_3');
             });
         return $oSelect->get();
     }
}
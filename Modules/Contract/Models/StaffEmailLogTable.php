<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/19/2021
 * Time: 4:36 PM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class StaffEmailLogTable extends Model
{
    protected $table = "staff_email_log";
    protected $primaryKey = "staff_email_log_id";
    protected $fillable = [
        "staff_email_log_id",
        "email_type",
        "email_subject",
        "email_subject",
        "email_from",
        "email_to",
        "email_cc",
        "email_params",
        "is_error",
        "error_description",
        "is_run",
        "run_at",
        "created_at",
    ];

    public $timestamps = false;

    public function createStaffEmailLog($data)
    {
        return $this->create($data)->staff_email_log_id;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/13/2019
 * Time: 3:22 PM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class RuleSettingOtherTable extends Model
{
    protected $table = "rule_setting_other";
    protected $primaryKey = "id";

    protected $fillable = ['id', 'name', 'is_actived', 'day', 'updated_by', 'updated_at'];

    public function getRuleSettingOther()
    {
        $oSelect = $this->get();
        return $oSelect;
    }
}
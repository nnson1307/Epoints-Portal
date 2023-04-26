<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTable extends Model
{
    protected $table = "team";
    protected $primaryKey = "team_id";

    /**
     * lấy danh sách nhóm
     */
    public function getAll(){
        return $this->get();
    }


}
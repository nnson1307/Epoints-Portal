<?php

namespace Modules\CustomerLead\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerLeadNoteTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_customer_lead_notes";
    protected $primaryKey = "customer_lead_note_id";

    protected $fillable = [
        'customer_lead_note_id',
        'customer_lead_id',
        'content',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function getListNoteCustomer($customer_lead_id){
        $oSelect = $this
            ->select(
                $this->table.'.customer_lead_note_id',
                $this->table.'.customer_lead_id',
                $this->table.'.content',
                $this->table.'.created_at',
                'staffs.full_name as created_by'
            )
            ->leftJoin('staffs','staffs.staff_id',$this->table.'.created_by')
            ->where($this->table.'.customer_lead_id',$customer_lead_id)
            ->orderBy($this->table.'.created_at','DESC');

        return $oSelect->get();
    }

    /**
     * Tạo note
     * @param $data
     */
    public function createdNote($data){
        return $this->insertGetId($data);
    }

}
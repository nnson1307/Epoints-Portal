<?php

namespace Modules\Salary\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class SalaryTable
 * @package Modules\Salary\Models
 * @author VuND
 * @since 02/12/2021
 */
class ContractReceiptTable extends Model
{

    protected $table = "contract_receipt";
    protected $primaryKey = "contract_receipt_id";
    protected $fillable = [
        "contract_receipt_id",
        "contract_id",
        "content",
        "collection_date",
        "collection_by",
        "prepayment",
        "amount_remain",
        "total_amount_receipt",
        "invoice_date",
        "invoice_no",
        "receipt_code",
        "note",
        "reason",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    public function getReceiptByStaffGroup($dateStart, $dateEnd){
        $oSelect = $this
            ->select("{$this->table}.*", 'contract_form','performer_by',
                'ticket_code', 'partner_object_type', 'partner_object_form', 'partner_object_id',
                'contract_payment.tax', 'contracts.is_applied_kpi'
            )
            ->join('contracts', 'contracts.contract_id', '=','contract_receipt.contract_id')
            ->join('contract_partner', 'contract_partner.contract_id', '=', 'contract_receipt.contract_id')
            ->join('contract_category_status', 'contract_category_status.status_code', '=', 'contracts.status_code')
            ->leftJoin('contract_payment', 'contract_payment.contract_id', '=', 'contracts.contract_id')
            ->whereBetween(DB::raw('DATE_FORMAT(contract_receipt.created_at, "%Y-%m-%d")'), [$dateStart, $dateEnd])
            ->whereNotNull('performer_by')
            ->where('contracts.is_deleted', 0)
            ->where('default_system', '<>','cancel')
            ->get();
        if($oSelect){
            return $oSelect->groupby('performer_by')->toArray();
        }
        return [];
    }

    public function getReceiptByStaffTicketGroup($dateStart, $dateEnd){
        $oSelect = $this
            ->select("{$this->table}.*", 'contract_form','performer_by', 'contracts.ticket_code',
                'partner_object_type', 'partner_object_form', 'partner_object_id',
                'ticket_processor.process_by','contracts.is_applied_kpi'
            )
            ->join('contracts', 'contracts.contract_id', '=','contract_receipt.contract_id')
            ->join('contract_partner', 'contract_partner.contract_id', '=', 'contract_receipt.contract_id')
            ->join('contract_category_status', 'contract_category_status.status_code', '=', 'contracts.status_code')
            ->join('ticket', 'ticket.ticket_code', 'contracts.ticket_code')
            ->join('ticket_processor', 'ticket_processor.ticket_id', '=', 'ticket.ticket_id')
            // ticket update trong thoi gian tinh luong
            ->whereBetween(DB::raw('DATE_FORMAT(ticket.updated_at, "%Y-%m-%d")'), [$dateStart, $dateEnd])
            // ticket trang thai dong
            ->where('ticket.ticket_status_id', 4)
            ->where('contracts.is_deleted', 0)
            ->where('default_system', '<>','cancel')
            ->get();
        if($oSelect){
            return $oSelect->groupby('process_by')->toArray();
        }
        return [];
    }
}
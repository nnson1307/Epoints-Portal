<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 07/09/2021
 * Time: 14:53
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractReceiptFileTable extends Model
{
    protected $table = "contract_receipt_files";
    protected $primaryKey = "contract_receipt_id";

    /**
     * Lấy thông tin file đợt thu
     *
     * @param $contractReceiptId
     * @return mixed
     */
    public function getFileByReceipt($contractReceiptId)
    {
        return $this->where("contract_receipt_id", $contractReceiptId)->get();
    }

    /**
     * Xoá file đợt thu
     *
     * @param $contractReceiptId
     * @return mixed
     */
    public function removeFileByReceipt($contractReceiptId)
    {
        return $this->where("contract_receipt_id", $contractReceiptId)->delete();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/09/2021
 * Time: 17:05
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractSpendFileTable extends Model
{
    protected $table = "contract_spend_files";
    protected $primaryKey = "contract_spend_file_id";

    /**
     * Lấy thông tin file đợt chi
     *
     * @param $contractSpendId
     * @return mixed
     */
    public function getFileBySpend($contractSpendId)
    {
        return $this->where("contract_spend_id", $contractSpendId)->get();
    }

    /**
     * Xoá file đợt chi
     *
     * @param $contractSpendId
     * @return mixed
     */
    public function removeFileBySpend($contractSpendId)
    {
        return $this->where("contract_spend_id", $contractSpendId)->delete();
    }
}
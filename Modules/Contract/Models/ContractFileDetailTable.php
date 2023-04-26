<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 09:53
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractFileDetailTable extends Model
{
    protected $table = "contract_file_details";
    protected $primaryKey = "contract_file_detail_id";

    /**
     * Lấy thông tin chi tiết file
     *
     * @param $fileID
     * @return mixed
     */
    public function getFile($fileID)
    {
        return $this->where("contract_file_id", $fileID)->get();
    }

    /**
     * Xoá chi tiết file
     *
     * @param $fileID
     * @return mixed
     */
    public function removeFile($fileID)
    {
        return $this->where("contract_file_id", $fileID)->delete();
    }
}
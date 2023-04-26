<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/21/2021
 * Time: 11:11 AM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractAnnexFileModel extends Model
{
    protected $table = "contract_annex_files";
    protected $primaryKey = "contract_annex_file_id";
    protected $fillable = [
        "contract_annex_file_id",
        "contract_annex_id",
        "link",
        "name",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    const IS_ACTIVE = 1;
    const IS_DELETED = 0;


    public function deleteData($id)
    {
        return $this->where("contract_annex_id", $id)->delete();
    }
    public function insertData($data)
    {
        return $this->insert($data);
    }
}
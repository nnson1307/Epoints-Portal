<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 8/23/2021
 * Time: 10:11 AM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContractCategoryFilesTable extends Model
{
    protected $table = 'contract_category_files';
    protected $primaryKey = 'contract_category_file_id';
    protected $fillable = [
        "contract_category_file_id",
        "contract_category_id",
        "link",
        "name",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];
    public function insertListFile($data)
    {
        return $this->insert($data);
    }
    public function deleteFile($cateId)
    {
        return $this->where("contract_category_id", $cateId)->delete();
    }
}
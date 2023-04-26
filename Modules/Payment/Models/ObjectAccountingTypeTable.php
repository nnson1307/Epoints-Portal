<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/08/2021
 * Time: 17:00 AM
 */

namespace Modules\Payment\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\Config;

class ObjectAccountingTypeTable extends Model
{
    use ListTableTrait;

    protected $table = "object_accounting_type";
    protected $primaryKey = "object_accounting_type_id ";

    protected $fillable = [
        'object_accounting_type_id  ', 'object_accounting_type_code', 'object_accounting_type_name_vi'
        , 'object_accounting_type_name_en', 'is_active', 'is_system',
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    const IS_ACTIVE = 1;
    const CONTRACT = "OAT_CONTRACT";

    /**
     * Lấy các loại đối tượng nhận để tạo sự lựa chọn (select option)
     *
     * @return mixed
     */
    public function getObjectAccountTypeOption()
    {

        $lang = Config::get('app.locale');
        return $this->select('object_accounting_type_id',
            'object_accounting_type_code',
            "object_accounting_type_name_$lang as object_accounting_type_name_vi",
            'object_accounting_type_name_en')
            ->where('is_active', 1)->get()->toArray();

    }


    /**
     * Lấy các option loại phiếu thu
     *
     * @return mixed
     */
    public function getOption()
    {
        $lang = Config::get('app.locale');
        $select = $this
            ->select(
                "object_accounting_type_id",
                "object_accounting_type_code",
                "object_accounting_type_name_$lang as object_accounting_type_name"
            )
            ->where("object_accounting_type_code", "<>", self::CONTRACT)
            ->where("is_active", self::IS_ACTIVE);
        return $select->get();
    }
}
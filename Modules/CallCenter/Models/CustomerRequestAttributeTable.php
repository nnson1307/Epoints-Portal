<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/31/2020
 * Time: 4:57 PM
 */

namespace Modules\CallCenter\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerRequestAttributeTable extends Model
{
    use ListTableTrait;
    protected $table = "customer_request_attribute";
    protected $primaryKey = "customer_request_attribute_id";
    protected $fillable = [
        "object_key",
        "object_value",
        "object_type",
        "object_data_type",
    ];

    const ATTRIBUT_CREATE = "create";
    const ATTRIBUT_INFO = "info";

    /**
     * Lấy option nguồn KH
     *
     * @return mixed
     */
    public function getOptionCreate()
    {
        return $this
            ->select(
                "object_key",
                "object_value",
                "object_data_type"
            )
            ->where("object_type", self::ATTRIBUT_CREATE)
            ->where("object_value", "!=", null)
            ->where("object_value", "!=", '')
            ->get();
    }
}
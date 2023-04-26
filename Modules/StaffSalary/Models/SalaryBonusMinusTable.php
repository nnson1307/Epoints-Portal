<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/18/22
 * Time: 5:48 PM
 */

namespace Modules\StaffSalary\Models;

use Illuminate\Database\Eloquent\Model;
// use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;

class SalaryBonusMinusTable extends Model
{
    // use ListTableTrait;
    protected $table = "salary_bonus_minus";
    protected $primaryKey = "salary_bonus_minus_id";
    protected $fillable = [
        "salary_bonus_minus_id",
        "salary_bonus_minus_name",
        "salary_bonus_minus_type",
        "salary_bonus_minus_num",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * add salary allowance
     *
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->salary_bonus_minus_id;
    }

    /**
     * edit salary allowance
     *
     * @return mixed
     */
    public function edit($data, $id){
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * get list salary allowance
     *
     * @return mixed
     */
    public function getList()
    {
        $ds = $this
            ->select(
                "salary_bonus_minus_id",
                "salary_bonus_minus_name",
                "salary_bonus_minus_type",
                "salary_bonus_minus_num",
            );

        return $ds->get();
    }

    /**
     * get salary allowance
     *
     * @return mixed
     */
    public function getDetail($id)
    {
        $ds = $this
            ->select(
                "salary_bonus_minus_id",
                "salary_bonus_minus_name",
                "salary_bonus_minus_type",
                "salary_bonus_minus_num",
            )
            ->where('salary_bonus_minus_id','=', $id);
        return $ds->first();
    }
}
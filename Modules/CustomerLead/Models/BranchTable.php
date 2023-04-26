<?php


namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class BranchTable extends Model
{
    use ListTableTrait;
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    //function fillable
    protected $fillable = [
        'branch_id',
        'branch_name',
        'address',
        'description',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'phone',
        'email',
        'hot_line',
        'provinceid',
        'districtid',
        'is_representative',
        'representative_code',
        'slug',
        'latitude',
        'longitude'
    ];

    public function getBranchOption()
    {
        return $this->select('branch_id', 'branch_code' , 'branch_name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get();
    }

    public function getLists(){
        return self::where('is_deleted', 0)->get();
    }

    /**
     * Lấy thông tin chi nhánh
     *
     * @param $brandId
     * @return mixed
     */
    public function getInfo($brandId)
    {
        return $this
            ->select(
                'branch_id',
                'branch_code' ,
                'branch_name'
            )
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->where("branch_id", $brandId)
            ->first();
    }
}
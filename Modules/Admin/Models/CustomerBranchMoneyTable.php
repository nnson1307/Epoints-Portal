<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/1/2019
 * Time: 13:54
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerBranchMoneyTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_branch_money';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'branch_id','customer_id','total_money','total_using','balance','created_by','updated_by',
        'created_at','updated_at', 'commission_money'
    ];

    public function add(array $data)
    {
        $add = $this->create($data);
        return $add;
    }

    public function edit(array $data, $id,$branch)
    {
        return $this->where('customer_id', $id)->where('branch_id',$branch)->update($data);
    }

    public function getPriceBranch($id,$branch)
    {
        $ds=$this->select(
            'branch_id',
            'customer_id',
            'total_money',
            'total_using',
            'balance',
            'commission_money'
        )
            ->where('customer_id',$id)
            ->where('branch_id',$branch)
            ->first();
        return $ds;
    }
}
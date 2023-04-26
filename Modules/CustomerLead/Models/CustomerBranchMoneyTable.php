<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBranchMoneyTable extends Model
{
    protected $table = 'customer_branch_money';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
      "branch_id",
      "customer_id",
      "total_money",
      "total_using",
      "balance",
      "commission_money",
      "created_by",
      "updated_by",
      "created_at",
      "updated_at",
    ];

    public function getPriceBranch($id,$branch)
    {
        $select = $this->select(
            'branch_id',
            'customer_id',
            'total_money',
            'total_using',
            'balance',
            'commission_money'
        )
            ->where('customer_id',$id)
            ->where('branch_id',$branch);
        return $select->first();
    }

    public function edit(array $data, $id,$branch)
    {
        return $this->where('customer_id', $id)->where('branch_id',$branch)->update($data);
    }

    public function add(array $data)
    {
        return $this->create($data);
    }
}
<?php


namespace Modules\Referral\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ReferralProgramItemTable extends Model
{
    use ListTableTrait;

    protected $table = "referral_program_item";
    protected $primaryKey = "referral_program_item_id";

    public function add($v)
    {
        return $this
            ->insertGetId($v);
    }

    public function getInfoTable($filter = [])
    {

        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $mSelect = $this
            ->select(
                "{$this->table}.object_type",
                "{$this->table}.object_id"
                );
        if (isset($filter['referral_program_id'])) {
            $mSelect = $mSelect->where("{$this->table}.referral_program_id", $filter['referral_program_id']);
        }
        return $mSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function deleteCommodity($locate)
    {

        return $this
            ->where("{$this->table}.object_id", $locate['idCommodity']['object_id'])
            ->where("{$this->table}.object_type", $locate['idCommodity']['object_type'])
            ->where("{$this->table}.referral_program_id", $locate['referral_program_id'])
            ->delete();
    }

    public function getInfoCommodity($params)
    {
        $page = (int)($params['page'] ?? 1);
        $display = (int)($params['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $mSelect = $this
            ->select(
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                )
            ->where("{$this->table}.referral_program_id", $params['id']);
        return $mSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getCommodityDeleted($locate)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.referral_program_item_id",
                "{$this->table}.referral_program_id",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                )
            ->where("{$this->table}.object_id", $locate['idCommodity'])
            ->where("{$this->table}.referral_program_id", $locate['referral_program_id']);
        return $mSelect->first()->toArray();

    }
    public function addNotDuplicate($data)
    {
        return $this
            ->insert($data);
    }
    public function getInfoNotPage($filter = [])
    {


        $mSelect = $this
            ->select(
                "{$this->table}.object_type",
                "{$this->table}.object_id"
            );
        if (isset($filter['referral_program_id'])) {
            $mSelect = $mSelect->where("{$this->table}.referral_program_id", $filter['referral_program_id']);
        }

        return $mSelect->get()->toArray();
    }
    public function delDuplicate($referral_program_id)
    {

        return $this
            ->where("{$this->table}.referral_program_id", $referral_program_id)
            ->delete();
    }


}
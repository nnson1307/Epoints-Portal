<?php
/**
 *MemberLevelVerb
 *ledangsinh
 * Date: 3/26/2018
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class MemberLevelVerbTable extends Model
{
    use ListTableTrait;

    protected $table = 'member_level_verb';
    protected $primaryKey = 'member_level_verb_id';

    /**
     * fill table
     * @var array
     */
    protected $fillable = ['member_level_verb_id', 'member_level_verb_name', 'member_level_id', 'member_level_verb_point', 'order_price_min', 'order_price_max', 'product_number_min', 'product_number_max', 'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    public function _getList()
    {
        $oSelect = $this->from($this->table .' as mlv')
            ->leftJoin('member_level', 'member_level.member_level_id', '=', 'mlv.member_level_id')
            ->select(
                'mlv.member_level_verb_id',
                'mlv.member_level_verb_name',
                'mlv.member_level_verb_point',
                'mlv.order_price_min',
                'mlv.order_price_max',
                'mlv.product_number_min',
                'mlv.product_number_max',
                'mlv.is_active',
                'mlv.is_delete',
                'mlv.created_at',
                'mlv.updated_at',
                'mlv.created_by',
                'mlv.updated_by',
                'mlv.member_level_id',
                'member_level.member_level_name as member_level_name')
            ->where('mlv.is_delete', 0);
        return $oSelect;
    }

    /*
     * Function add member level verb
     */
    public function add(array $data)
    {
        $oMemberLevelVerb = $this->create($data);
        return $oMemberLevelVerb->member_level_verb_id;
    }

    /*
     * Function edit member level verb
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /*
     * Function remove member level verb
     */
    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_delete' => 1]);
    }

    /*
     * Function get item
     */
    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /*
     * Function get member level
     */
    public function getListMemberLevelOptions()
    {
        $oSelect = $this->from('member_level')->select('member_level_id', 'member_level_name')->get();
        $listData = array();
        foreach ($oSelect as $key => $value) {
            $listData[$value['member_level_id']] = $value['member_level_name'];
        }
        return $listData;
    }
    /**
     * Export Excel
     */
    public function exportExcel(array $oSelect)
    {
        $oExportExcel = DB::table($this->table .' as mlv')
        ->leftJoin('member_level', 'member_level.member_level_id', '=', 'mlv.member_level_id')
            ->select($oSelect)->get();
        return $oExportExcel;
    }
}
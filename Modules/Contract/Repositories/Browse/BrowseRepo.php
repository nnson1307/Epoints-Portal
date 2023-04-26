<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/11/2021
 * Time: 18:19
 */

namespace Modules\Contract\Repositories\Browse;


use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractBrowserTable;
use Modules\Contract\Models\ContractCategoryStatusApproveTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Models\MapRoleGroupStaffTable;
use Modules\Contract\Repositories\Contract\ContractRepoInterface;

class BrowseRepo implements BrowseRepoInterface
{
    protected $contractBrowse;

    public function __construct(
        ContractBrowserTable $contractBrowse
    )
    {
        $this->contractBrowse = $contractBrowse;
    }

    /**
     * Danh sách HĐ cần phê duyệt
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $mContractCategoryStatusApprove = new ContractCategoryStatusApproveTable();

        $mRoleStaff = new MapRoleGroupStaffTable();
        //Lấy ds HĐ cần duyệt
        $list = $this->contractBrowse->getList($filters);

        foreach ($list->items() as $key => $value) {
            $value['can_browse'] = 0;
            $dataRoleApprove = $mContractCategoryStatusApprove->getDetailStatusApprove($value['status_code_now']);
            foreach ($dataRoleApprove as $k => $v) {
                // get list staff by role
                $lstStaff = $mRoleStaff->getListStaffByRoleGroup($v['approve_by']);
                $arrStaff = collect($lstStaff)->groupBy('staff_id')->toArray();
                if(in_array(auth()->id(), array_keys($arrStaff))){
                    $value['can_browse'] = 1;
                }
            }
        }

        return [
            'list' => $list
        ];
    }

    const CONFIRM = "confirm";
    const REFUSE = "refuse";

    /**
     * Duyệt HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function confirm($input)
    {
        DB::beginTransaction();
        try {
            $mContract = app()->get(ContractTable::class);

            //Lấy thông tin yêu cầu duyệt
            $infoBrowse = $this->contractBrowse->getInfo($input['contract_browse_id']);

            //Cập nhật trạng thái yêu cầu duyệt
            $this->contractBrowse->edit([
                'status' => self::CONFIRM,
                'updated_by' => Auth()->id()
            ], $input['contract_browse_id']);
            //Cập nhật HĐ
            $mContract->edit([
                'status_code' => $infoBrowse['status_code_new'],
                'is_browse' => 0
            ], $infoBrowse['contract_id']);

            //Gửi thông báo duyệt
            $mContractRepo = app()->get(ContractRepoInterface::class);
            $mContractRepo->saveContractNotification('approved', $infoBrowse['contract_id']);

            DB::commit();
            return response()->json([
                "error" => false,
                "message" => __("Duyệt hợp đồng thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Duyệt hợp đồng thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Từ chối duyệt HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function refuse($input)
    {
        DB::beginTransaction();
        try {
            $mContract = app()->get(ContractTable::class);

            //Lấy thông tin yêu cầu duyệt
            $infoBrowse = $this->contractBrowse->getInfo($input['contract_browse_id']);

            //Cập nhật trạng thái yêu cầu duyệt
            $this->contractBrowse->edit([
                'status' => self::REFUSE,
                'reason_refuse' => $input['reason_refuse'],
                'updated_by' => Auth()->id()
            ], $input['contract_browse_id']);
            //Cập nhật HĐ
            $mContract->edit([
                'is_browse' => 0
            ], $infoBrowse['contract_id']);

            //Gửi thông báo từ chối duyệt
            $mContractRepo = app()->get(ContractRepoInterface::class);
            $mContractRepo->saveContractNotification('denied', $infoBrowse['contract_id']);

            DB::commit();
            return response()->json([
                "error" => false,
                "message" => __("Từ chối duyệt hợp đồng thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Từ chối duyệt hợp đồng thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/08/2021
 * Time: 16:32
 */

namespace Modules\Contract\Repositories\ExpectedRevenue;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractCategoryRemindTable;
use Modules\Contract\Models\ContractExpectedRevenueFileTable;
use Modules\Contract\Models\ContractExpectedRevenueLogTable;
use Modules\Contract\Models\ContractExpectedRevenueTable;
use Modules\Contract\Models\ContractLogExpectedRevenueTable;
use Modules\Contract\Models\ContractLogReceiptSpendTable;
use Modules\Contract\Models\ContractLogTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Repositories\Contract\ContractRepoInterface;

class ExpectedRevenueRepo implements ExpectedRevenueRepoInterface
{
    /**
     * Danh sách thu - chi
     *
     * @param array $filter
     * @return array|mixed
     */
    public function listRevenue(array $filter = [])
    {
        $mRevenue = app()->get(ContractExpectedRevenueTable::class);
        $mRevenueFile = app()->get(ContractExpectedRevenueFileTable::class);

        //Lấy ds thu - chi
        $list = $mRevenue->getList($filter);

        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                //Lấy file kèm theo
                $v['file'] = $mRevenueFile->getFileByRevenue($v['contract_expected_revenue_id']);
            }
        }

        return [
            'list' => $list
        ];
    }

    /**
     * Lấy data view tạo dự kiến thu - chi
     *
     * @param $input
     * @return mixed|void
     */
    public function getDataViewCreate($input)
    {
        $type = "";

        if ($input['type'] == "receipt") {
            $type = "receive_due_soon";
        } else if ($input['type'] == "spend") {
            $type = "spend_due_soon";
        }

        $mCategoryRemind = app()->get(ContractCategoryRemindTable::class);

        //Lấy option nội dung nhắc nhở
        $optionRemind = $mCategoryRemind->getOptionByType($input['contract_category_id'], $type);

        return [
            'optionRemind' => $optionRemind,
            'type' => $input['type']
        ];
    }

    /**
     * Thêm dự kiến thu - chi
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mContract = app()->get(ContractTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogRevenue = app()->get(ContractLogExpectedRevenueTable::class);
            $mRevenue = app()->get(ContractExpectedRevenueTable::class);
            $mRevenueFile = app()->get(ContractExpectedRevenueFileTable::class);

            //Lấy thông tin HĐ
            $infoContract = $mContract->getInfo($input['contract_id']);
            //Tạo đợt thu - chi
            $idRevenue = $mRevenue->add([
                "contract_id" => $input['contract_id'],
                "type" => $input['type'],
                "title" => $input['title'],
                "contract_category_remind_id" => $input['contract_category_remind_id'],
                "send_type" => $input['send_type'],
                "send_value" => isset($input['send_value']) ? $input['send_value'] : null,
                "send_value_child" => isset($input['send_value_child']) ? $input['send_value_child'] : null,
                "note" => $input['note'],
                "amount" => $input['amount'],
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Insert log nhắc thu - chi
            $this->_insertLogRevenue($infoContract, $input, $idRevenue);

            $arrFile = [];

            if (isset($input['contract_revenue_files']) && count($input['contract_revenue_files']) > 0) {
                foreach ($input['contract_revenue_files'] as $k => $v) {
                    $arrFile [] = [
                        'contract_expected_revenue_id' => $idRevenue,
                        'file_name' => $input['contract_revenue_name_files'][$k],
                        'link' => $v
                    ];
                }
            }
            //Insert file
            $mRevenueFile->insert($arrFile);

            $mContractRepo = app()->get(ContractRepoInterface::class);
            if ($input['type'] == "receipt") {
                //Dự thu
                $note = __('Tạo dự kiến thu');
                $mContractRepo->saveContractNotification('updated_content', $input['contract_id'], __('Dự kiến thu'));
            } else {
                //Dự chi
                $note = __('Tạo dự kiến chi');
                $mContractRepo->saveContractNotification('updated_content', $input['contract_id'], __('Dự kiến chi'));
            }

            //Lưu log hợp đồng khi trigger thu - chi
            $logId = $mLog->add([
                "contract_id" => $infoContract['contract_id'],
                "change_object_type" => "expected_revenue",
                "note" => $note,
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogRevenue->add([
                "contract_log_id" => $logId,
                "contract_expected_revenue_id" => $idRevenue
            ]);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Thêm thành công"),
                "type" => $input['type']
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Thêm thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine() . $e->getFile()
            ]);
        }
    }

    /**
     * Lưu log nhắc dự kiến thu - chi
     *
     * @param $infoContract
     * @param $input
     * @param $idRevenue
     */
    public function _insertLogRevenue($infoContract, $input, $idRevenue)
    {
        $mRevenueLog = app()->get(ContractExpectedRevenueLogTable::class);

        $arrLog = [];

        if ($input['send_type'] == 'after' && $infoContract['sign_date'] != null) {
            //Sau ngày ký HĐ
            $date = Carbon::parse($infoContract['sign_date'])->addDays($input['send_value'])->format('Y-m-d');

            $arrLog =[
                "contract_expected_revenue_id" => $idRevenue,
                "contract_id" => $infoContract['contract_id'],
                "date_send" => $date
            ];
        }

        if ($input['send_type'] == 'hard' && $infoContract['effective_date'] != null && $infoContract['expired_date']) {
            //Cố định
            $dtStart = \Carbon\Carbon::parse($infoContract['effective_date']);
            $dtEnd = Carbon::parse($infoContract['expired_date']);
            $monthStart = Carbon::parse($infoContract['effective_date'])->format('Y-m');
            $monthStart = Carbon::parse($monthStart);
            $monthEnd = Carbon::parse($infoContract['expired_date'])->format('Y-m');
            $monthEnd = Carbon::parse($monthEnd);
            // get diff month
            $part1 = ($monthStart->format('Y') * 12) + $monthStart->format('m');
            $part2 = ($monthEnd->format('Y') * 12) + $monthEnd->format('m');
            $diffMonth = abs($part1 - $part2);
//            dd(dump($diff));
//            $diffMonth = $monthStart->diffInMonths($monthEnd);
            if ($diffMonth > 0 && $input['send_value_child'] <= $diffMonth) {
                //Chia mỗi chu kỳ (làm tròn)
                $number = intval($diffMonth/$input['send_value_child']);

                for ($i = 1; $i <= $number; $i++) {
                    $format = Carbon::parse($monthStart)->addMonths($i);
                    $date = Carbon::parse($monthStart)->addMonths($i)->format('Y-m') .'-'. sprintf("%02d", $input['send_value']);
                    //Check ngày có tồn tại ko
                    if (checkdate($format->format('m'), sprintf("%02d", $input['send_value']), $format->format('Y')) == true) {
                        if($dtStart->lte($format) && $dtEnd->gte($format)){
                            $arrLog [] = [
                                "contract_expected_revenue_id" => $idRevenue,
                                "contract_id" => $infoContract['contract_id'],
                                "date_send" => $date
                            ];
                        }
                    }
                }

            }
        }

        if ($input['send_type'] == 'custom' && count($input['arrDateCustom']) > 0) {
            //Tuỳ chọn ngày
            foreach ($input['arrDateCustom'] as $v) {
                if ($v != null) {
                    $date =  Carbon::createFromFormat('d/m/Y', $v)->format('Y-m-d');

                    $arrLog [] = [
                        "contract_expected_revenue_id" => $idRevenue,
                        "contract_id" => $infoContract['contract_id'],
                        "date_send" => $date
                    ];
                }
            }
        }

        //Insert log
        $mRevenueLog->insert($arrLog);
    }

    /**
     * Lấy data view chỉnh sửa dự kiến thu - chi
     *
     * @param $input
     * @return array|mixed
     */
    public function getDataViewEdit($input)
    {
        $type = "";

        if ($input['type'] == "receipt") {
            $type = "receive_due_soon";
        } else if ($input['type'] == "spend") {
            $type = "spend_due_soon";
        }

        $mCategoryRemind = app()->get(ContractCategoryRemindTable::class);
        $mRevenue = app()->get(ContractExpectedRevenueTable::class);
        $mRevenueFile = app()->get(ContractExpectedRevenueFileTable::class);
        $mRevenueLog = app()->get(ContractExpectedRevenueLogTable::class);

        //Lấy option nội dung nhắc nhở
        $optionRemind = $mCategoryRemind->getOptionByType($input['contract_category_id'], $type);
        //Lấy thông tin dự kiến thu - chi
        $infoRevenue = $mRevenue->getInfo($input['contract_expected_revenue_id']);
        //Lấy file thu - chi
        $revenueFile = $mRevenueFile->getFileByRevenue($input['contract_expected_revenue_id']);
        //Lấy log thu - chi
        $revenueLog = $mRevenueLog->getLogByRevenue($input['contract_expected_revenue_id']);

        return [
            'optionRemind' => $optionRemind,
            'infoRevenue' => $infoRevenue,
            'revenueFile' => $revenueFile,
            'revenueLog' => $revenueLog,
            'type' => $input['type']
        ];
    }

    /**
     * Chỉnh sửa dự kiến thu - chi
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mContract = app()->get(ContractTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogRevenue = app()->get(ContractLogExpectedRevenueTable::class);
            $mRevenue = app()->get(ContractExpectedRevenueTable::class);
            $mRevenueFile = app()->get(ContractExpectedRevenueFileTable::class);
            $mRevenueLog = app()->get(ContractExpectedRevenueLogTable::class);

            //Lấy thông tin HĐ
            $infoContract = $mContract->getInfo($input['contract_id']);
            //Chỉnh sửa đợt thu - chi
            $mRevenue->edit([
                "contract_id" => $input['contract_id'],
                "type" => $input['type'],
                "title" => $input['title'],
                "contract_category_remind_id" => $input['contract_category_remind_id'],
                "send_type" => $input['send_type'],
                "send_value" => isset($input['send_value']) ? $input['send_value'] : null,
                "send_value_child" => isset($input['send_value_child']) ? $input['send_value_child'] : null,
                "note" => $input['note'],
                "amount" => $input['amount'],
                "updated_by" => Auth()->id()
            ], $input['contract_expected_revenue_id']);

            //Xoá log nhắc thu - chi
            $mRevenueLog->removeLogByRevenue($input['contract_expected_revenue_id']);

            //Insert log nhắc thu - chi
            $this->_insertLogRevenue($infoContract, $input, $input['contract_expected_revenue_id']);

            $arrFile = [];

            if (isset($input['contract_revenue_files']) && count($input['contract_revenue_files']) > 0) {
                foreach ($input['contract_revenue_files'] as $k => $v) {
                    $arrFile [] = [
                        'contract_expected_revenue_id' => $input['contract_expected_revenue_id'],
                        'file_name' => $input['contract_revenue_name_files'][$k],
                        'link' => $v
                    ];
                }
            }
            //Xoá file
            $mRevenueFile->removeFileByRevenue($input['contract_expected_revenue_id']);
            //Insert file
            $mRevenueFile->insert($arrFile);

            if ($input['type'] == "receipt") {
                //Dự thu
                $note = __('Chỉnh sửa dự kiến thu');
            } else {
                //Dự chi
                $note = __('Chỉnh sửa dự kiến chi');
            }

            //Lưu log hợp đồng khi trigger thu - chi
            $logId = $mLog->add([
                "contract_id" => $infoContract['contract_id'],
                "change_object_type" => "expected_revenue",
                "note" => $note,
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogRevenue->add([
                "contract_log_id" => $logId,
                "contract_expected_revenue_id" => $input['contract_expected_revenue_id']
            ]);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Chỉnh sửa thành công"),
                "type" => $input['type']
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Chỉnh sửa thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Xoá dự kiến thu - chi
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($input)
    {
        try {
            $mContract = app()->get(ContractTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogRevenue = app()->get(ContractLogExpectedRevenueTable::class);
            $mRevenue = app()->get(ContractExpectedRevenueTable::class);

            //Lấy thông tin HĐ
            $infoContract = $mContract->getInfo($input['contract_id']);
            //Chỉnh sửa đợt thu - chi
            $mRevenue->edit([
                "is_deleted" => 1
            ], $input['contract_expected_revenue_id']);

            if ($input['type'] == "receipt") {
                //Dự thu
                $note = __('Xoá dự kiến thu');
            } else {
                //Dự chi
                $note = __('Xoá dự kiến chi');
            }

            //Lưu log hợp đồng khi trigger thu - chi
            $logId = $mLog->add([
                "contract_id" => $infoContract['contract_id'],
                "change_object_type" => "expected_revenue",
                "note" => $note,
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogRevenue->add([
                "contract_log_id" => $logId,
                "contract_expected_revenue_id" => $input['contract_expected_revenue_id']
            ]);


            return response()->json([
                "error" => false,
                "message" => __("Xoá thành công"),
                "type" => $input['type']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => __("Xoá thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }
}
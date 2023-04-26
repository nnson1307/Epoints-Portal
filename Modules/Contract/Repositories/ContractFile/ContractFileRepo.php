<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 10:58
 */

namespace Modules\Contract\Repositories\ContractFile;


use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractFileDetailTable;
use Modules\Contract\Models\ContractFileTable;
use Modules\Contract\Models\ContractLogFileTable;
use Modules\Contract\Models\ContractLogTable;
use Modules\Contract\Repositories\Contract\ContractRepoInterface;

class ContractFileRepo implements ContractFileRepoInterface
{
    const FILE = "file";

    /**
     * Lấy ds file HĐ
     *
     * @param array $filter
     * @return array|mixed
     */
    public function list(array $filter = [])
    {
        $mContractFile = app()->get(ContractFileTable::class);
        $mContractFileDetail = app()->get(ContractFileDetailTable::class);

        //Lấy ds file đính kèm
        $list = $mContractFile->getList($filter);

        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                //Lấy file kèm theo
                $v['file'] = $mContractFileDetail->getFile($v['contract_file_id']);
            }
        }

        return [
            'list' => $list
        ];
    }

    /**
     * Thêm file HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mContractFile = app()->get(ContractFileTable::class);
            $mContractFileDetail = app()->get(ContractFileDetailTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogFile = app()->get(ContractLogFileTable::class);

            //Thêm file
            $fileId = $mContractFile->add([
                'contract_id' => $input['contract_id'],
                'name' => $input['name'],
                'note' => $input['note'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            $arrFile = [];

            if (isset($input['contract_files']) && count($input['contract_files']) > 0) {
                foreach ($input['contract_files'] as $k => $v) {
                    $arrFile [] = [
                        'contract_file_id' => $fileId,
                        'file_name' => $input['contract_name_files'][$k],
                        'link' => $v
                    ];
                }
            }
            //Thêm file kèm theo
            $mContractFileDetail->insert($arrFile);
            //Lưu log hợp đồng khi trigger thêm file
            $logId = $mLog->add([
                "contract_id" => $input['contract_id'],
                "change_object_type" => self::FILE,
                "note" => __('Thêm hồ sơ'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogFile->add([
                "contract_log_id" => $logId,
                "contract_file_id" => $fileId
            ]);

            $mContractRepo = app()->get(ContractRepoInterface::class);
            $mContractRepo->saveContractNotification('updated_content', $input['contract_id'], __('Đính kèm'));
            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Thêm thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Thêm thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $input
     * @return array|mixed
     */
    public function getDataEdit($input)
    {
        $mContractFile = app()->get(ContractFileTable::class);
        $mContractFileDetail = app()->get(ContractFileDetailTable::class);

        //Lấy thông tin file
        $info = $mContractFile->getInfo($input['contract_file_id']);
        //Lấy thông tin chi tiết file
        $getFile = $mContractFileDetail->getFile($input['contract_file_id']);

        return [
            'item' => $info,
            'contractFile' => $getFile
        ];
    }

    /**
     * Chỉnh sửa file HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mContractFile = app()->get(ContractFileTable::class);
            $mContractFileDetail = app()->get(ContractFileDetailTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogFile = app()->get(ContractLogFileTable::class);

            //Chỉnh sửa file
            $mContractFile->edit([
                'name' => $input['name'],
                'note' => $input['note'],
                'updated_by' => Auth()->id()
            ], $input['contract_file_id']);
            //Xoá file kèm theo
            $mContractFileDetail->removeFile($input['contract_file_id']);

            $arrFile = [];

            if (isset($input['contract_files']) && count($input['contract_files']) > 0) {
                foreach ($input['contract_files'] as $k => $v) {
                    $arrFile [] = [
                        'contract_file_id' => $input['contract_file_id'],
                        'file_name' => $input['contract_name_files'][$k],
                        'link' => $v
                    ];
                }
            }
            //Thêm file kèm theo
            $mContractFileDetail->insert($arrFile);

            //Lưu log hợp đồng khi trigger thêm file
            $logId = $mLog->add([
                "contract_id" => $input['contract_id'],
                "change_object_type" => self::FILE,
                "note" => __('Chỉnh sửa hồ sơ'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogFile->add([
                "contract_log_id" => $logId,
                "contract_file_id" => $input['contract_file_id']
            ]);

            $mContractRepo = app()->get(ContractRepoInterface::class);
            $mContractRepo->saveContractNotification('updated_content', $input['contract_id'], __('Đính kèm'));
            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Chỉnh sửa thành công"),
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
     * Xoá file HĐ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($input)
    {
        DB::beginTransaction();
        try {
            $mContractFile = app()->get(ContractFileTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogFile = app()->get(ContractLogFileTable::class);

            //Update file HĐ
            $mContractFile->edit([
                'is_deleted' => 1
            ], $input['contract_file_id']);

            //Lưu log hợp đồng khi trigger xoá file
            $logId = $mLog->add([
                "contract_id" => $input['contract_id'],
                "change_object_type" => self::FILE,
                "note" => __('Xoá hồ sơ'),
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);
            //Log detail
            $mLogFile->add([
                "contract_log_id" => $logId,
                "contract_file_id" => $input['contract_file_id']
            ]);

            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Xoá thành công"),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Xoá thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }
}
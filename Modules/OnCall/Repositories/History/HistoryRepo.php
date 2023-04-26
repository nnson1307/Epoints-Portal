<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 29/07/2021
 * Time: 10:59
 */

namespace Modules\OnCall\Repositories\History;


use Modules\OnCall\Models\HistoryTable;
use Modules\OnCall\Models\SourceTable;
use Modules\OnCall\Models\StaffTable;

class HistoryRepo implements HistoryRepoInterface
{
    protected $history;

    public function __construct(
        HistoryTable $history
    ) {
        $this->history = $history;
    }

    /**
     * Lấy ds lịch sử cuộc gọi
     *
     * @param array $input
     * @return array|mixed
     */
    public function list($input = [])
    {
        //Lấy danh sách extension
        $list = $this->history->getList($input);

        return [
            "list" => $list
        ];
    }

    /**
     * Lấy option trang danh sách
     *
     * @return array|mixed
     */
    public function getOption()
    {
        $mStaff = app()->get(StaffTable::class);
        $mSource = app()->get(SourceTable::class);

        //Lấy option staff
        $getStaff = $mStaff->getStaff();

        $arrayStaff = [];

        foreach ($getStaff as $item) {
            $arrayStaff[$item['staff_id']] = $item['full_name'];
        }

        //Lấy nguồn cuộc gọi
        $getSource = $mSource->getOption();

        $arraySource = [];

        foreach ($getSource as $item) {
            $arraySource[$item['source_code']] = $item['source_name'];
        }

        return [
            'optionStaff' => $arrayStaff,
            'optionSource' => $arraySource
        ];
    }

    /**
     * Lấy dữ liệu chi tiết cuộc gọi
     *
     * @param $historyId
     * @return array|mixed
     */
    public function dataViewDetail($historyId)
    {
        //Lấy thông tin lịch sừ cuộc gọi
        $info = $this->history->getInfo($historyId);
        
        return [
            'item' => $info
        ];
    }
}
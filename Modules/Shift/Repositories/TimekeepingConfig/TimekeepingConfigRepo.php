<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 2:24 PM
 */

namespace Modules\Shift\Repositories\TimekeepingConfig;


use Illuminate\Support\Facades\DB;
use Modules\Shift\Models\BranchTable;
use Modules\Shift\Models\TimekeepingConfigTable;

class TimekeepingConfigRepo implements TimekeepingConfigRepoInterface
{
    const ALLOWABLE_RADIUS = 100;
    protected $timekeepingConfig;

    public function __construct(
        TimekeepingConfigTable $timekeepingConfig
    )
    {
        $this->timekeepingConfig = $timekeepingConfig;
    }

    /**
     * Lấy data filter
     *
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataFilter()
    {
        $mBranch = app()->get(BranchTable::class);

        $data = [
            'optionBranch' => [],
            'optionDepartment' => [],
            'optionStaff' => []
        ];

        //Lấy option chi nhánh
        $getOptionBranch = $mBranch->getOption();

        if (count($getOptionBranch) > 0) {
            foreach ($getOptionBranch as $v) {
                $data['optionBranch'] [$v['branch_id']] = $v['branch_name'];
            }
        }

        return $data;
    }

    /**
     * Danh sách
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->timekeepingConfig->getList($filters);

        return [
            "list" => $list
        ];
    }

    /**
     * Data view thêm
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dataViewCreate($input)
    {
        $mBranch = app()->get(BranchTable::class);

        //Lấy option chi nhánh
        $optionBranch = $mBranch->getOption();

        $html = \View::make('shift::timekeeping-config.popup-create', [
            'optionBranch' => $optionBranch
        ])->render();

        return [
            'html' => $html
        ];

    }

    /**
     * Thêm
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        try {
            //Insert
            $this->timekeepingConfig->add([
                "timekeeping_type" => $input["timekeeping_type"],
                "latitude" => $input["latitude"] ?? null,
                "longitude" => $input["longitude"] ?? null,
                "allowable_radius" => $input["allowable_radius"] ?? self::ALLOWABLE_RADIUS,
                "wifi_name" => $input["wifi_name"] ?? null,
                "wifi_ip" => $input["wifi_ip"] ?? null,
                "branch_id" => $input['branch_id'],
                "note" => $input["timekeeping_type"] == "gps" ? $input["note_gps"] ?? null :  $input['note'] ?? null,
                "is_actived" => 1
            ]);

            return response()->json([
                "error" => false,
                "message" => __("Tạo thành công")
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => __("Tạo thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Data view edit
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dataViewEdit($input)
    {
        $mBranch = app()->get(BranchTable::class);

        //Lấy thông tin cấu hình
        $info = $this->timekeepingConfig->getInfo($input['timekeeping_config_id']);

        //Lấy option chi nhánh
        $optionBranch = $mBranch->getOption();

        $html = \View::make('shift::timekeeping-config.popup-edit', [
            'item' => $info,
            'optionBranch' => $optionBranch
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Chỉnh sửa
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        try {
            $note = '';
            //Chỉnh sửa cấu hình wifi
            if($input['timekeeping_type'] == 'gps'){
                $note = $input['note_gps'] ?? '';
            }else {
                $note = $input['note'] ?? '';
            }
            $this->timekeepingConfig->edit([
                "wifi_name" => $input["timekeeping_type"] == "wifi" ? $input["wifi_name"] ?? null : null,
                "wifi_ip" => $input["timekeeping_type"] == "wifi" ? $input["wifi_ip"] ?? null : null,
                "timekeeping_type" => $input["timekeeping_type"],
                "latitude" => $input["timekeeping_type"] == "gps" ? $input["latitude"] ?? null : null,
                "longitude" => $input["timekeeping_type"] == "gps" ? $input["longitude"] ?? null : null,
                "allowable_radius" => $input["timekeeping_type"] == "gps" ? $input["allowable_radius"] ?? self::ALLOWABLE_RADIUS : null,
                "branch_id" => $input['branch_id'],
                "note" => $note,
            ], $input['timekeeping_config_id']);

            return response()->json([
                "error" => false,
                "message" => __("Chỉnh sửa thành công"),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => __("Chỉnh sửa thất bại"),
                "_message" => $e->getMessage()
            ]);
        }
    }


    /**
     * Xóa KH tiềm năng
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            //Xóa
            $this->timekeepingConfig->edit([
                'is_deleted' => 1
            ], $input['timekeeping_config_id']);

            return [
                'error' => false,
                'message' => __('Xóa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xóa thất bại')
            ];
        }
    }

    /**
     * Lấy ip hiện tại
     *
     * @return array|mixed
     */
    public function currentIp()
    {
        $ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));

        return [
            'ip' => $ip
        ];
    }

    /**
     * Cập nhật trạng thái
     *
     * @param $input
     * @return array|mixed
     */
    public function changeStatus($input)
    {
        try {
            //Cập nhật trạng thái
            $this->timekeepingConfig->edit([
                'is_actived' => $input['is_actived']
            ], $input['timekeeping_config_id']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ];
        }
    }

}
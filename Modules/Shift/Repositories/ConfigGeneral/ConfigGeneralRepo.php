<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/10/2022
 * Time: 16:00
 */

namespace Modules\Shift\Repositories\ConfigGeneral;


use Modules\Shift\Models\ConfigGeneralTable;

class ConfigGeneralRepo implements ConfigGeneralRepoInterface
{
    protected $configGeneral;

    public function __construct(
        ConfigGeneralTable $configGeneral
    ) {
        $this->configGeneral = $configGeneral;
    }

    /**
     * Lấy data cấu hình chung
     *
     * @return array|mixed
     */
    public function getDataGeneral()
    {
        //Lấy thông tin cấu hình chung
        $getDataConfig = $this->configGeneral->getConfig();

        return [
            'list' => $getDataConfig
        ];
    }

    /**
     * Chỉnh sửa cấu hình chung
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            if (isset($input['arrayData']) && count($input['arrayData']) > 0) {
                $isActiveLate = 0;
                $isActiveOffCheckIn = 0;
                $isActiveSoon = 0;
                $isActiveOffCheckOut = 0;

                $numberLate = 0;
                $numberOffCheckIn = 0;
                $numberSoon = 0;
                $numberOffCheckOut = 0;

                foreach ($input['arrayData'] as $v) {
                    switch ($v['config_general_code']) {
                        case 'late_check_in':
                            $isActiveLate = $v['is_actived'];
                            $numberLate = $v['config_general_value'];
                            break;
                        case 'off_check_in':
                            $isActiveOffCheckIn = $v['is_actived'];
                            $numberOffCheckIn = $v['config_general_value'];
                            break;
                        case 'back_soon_check_out':
                            $isActiveSoon = $v['is_actived'];
                            $numberSoon = $v['config_general_value'];
                            break;
                        case 'off_check_out':
                            $isActiveOffCheckOut = $v['is_actived'];
                            $numberOffCheckOut = $v['config_general_value'];
                            break;
                    }
                }

                //Validate time đi trễ phải nhỏ hơn time đi trễ nghỉ không lương
                if ($isActiveLate == 1 && $isActiveOffCheckIn == 1 && $numberLate > $numberOffCheckIn) {
                    return [
                        'error' => true,
                        'message' => __('Số phút đi trễ phải nhỏ hơn số phút đi trễ tính nghỉ')
                    ];
                }

                //Validate time về sớm phải nhỏ hơn time về sớm nghỉ không lương
                if ($isActiveSoon == 1 && $isActiveOffCheckOut == 1 && $numberSoon > $numberOffCheckOut) {
                    return [
                        'error' => true,
                        'message' => __('Số phút về sớm phải nhỏ hơn số phút về sớm tính nghỉ')
                    ];
                }

                foreach ($input['arrayData'] as $v) {
                    //Chỉnh sửa cấu hình chung
                    $this->configGeneral->edit([
                        'is_actived' => $v['is_actived'],
                        'config_general_value' => $v['config_general_value'],
                        'updated_by' => Auth()->id()
                    ], $v['config_general_id']);
                }
            }

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại')
            ];
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/05/2021
 * Time: 15:40
 */

namespace Modules\Customer\Repositories\CustomerInfoTemp;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\CustomerInfoTempTable;
use Modules\Customer\Models\CustomerTable;
use Modules\Customer\Models\DistrictTable;
use Modules\Customer\Models\ProvinceTable;

class CustomerInfoTempRepo implements CustomerInfoTempRepoInterface
{
    protected $infoTemp;

    public function __construct(
        CustomerInfoTempTable $infoTemp
    )
    {
        $this->infoTemp = $infoTemp;
    }

    /**
     * Danh sách thông tin cần cập nhật
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->infoTemp->getList($filters);

        return [
            "list" => $list,
        ];
    }

    /**
     * Data view xác nhận thông tin cần cập nhật
     *
     * @param $input
     * @return array|mixed
     */
    public function dataViewConfirm($input)
    {
        $mProvince = app()->get(ProvinceTable::class);
        $mDistrict = app()->get(DistrictTable::class);

        //Lấy thông tin cần cập nhật
        $info = $this->infoTemp->getInfo($input['customer_info_temp_id']);
        //Lấy option tỉnh thành
        $optionProvince = $mProvince->getOptionProvince();

        $optionDistrict = [];
        if ($info['province_id_temp'] != null) {
            //Lấy option quận huyện
            $optionDistrict = $mDistrict->getOptionDistrict($info['province_id_temp']);
        }

        return [
            'item' => $info,
            'optionProvince' => $optionProvince,
            'optionDistrict' => $optionDistrict
        ];
    }

    const CONFIRM = "confirm";

    /**
     * Xác nhận thông tin cần cập nhật
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function confirm($input)
    {
        DB::beginTransaction();
        try {
            $mCustomer = app()->get(CustomerTable::class);

            $input['birthday'] = $input['birthday'] != null ? Carbon::createFromFormat('d/m/Y', $input['birthday'])->format('Y-m-d'): null;

            //Cập nhật thông tin KH
            $mCustomer->edit([
                "full_name" => $input['full_name'],
                "phone1" => $input['phone'],
                "gender" => $input['gender'],
                "email" => $input['email'],
                "province_id" => $input['province_id'],
                "district_id" => $input['district_id'],
                "address" => $input['address'],
                "birthday" => $input['birthday']
            ], $input['customer_id']);
            //Cập nhật thông tin cần xác nhận
            $this->infoTemp->edit([
                "full_name" => $input['full_name'],
                "phone" => $input['phone'],
                "gender" => $input['gender'],
                "email" => $input['email'],
                "province_id" => $input['province_id'],
                "district_id" => $input['district_id'],
                "address" => $input['address'],
                "birthday" => $input['birthday'],
                "status" => self::CONFIRM
            ], $input['customer_info_temp_id']);

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Xác nhận thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Xác nhận thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }
}
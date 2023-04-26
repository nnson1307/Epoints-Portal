<?php


namespace Modules\Delivery\Repositories\DeliveryCost;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Delivery\Models\DeliveryCostDetailTable;
use Modules\Delivery\Models\DeliveryCostMapMethodTable;
use Modules\Delivery\Models\DeliveryCostTable;
use Modules\Delivery\Models\DeliveryMethodConfigTable;
use Modules\Delivery\Models\DistrictTable;
use Modules\Delivery\Models\ProvinceTable;

class DeliveryCostRepo implements DeliveryCostRepoInterface
{
    protected $deliveryCost;
    public function __construct(
        DeliveryCostTable $deliveryCost
    )
    {
        $this->deliveryCost = $deliveryCost;
    }

    /**
     * Danh sach chi phi giao hang
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->deliveryCost->getList($filters);
        return [
            'list' => $list,
        ];
    }

    /**
     * Luu chi phi giao hang
     *
     * @param $data
     * @return array|mixed
     */
    public function store($data)
    {
        try {
            $mDeliveryCostDetail = new DeliveryCostDetailTable();

            $data['delivery_cost'] = str_replace(',', '', $data['delivery_cost']);
            $data['delivery_cost'] = (float)$data['delivery_cost'];
            if ($data['delivery_cost'] < 0) {
                return [
                    'error' => true,
                    'message' => __('Chi phí vận chuyển tối thiểu là 0')
                ];
            }

            if (isset($data['is_delivery_fast'])){
                if (!isset($data['delivery_fast_cost']) || str_replace(',', '', $data['delivery_fast_cost']) < 0) {
                    return [
                        'error' => true,
                        'message' => __('Chi phí giao hàng hỏa tốc tối thiểu là 0')
                    ];
                }
            }

            $data['is_system'] = (int)$data['is_system'];
            // check is_system
            if ($data['is_system'] == 1) {
                $this->deliveryCost->updateAll(['is_system' => 0]);
            }

            // check postcode đã có chi phí vận chuyển hay chưa
            $arrPostcode = [];
            if (isset($data['district_id']) && $data['district_id'] > 0) {
                $mDistrict = new DistrictTable();
                foreach ($data['district_id'] as $key => $value) {
                    // get postcode by id
                    $districtDetail = $mDistrict->getItemUpdate($value);
                    if($districtDetail != null){
                        if ($mDeliveryCostDetail->checkPostcode($districtDetail['postcode'], $value, null) != null) {
                            $temp = [
                                'district_name' =>  $districtDetail['name'],
                                'postcode' =>  $districtDetail['postcode']
                            ];
                            $arrPostcode[] = $temp;
                        }
                    }
                }
            }

            if (count($arrPostcode) > 0) {
                $mess = '';
                foreach ($arrPostcode as $item) {
                    $mess = $mess . $item['district_name'] .' - '. $item['postcode'] .'</br>';
                }
                return [
                    'error' => true,
                    'message' => $mess .__('đã được cấu hình')
                ];
            }

            $dataInsert = [
                'delivery_cost_name'  => $data['delivery_cost_name'],
                'is_system'  => $data['is_system'],
                'delivery_cost'  => $data['delivery_cost'],
                'created_by'  => Auth::id(),
                'is_delivery_fast' => isset($data['is_delivery_fast']) ? 1 : 0,
                'delivery_fast_cost' => str_replace(',', '', $data['delivery_fast_cost']),
            ];
            $deliveryCostId = $this->deliveryCost->store($dataInsert);
            // update delivery cost code
            $deliveryCostCode = 'DCC_' . date('dmY') . sprintf("%02d", $deliveryCostId);
            $this->deliveryCost->edit([
                'delivery_cost_code' => $deliveryCostCode
            ], $deliveryCostId);

            // insert delivery cost detail
            if (isset($data['district_id']) && $data['district_id'] > 0) {
                $mDistrict = new DistrictTable();
                foreach ($data['district_id'] as $key => $value) {
                    // get postcode by id
                    $districtDetail = $mDistrict->getItemUpdate($value);
                    if ($districtDetail != null){
                        $dataDetail = [
                            'delivery_cost_code' => $deliveryCostCode,
                            'province_id' => $districtDetail['provinceid'],
                            'postcode' => $districtDetail['postcode'],
                            'district_id' => $value,
                            'created_by' => Auth::id()
                        ];
                        $deliveryCostDetailId = $mDeliveryCostDetail->store($dataDetail);
                    }
                }
            }

            return [
                'error' => false,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại')
            ];
        }
    }

    /**
     * Cap nhat chi phi giao hang
     *
     * @param $data
     * @return array|mixed
     */
    public function update($data)
    {
        try {
            $mDeliveryCostDetail = new DeliveryCostDetailTable();

            $data['delivery_cost'] = str_replace(',', '', $data['delivery_cost']);
            $data['delivery_cost'] = (float)$data['delivery_cost'];
            if ($data['delivery_cost'] < 0) {
                return [
                    'error' => true,
                    'message' => __('Chi phí vận chuyển tối thiểu là 0')
                ];
            }

            if (isset($data['is_delivery_fast'])){
                if (!isset($data['delivery_fast_cost']) || str_replace(',', '', $data['delivery_fast_cost']) < 0) {
                    return [
                        'error' => true,
                        'message' => __('Chi phí giao hàng hỏa tốc tối thiểu là 0')
                    ];
                }
            }

            $data['is_system'] = (int)$data['is_system'];

            // check is_system
            if ($data['is_system'] == 1) {
                $this->deliveryCost->updateAll(['is_system' => 0]);
            }
            // check postcode đã có chi phí vận chuyển hay chưa
            $arrPostcode = [];
            if (isset($data['district_id']) && $data['district_id'] > 0) {
                $mDistrict = new DistrictTable();
                foreach ($data['district_id'] as $key => $value) {
                    // get postcode by id
                    $districtDetail = $mDistrict->getItemUpdate($value);
                    if ($districtDetail != null){
                        if ($mDeliveryCostDetail->checkPostcode($districtDetail['postcode'], $value, $data['delivery_cost_code']) != null) {
                            $temp = [
                                'district_name' =>  $districtDetail['name'],
                                'postcode' =>  $districtDetail['postcode']
                            ];
                            $arrPostcode[] = $temp;
                        }
                    }
                }
            }
            if (count($arrPostcode) > 0) {
                $mess = '';
                foreach ($arrPostcode as $item) {
                    $mess = $mess . $item['district_name'] .' - '. $item['postcode'] .'</br>';
                }
                return [
                    'error' => true,
                    'message' => $mess .__('đã được cấu hình')
                ];
            }
            $dataUpdate = [
                'delivery_cost_name' => $data['delivery_cost_name'],
                'delivery_cost' => $data['delivery_cost'],
                'is_system' => $data['is_system'],
                'is_delivery_fast' => isset($data['is_delivery_fast']) ? 1 : 0,
                'delivery_fast_cost' => str_replace(',', '', $data['delivery_fast_cost']),
                'updated_by' => Auth::id(),
            ];

            $this->deliveryCost->edit($dataUpdate, $data['delivery_cost_id']);

            // update delivery cost detail
            $mDistrict = new DistrictTable();
            $mDeliveryCostDetail->remove($data['delivery_cost_code']);

            if (isset($data['district_id']) && $data['district_id'] > 0) {
                foreach ($data['district_id'] as $key => $value) {
                    // get postcode by id
                    $districtDetail = $mDistrict->getItemUpdate($value);
                    if ($districtDetail != null){
                        $dataDetail = [
                            'delivery_cost_code' => $data['delivery_cost_code'],
                            'province_id' => $districtDetail['provinceid'],
                            'postcode' => $districtDetail['postcode'],
                            'district_id' => $value,
                            'created_by' => Auth::id()
                        ];
                        $deliveryCostDetailId = $mDeliveryCostDetail->store($dataDetail);
                    }
                }
            }

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Chi tiet chi phi giao hang
     *
     * @param $deliveryCostId
     * @return mixed
     */
    public function getDetail($deliveryCostId)
    {
        return $this->deliveryCost->getDetail($deliveryCostId);
    }

    /**
     * Xoa chi phi giao hang
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        try {
            $this->deliveryCost->delDeliveryCost($id);
            return [
                'error' => false,
                'message' => __('Xoá thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá thất bại')
            ];
        }
    }


    /**
     * data view màn hình chỉnh sửa chi phí vận chuyển
     *
     * @param $deliveryCostId
     * @return array|mixed
     */
    public function dataViewEdit($deliveryCostId)
    {
        $mDeliveryCostDetail = new DeliveryCostDetailTable();
        $mDistrict = new DistrictTable();
        $mProvince = new ProvinceTable();
        $optionProvince = $mProvince->getOptionProvince();
        $optionDistrict = $mDistrict->getOptionDistrict();
        $item = $this->deliveryCost->getDetail($deliveryCostId);
        $itemDetail = $mDeliveryCostDetail->getDetailByCode($item['delivery_cost_code']);
        // array province selected
        $arrProvince = [];
        $listProvinceSelected = collect($itemDetail)->groupBy('province_id');
        if ($listProvinceSelected != null && count($listProvinceSelected) > 0) {
            foreach ($listProvinceSelected as $key => $v) {
                $arrProvince[] = $key;
            }
        }
        // array district selected
        $arrDistrict = [];
        if ($itemDetail != null && count($itemDetail) > 0) {
            foreach ($itemDetail as $k => $v) {
                $arrDistrict[] = $v['district_id'];
            }
        }

        $data = [
            'item' => $item,
            'optionDistrict' => $optionDistrict,
            'optionProvince' => $optionProvince,
            'arrDistrict' => $arrDistrict,
            'arrProvince' => $arrProvince,
        ];
        return $data;
    }

    /**
     * Lấy danh sách quận/huyện (town)
     *
     * @param array $filters
     * @return mixed
     */
    public function getOptionDistrict(array $filters = [])
    {
        $mDistrict = new DistrictTable();
        if (!isset($filters['page'])) {
            $filters['page'] = 1;
        }
        return $mDistrict->getList($filters);
    }

    /**
     * data view màn hình tạo chi phí vận chuyển
     *
     * @return array|mixed
     */
    public function dataViewCreate()
    {
        $mProvince = new ProvinceTable();
        $optionProvince = $mProvince->getOptionProvince();
        return [
            'optionProvince' => $optionProvince
        ];
    }

    /**
     * Danh sách huyện theo tỉnh thành phân trang
     *
     * @param $input
     * @return mixed
     */
    public function loadDistrictPagination($input)
    {
        $data = [];
        $mDistrict = new DistrictTable();
        $optionDistrict = $mDistrict->getDistrictByArrayProvince($input);
        if (count($optionDistrict) > 0) {
            foreach ($optionDistrict as $value) {
                $data [] = [
                    'id' => $value['districtid'],
                    'name' => $value['name'],
                ];
            }
        }
        return [
            'optionDistrict' => $data,
            'pagination' => $optionDistrict->nextPageUrl() ? true : false
        ];
    }

    /**
     * Lấy danh sách phương thức vận chuyển
     * @param $input
     * @return mixed|void
     */
    public function getListMethodDelivery()
    {
        $mDeliveryMethodConfig = app()->get(DeliveryMethodConfigTable::class);
        return $mDeliveryMethodConfig->getAll();
    }
}
<?php
namespace Modules\Delivery\Repositories\PickupAddress;

use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Delivery\Models\PickupAddressTable;

class PickupAddressRepo implements PickupAddressRepoInterface
{
    protected $pickupAddress;

    public function __construct(
        PickupAddressTable $pickupAddress
    )
    {
        $this->pickupAddress = $pickupAddress;
    }

    /**
     * Danh sach dia chi lay hang
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->pickupAddress->getList($filters);
        return [
            'list' => $list,
        ];
    }

    /**
     * Tao moi dia chi lay hang
     *
     * @param $data
     * @return array|mixed
     */
    public function store($data)
    {
        try {
            $data['created_by'] = Auth::id();
            $pickupAddressId = $this->pickupAddress->store($data);
            // update pick up address code
            $pickupAddressCode = 'PUA_' . date('dmY') . sprintf("%02d", $pickupAddressId);
            $this->pickupAddress->edit([
                'pickup_address_code' => $pickupAddressCode
            ], $pickupAddressId);

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
     * Chinh sua dia chi lay hang
     *
     * @param $data
     * @return array
     */
    public function update($data)
    {
        try {
            $id = $data['pickup_address_id'];
            $dataUpdate = [
                'address' => $data['address'],
                'is_actived' => $data['is_actived'],
                'updated_by' => Auth::id(),
            ];
            $this->pickupAddress->edit($dataUpdate, $id);
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

    /**
     * Lay chi tiet dia chi lay hang
     *
     * @param $pickupAddressId
     * @return mixed
     */
    public function getDetail($pickupAddressId)
    {
        return $this->pickupAddress->getDetail($pickupAddressId);
    }

    /**
     * Xoa dia chi lay hang
     *
     * @param $pickupAddressId
     * @return mixed|void
     */
    public function destroy($pickupAddressId)
    {
        try {
            $this->pickupAddress->edit([
                'is_deleted' => 1
            ], $pickupAddressId);
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
}
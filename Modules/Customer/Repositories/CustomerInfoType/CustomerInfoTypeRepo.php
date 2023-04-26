<?php

namespace Modules\Customer\Repositories\CustomerInfoType;

use Modules\Customer\Models\CustomerInfoTypeTable;

class CustomerInfoTypeRepo implements CustomerInfoTypeRepoInterface
{
    protected $customerInfoType;
    public function __construct(CustomerInfoTypeTable $customerInfoType)
    {
        $this->customerInfoType = $customerInfoType;
    }

    public function getList(array $filters = [])
    {
        $list = $this->customerInfoType->getList($filters);
        return [
            "list" => $list,
        ];
    }

    /**
     * Popup loại thông tin kèm them
     *
     * @param $input
     * @return array
     */
    public function dataViewCreate($input)
    {
        $html = \View::make('customer::customer-info-type.modal-create')->render();
        return [
            'html' => $html
        ];
    }

    /**
     * Thêm loại thông tin kèm them
     *
     * @param $input
     * @return array
     */
    public function store($input)
    {
        try {
            $this->customerInfoType->add($input);
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
     * popup view chỉnh sửa loại thông tin kèm them
     *
     * @param $input
     * @return array
     */
    public function dataViewEdit($input)
    {
        $getDetail = $this->customerInfoType->getDetail($input['id']);
        $html = \View::make('customer::customer-info-type.modal-edit', [
            'item' => $getDetail
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $input
     * @return array
     */
    public function update($input)
    {
        try {
            $id = $input['customer_info_type_id'];
            if (isset($id) && $id != null) {
                $this->customerInfoType->edit([
                    'customer_info_type_name_vi' => $input['customer_info_type_name_vi'],
                    'customer_info_type_name_en' => $input['customer_info_type_name_en']
                ], $id);
            } else {
                return [
                    'error' => true,
                    'message' => __('Cập nhật thất bại')
                ];
            }
            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }

    /**
     * Xoá loại thông tin kèm them
     *
     * @param $input
     * @return array
     */
    public function delete($input)
    {
        try {
            $id = $input['customer_info_type_id'];
            if (isset($id) && $id != null) {
                $this->customerInfoType->edit([
                    'is_deleted' => 1
                ], $id);
            } else {
                return [
                    'error' => true,
                    'message' => __('Xoá thất bại')
                ];
            }
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
     * Cập nhật trạng thái loại thông tin kèm theo
     *
     * @param $input
     * @return array
     */
    public function updateStatus($input)
    {
        try {
            $id = $input['customer_info_type_id'];
            if (isset($id) && $id != null) {
                $this->customerInfoType->edit([
                    'is_actived' => (int)$input['status']
                ], $id);
            } else {
                return [
                    'error' => true,
                    'message' => __('Cập nhật thất bại')
                ];
            }
            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }
}
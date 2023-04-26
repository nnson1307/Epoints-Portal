<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ZNS\Repositories\CustomerCare;

use Illuminate\Http\Request;
use Modules\ZNS\Http\Api\ZnsApi;
use Modules\ZNS\Models\ZaloCustomerCareTable;
use Modules\ZNS\Models\ZaloCustomerTagTable;
use Modules\ZNS\Models\ZaloCustomerTagMapTable;
use Modules\ZNS\Models\ProvinceTable;
use Modules\ZNS\Models\DistrictTable;
use GuzzleHttp\Client;
use Carbon\Carbon;


class CustomerCareRepository implements CustomerCareRepositoryInterface
{
    /**
     * @var ZaloCustomerCareTable
     */
    protected $customerCare;
    protected $timestamps = true;


    public function __construct(ZaloCustomerCareTable $customerCare)
    {
        $this->customerCare = $customerCare;
    }

    /**
     *get list CustomerCare
     */
    public function list(array $filters = [])
    {
        $mZaloCustomertagTable = app()->get(ZaloCustomertagTable::class);
        return [
            'list' => $this->customerCare->getList($filters),
            'params' => $filters,
            'tag_list' => $mZaloCustomertagTable->getName(),
        ];
    }

    /**
     *get list CustomerCare
     */
    public function listTag(array $filters = [])
    {
        $mZaloCustomertagTable = app()->get(ZaloCustomertagTable::class);
        return [
            'list' => $mZaloCustomertagTable->getList($filters),
            'params' => $filters,
        ];
    }

    /**
     * delete template
     */
    public function remove($id)
    {
        $this->customerCare->remove($id);
    }

    /**
     * add template
     */
    public function add(array $data)
    {

        return $this->customerCare->add($data);
    }

    /**
     * add new tag
     */
    public function addTagAction(array $data)
    {
        $mZaloCustomertagTable = app()->get(ZaloCustomertagTable::class);
        $zalo_customer_tag_id = $mZaloCustomertagTable->add($data);
        if ($zalo_customer_tag_id) {
            if ($zalo_customer_tag_id == -1) {
                return [
                    "status" => -1,
                    "message" => __("Tên thẻ đã tồn tại"),
                ];
            }
            return [
                "status" => 1,
                "message" => __("Thêm thành công"),
            ];
        }
        return [
            "status" => 0,
            "message" => __("Thêm thất bại"),
        ];
    }

    /**
     * add new tag
     */
    public function editTagAction(array $data, $zalo_customer_tag_id)
    {
        $mZaloCustomertagTable = app()->get(ZaloCustomertagTable::class);
        $zalo_customer_tag_id = $mZaloCustomertagTable->edit($data, $zalo_customer_tag_id);
        if ($zalo_customer_tag_id) {
            return [
                "status" => 1,
                "message" => __("Chỉnh sửa thành công"),
            ];
        }
        return [
            "status" => 0,
            "message" => __("Chỉnh sửa thất bại"),
        ];
    }

    public function removeAction($id)
    {
        $status = $this->customerCare->remove($id);
        if ($status) {
            return [
                "status" => 1,
                "message" => __("Xóa thành công"),
            ];
        }
        return [
            "status" => 0,
            "message" => __("Xóa thất bại"),
        ];
    }

    public function removeTagAction($id)
    {
        $mZaloCustomertagTable = app()->get(ZaloCustomertagTable::class);
        $status = $mZaloCustomertagTable->remove($id);
        if ($status) {
            if ($status == -1) {
                return [
                    "status" => -1,
                    "message" => __("Thẻ đang được sử dụng"),
                ];
            }
            return [
                "status" => 1,
                "message" => __("Xóa thành công"),
            ];
        }
        return [
            "status" => 0,
            "message" => __("Xóa thất bại"),
        ];
    }

    /*
     * edit template
     */
    public function edit(array $data, $id)
    {
        return $this->customerCare->edit($data, $id);
    }

    /*
     * edit Customer Care
     */
    public function editCustomerCareAction(array $data)
    {
        $id = $data['zalo_customer_care_id'];
        $list_tag = isset($data['zalo_customer_tag_id']) ? $data['zalo_customer_tag_id'] : [];
        unset($data['zalo_customer_tag_id']);
        $zalo_customer_care_id = $this->customerCare->edit($data, $id);
        if ($zalo_customer_care_id) {
            $mZaloCustomertagMapTable = app()->get(ZaloCustomertagMapTable::class);
            $mZaloCustomertagMapTable->removeByZaloCustomerCareId($id);
            if (count($list_tag)) {
                foreach ($list_tag as $value) {
                    $tag_map = [
                        "zalo_customer_care_id" => $id,
                        "zalo_customer_tag_id" => $value
                    ];
                    $mZaloCustomertagMapTable->add($tag_map);
                }
            }
            return [
                "status" => 1,
                "message" => __("Chỉnh sửa thành công"),
            ];
        }
        return [
            "status" => 0,
            "message" => __("Chỉnh sửa thất bại"),
        ];
    }

    /*
     * edit template
     */
    public function editCustomerCare(array $params)
    {
        $item = $this->getItem($params['id']);
        $mProvinceTable = app()->get(ProvinceTable::class);
        $mDistrictTable = app()->get(DistrictTable::class);
        $mZaloCustomertagTable = app()->get(ZaloCustomertagTable::class);

        return [
            'status' => 1,
            'html' => view('zns::customer_care.edit', [
                "item" => $item,
                "optionProvince" => $mProvinceTable->getOption(),
                "optionDistrict" => $mDistrictTable->getOption($item->province_id != null ? $item->province_id : 79),
                "listTag" => $mZaloCustomertagTable->getName(),
            ])->render(),
        ];
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->customerCare->getItem($id);
    }

    public function getDistrict($province_id = "")
    {
        $mDistrictTable = app()->get(DistrictTable::class);
        $list_district = $mDistrictTable->getOption($province_id);
        return [
            "status" => 1,
            "list_district" => $list_district,
        ];
    }

    public function synchronized()
    {
        $oClient = new Client();
        $mZnsApi = app()->get(ZnsApi::class);
        //Call api lấy template zns
        $list_customer_care = $mZnsApi->getCustomerCare();
        if ($list_customer_care['ErrorCode'] == 0 && $list_customer_care['Data']) {
            foreach ($list_customer_care['Data'] as $customer_detail) {
                $data_insert = [
                    "full_name" => $customer_detail['display_name'],
                    "avatar" => $customer_detail['avatar'],
//                    "phone_number" => $customer_detail['1'],
//                    "address" => $customer_detail['1'],
//                    "province_id" => $customer_detail['1'],
                    "user_gender" => $customer_detail['user_gender'],
                    "status" => "follower",
                    "zalo_user_id" => $customer_detail['user_id'],
                ];
                $zalo_customer_care_id = $this->customerCare->insertOrUpdateMultipleRows($data_insert);
            }
        }
        return [
            "status" => 1,
            "message" => __("Đồng bộ thành công")
        ];
    }


}
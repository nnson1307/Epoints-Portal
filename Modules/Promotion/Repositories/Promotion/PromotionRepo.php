<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/8/2020
 * Time: 2:27 PM
 */

namespace Modules\Promotion\Repositories\Promotion;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\MemberLevelTable;
use Modules\Admin\Models\ProductCategoryTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ServiceCard;
use Modules\Admin\Models\ServiceCardGroup;
use Modules\Admin\Models\ServiceCategoryTable;
use Modules\Admin\Models\ServiceTable;
use Modules\Promotion\Models\PromotionDailyTimeTable;
use Modules\Promotion\Models\PromotionDateTimeTable;
use Modules\Promotion\Models\PromotionDetailTable;
use Modules\Promotion\Models\PromotionMasterTable;
use Modules\Promotion\Models\PromotionMonthlyTimeTable;
use Modules\Promotion\Models\PromotionObjectApplyTable;
use Modules\Promotion\Models\PromotionWeeklyTimeTable;
use Modules\Promotion\Models\ServiceCardTable;

class PromotionRepo implements PromotionRepoInterface
{
    protected $promotion;

    public function __construct(
        PromotionMasterTable $promotion
    )
    {
        $this->promotion = $promotion;
    }

    /**
     * Danh sách CTKM
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->promotion->getList($filters);

        return [
            'list' => $list
        ];
    }

    /**
     * Dữ liệu view khách hàng
     *
     * @return array|mixed
     */
    public function dataCreate()
    {
        $mBranch = new BranchTable();
        $mMemberLevel = new MemberLevelTable();
        $mCustomerGroup = new CustomerGroupTable();
        $mCustomer = new CustomerTable();

        session()->forget('product_temp');
        session()->forget('service_temp');
        session()->forget('service_card_temp');
        session()->forget('remove_product');
        session()->forget('remove_service');
        session()->forget('remove_service_card');

        session()->forget('product');
        session()->forget('service');
        session()->forget('service_card');
        session()->forget('list_all');

        $branch = $mBranch->getBranchOption();
        $memberLevel = $mMemberLevel->getOptionMemberLevel();
        $customerGroup = $mCustomerGroup->getOption();
        $customer = $mCustomer->getCustomerOption();

        //Vị trí hiển thị nổi bật
        $position1 = $this->promotion->getPosition(1);
        $position2 = $this->promotion->getPosition(2);
        $position3 = $this->promotion->getPosition(3);
        $position4 = $this->promotion->getPosition(4);
        $position5 = $this->promotion->getPosition(5);

        return [
            'branch' => $branch,
            'memberLevel' => $memberLevel,
            'customerGroup' => $customerGroup,
            'customer' => $customer,
            'position1' => $position1,
            'position2' => $position2,
            'position3' => $position3,
            'position4' => $position4,
            'position5' => $position5,
        ];
    }

    /**
     * Show popup sp/dv/thẻ dv
     *
     * @param $data
     * @return mixed|void
     */
    public function showPopup($data)
    {
        $mProduct = new \Modules\Promotion\Models\ProductChildTable();
        $mService = new \Modules\Promotion\Models\ServiceTable();
        $mServiceCard = new ServiceCardTable();

        if ($data['type'] == 'product') {
            //Get session product
            $arrProductTemp = [];
            if (session()->get('product')) {
                $arrProductTemp = session()->get('product');
            }

            session()->forget('product_temp');
            session()->put('product_temp', $arrProductTemp);
            session()->forget('remove_product');

            $list = $mProduct->getListChild([]);

            $html = \View::make('promotion::promotion.popup.pop-product', [
                'list' => $list,
                'FILTER' => $this->productFilters(),
                'arrProductTemp' => $arrProductTemp
            ])->render();
        } else if ($data['type'] == 'service') {
            //Get session service
            $arrServiceTemp = [];
            if (session()->get('service')) {
                $arrServiceTemp = session()->get('service');
            }

            session()->forget('service_temp');
            session()->put('service_temp', $arrServiceTemp);
            session()->forget('remove_service');

            $list = $mService->getList([]);

            $html = \View::make('promotion::promotion.popup.pop-service', [
                'list' => $list,
                'FILTER' => $this->serviceFilters(),
                'arrServiceTemp' => $arrServiceTemp
            ])->render();
        } else if ($data['type'] == 'service_card') {
            //Get session service card
            $arrServiceCardTemp = [];
            if (session()->get('service_card')) {
                $arrServiceCardTemp = session()->get('service_card');
            }

            session()->forget('service_card_temp');
            session()->put('service_card_temp', $arrServiceCardTemp);
            session()->forget('remove_service_card');

            $list = $mServiceCard->getList([]);

            $html = \View::make('promotion::promotion.popup.pop-service-card', [
                'list' => $list,
                'FILTER' => $this->serviceCardFilters(),
                'arrServiceCardTemp' => $arrServiceCardTemp
            ])->render();
        }

        return [
            'html' => $html,
        ];
    }

    /**
     * Filter sản phẩm
     *
     * @return array
     */
    protected function productFilters()
    {
        $mProductCategory = new ProductCategoryTable();

        $optionCate = $mProductCategory->getAll()->toArray();

        $groupCate = (["" => __("Chọn nhóm sản phẩm")]) + array_combine(array_column($optionCate, 'product_category_id'), array_column($optionCate, 'category_name'));

        return [
            'products$product_category_id' => [
                'data' => $groupCate
            ],
        ];
    }

    /**
     * Filter dịch vụ
     *
     * @return array
     */
    protected function serviceFilters()
    {
        $mServiceCategory = new ServiceCategoryTable();

        $optionCate = $mServiceCategory->getOptionServiceCategory();

        $groupCate = (['' => __('Chọn nhóm')]) + array_combine(array_column($optionCate, 'service_category_id'), array_column($optionCate, 'name'));

        return [
            'services$service_category_id' => [
                'data' => $groupCate
            ],
        ];
    }

    /**
     * Filter dịch vụ
     *
     * @return array
     */
    protected function serviceCardFilters()
    {
        $mServiceCardGroup = new ServiceCardGroup();

        $optionGroup = $mServiceCardGroup->getAllName()->toArray();

        $groupCate = (['' => __('Chọn nhóm')]) + array_combine(array_column($optionGroup, 'service_card_group_id'), array_column($optionGroup, 'name'));

        return [
            'service_cards$service_card_group_id' => [
                'data' => $groupCate
            ],
        ];
    }

    /**
     * Ajax phân trang, filter product
     *
     * @param $filter
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listProduct($filter)
    {
        $mProduct = new \Modules\Promotion\Models\ProductChildTable();

        $list = $mProduct->getListChild($filter);

        //Get session product temp
        $arrProductTemp = [];
        if (session()->get('product_temp')) {
            $arrProductTemp = session()->get('product_temp');
        }

        return view('promotion::promotion.popup.list-product', [
            'list' => $list,
            'page' => $filter['page'],
            'arrProductTemp' => $arrProductTemp
        ]);
    }

    /**
     * Ajax filter, phân trang service
     *
     * @param $filter
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listService($filter)
    {
        $mService = new \Modules\Promotion\Models\ServiceTable();

        $list = $mService->getList($filter);

        //Get session service temp
        $arrServiceTemp = [];
        if (session()->get('service_temp')) {
            $arrServiceTemp = session()->get('service_temp');
        }

        return view('promotion::promotion.popup.list-service', [
            'list' => $list,
            'page' => $filter['page'],
            'arrServiceTemp' => $arrServiceTemp
        ]);
    }

    /**
     * Ajax filter, phân trang service card
     *
     * @param $filter
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listServiceCard($filter)
    {
        $mServiceCard = new ServiceCardTable();

        $list = $mServiceCard->getList($filter);

        //Get session service temp
        $arrServiceCardTemp = [];
        if (session()->get('service_card_temp')) {
            $arrServiceCardTemp = session()->get('service_card_temp');
        }

        return view('promotion::promotion.popup.list-service-card', [
            'list' => $list,
            'page' => $filter['page'],
            'arrServiceCardTemp' => $arrServiceCardTemp
        ]);
    }

    /**
     * Chọn all trên 1 page sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed|void
     */
    public function chooseAll($data)
    {
        if ($data['type'] == 'product') {
            //Get session 9
            $arrProduct = [];
            if (session()->get('product')) {
                $arrProduct = session()->get('product');
            }
            //Get session temp
            $arrProductTemp = [];
            if (session()->get('product_temp')) {
                $arrProductTemp = session()->get('product_temp');
            }
            //Merge vào array temp
            $arrProductNew = [];
            if (count($data['arr_check']) > 0) {
                foreach ($data['arr_check'] as $v) {
                    $arrProductNew[$v['object_code']] = [
                        'object_type' => $data['type'],
                        'object_id' => $v['object_id'],
                        'object_code' => $v['object_code'],
                        'object_name' => $v['object_name'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => null,
                        'quantity_buy' => null,
                        'quantity_gift' => null,
                        'gift_object_type' => null,
                        'gift_object_id' => null,
                        'gift_object_code' => null,
                        'gift_object_name' => null,
                        'is_actived' => 1
                    ];
                }
            }
            //Merge 2 array temp + new
            $arrProductTempNew = array_merge($arrProductTemp, $arrProductNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrProductTempNew, $arrProduct);
            //Lưu session temp mới
            session()->forget('product_temp');
            session()->put('product_temp', $arrResult);
        } else if ($data['type'] == 'service') {
            //Get session 9
            $arrService = [];
            if (session()->get('service')) {
                $arrService = session()->get('service');
            }
            //Get session temp
            $arrServiceTemp = [];
            if (session()->get('service_temp')) {
                $arrServiceTemp = session()->get('service_temp');
            }
            //Merge vào array temp
            $arrServiceNew = [];
            if (count($data['arr_check']) > 0) {
                foreach ($data['arr_check'] as $v) {
                    $arrServiceNew[$v['object_code']] = [
                        'object_type' => $data['type'],
                        'object_id' => $v['object_id'],
                        'object_code' => $v['object_code'],
                        'object_name' => $v['object_name'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => null,
                        'quantity_buy' => null,
                        'quantity_gift' => null,
                        'gift_object_type' => null,
                        'gift_object_id' => null,
                        'gift_object_code' => null,
                        'gift_object_name' => null,
                        'is_actived' => 1
                    ];
                }
            }
            //Merge 2 array temp + new
            $arrServiceTempNew = array_merge($arrServiceTemp, $arrServiceNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrServiceTempNew, $arrService);
            //Lưu session temp mới
            session()->forget('service_temp');
            session()->put('service_temp', $arrResult);
        } else if ($data['type'] == 'service_card') {
            //Get session 9
            $arrServiceCard = [];
            if (session()->get('service_card')) {
                $arrServiceCard = session()->get('service_card');
            }
            //Get session temp
            $arrServiceCardTemp = [];
            if (session()->get('service_card_temp')) {
                $arrServiceCardTemp = session()->get('service_card_temp');
            }
            //Merge vào array temp
            $arrServiceCardNew = [];
            if (count($data['arr_check']) > 0) {
                foreach ($data['arr_check'] as $v) {
                    $arrServiceCardNew[$v['object_code']] = [
                        'object_type' => $data['type'],
                        'object_id' => $v['object_id'],
                        'object_code' => $v['object_code'],
                        'object_name' => $v['object_name'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => null,
                        'quantity_buy' => null,
                        'quantity_gift' => null,
                        'gift_object_type' => null,
                        'gift_object_id' => null,
                        'gift_object_code' => null,
                        'gift_object_name' => null,
                        'is_actived' => 1
                    ];
                }
            }
            //Merge 2 array temp + new
            $arrServiceCardTempNew = array_merge($arrServiceCardNew, $arrServiceCard);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrServiceCardTempNew, $arrServiceCard);
            //Lưu session temp mới
            session()->forget('service_card_temp');
            session()->put('service_card_temp', $arrResult);
        }
    }

    /**
     * Chọn sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed|void
     */
    public function choose($data)
    {
        if ($data['type'] == 'product') {
            //Get session 9
            $arrProduct = [];
            if (session()->get('product')) {
                $arrProduct = session()->get('product');
            }
            //Get session temp
            $arrProductTemp = [];
            if (session()->get('product_temp')) {
                $arrProductTemp = session()->get('product_temp');
            }
            //Merge vào array temp
            $arrProductNew = [
                $data['object_code'] => [
                    'object_type' => $data['type'],
                    'object_id' => $data['object_id'],
                    'object_code' => $data['object_code'],
                    'object_name' => $data['object_name'],
                    'base_price' => $data['base_price'],
                    'promotion_price' => null,
                    'quantity_buy' => null,
                    'quantity_gift' => null,
                    'gift_object_type' => null,
                    'gift_object_id' => null,
                    'gift_object_code' => null,
                    'gift_object_name' => null,
                    'is_actived' => 1
                ]
            ];
            //Merge 2 array temp + new
            $arrProductTempNew = array_merge($arrProductTemp, $arrProductNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrProductTempNew, $arrProduct);
            //Lưu session temp mới
            session()->forget('product_temp');
            session()->put('product_temp', $arrResult);
        } else if ($data['type'] == 'service') {
            //Get session 9
            $arrService = [];
            if (session()->get('service')) {
                $arrService = session()->get('service');
            }
            //Get session temp
            $arrServiceTemp = [];
            if (session()->get('service_temp')) {
                $arrServiceTemp = session()->get('service_temp');
            }
            //Merge vào array temp
            $arrServiceNew = [
                $data['object_code'] => [
                    'object_type' => $data['type'],
                    'object_id' => $data['object_id'],
                    'object_code' => $data['object_code'],
                    'object_name' => $data['object_name'],
                    'base_price' => $data['base_price'],
                    'promotion_price' => null,
                    'quantity_buy' => null,
                    'quantity_gift' => null,
                    'gift_object_type' => null,
                    'gift_object_id' => null,
                    'gift_object_code' => null,
                    'gift_object_name' => null,
                    'is_actived' => 1
                ]
            ];
            //Merge 2 array temp + new
            $arrServiceTempNew = array_merge($arrServiceTemp, $arrServiceNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrServiceTempNew, $arrService);
            //Lưu session temp mới
            session()->forget('service_temp');
            session()->put('service_temp', $arrResult);
        } else if ($data['type'] == 'service_card') {
            //Get session 9
            $arrServiceCard = [];
            if (session()->get('service_card')) {
                $arrServiceCard = session()->get('service_card');
            }
            //Get session temp
            $arrServiceCardTemp = [];
            if (session()->get('service_card_temp')) {
                $arrServiceCardTemp = session()->get('service_card_temp');
            }
            //Merge vào array temp
            $arrServiceCardNew = [
                $data['object_code'] => [
                    'object_type' => $data['type'],
                    'object_id' => $data['object_id'],
                    'object_code' => $data['object_code'],
                    'object_name' => $data['object_name'],
                    'base_price' => $data['base_price'],
                    'promotion_price' => null,
                    'quantity_buy' => null,
                    'quantity_gift' => null,
                    'gift_object_type' => null,
                    'gift_object_id' => null,
                    'gift_object_code' => null,
                    'gift_object_name' => null,
                    'is_actived' => 1
                ]
            ];
            //Merge 2 array temp + new
            $arrServiceCardTempNew = array_merge($arrServiceCardTemp, $arrServiceCardNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrServiceCardTempNew, $arrServiceCard);
            //Lưu session temp mới
            session()->forget('service_card_temp');
            session()->put('service_card_temp', $arrResult);
        }
    }

    /**
     * Bỏ chọn all trên 1 page sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed|void
     */
    public function unChooseAll($data)
    {
        if ($data['type'] == 'product') {
            //Get session 9
            $arrProduct = [];
            if (session()->get('product')) {
                $arrProduct = session()->get('product');
            }
            //Get session temp
            $arrProductTemp = [];
            if (session()->get('product_temp')) {
                $arrProductTemp = session()->get('product_temp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrProductTemp, $arrProduct);
            $arrRemoveProductTemp = [];
            //Unset phần tử
            if (count($data['arr_un_check']) > 0) {
                foreach ($data['arr_un_check'] as $v) {
                    $arrRemoveProductTemp [] = $v['object_code'];
                    unset($arrResult[$v['object_code']]);
                }
            }
            //Lưu session temp mới
            session()->forget('product_temp');
            session()->put('product_temp', $arrResult);
            //Get session remove temp
            if (session()->get('remove_product')) {
                $arrRemoveProductTemp = session()->get('remove_product');
            }
            //Lưu session remove temp
            session()->forget('remove_product');
            session()->put('remove_product', $arrRemoveProductTemp);
        } else if ($data['type'] == 'service') {
            //Get session 9
            $arrService = [];
            if (session()->get('service')) {
                $arrService = session()->get('service');
            }
            //Get session temp
            $arrServiceTemp = [];
            if (session()->get('service_temp')) {
                $arrServiceTemp = session()->get('service_temp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrServiceTemp, $arrService);
            $arrRemoveServiceTemp = [];
            //Unset phần tử
            if (count($data['arr_un_check']) > 0) {
                foreach ($data['arr_un_check'] as $v) {
                    unset($arrResult[$v['object_code']]);
                }
            }
            //Lưu session temp mới
            session()->forget('service_temp');
            session()->put('service_temp', $arrResult);
            //Get session remove temp
            if (session()->get('remove_service')) {
                $arrRemoveServiceTemp = session()->get('remove_service');
            }
            //Lưu session remove temp
            session()->forget('remove_service');
            session()->put('remove_service', $arrRemoveServiceTemp);
        } else if ($data['type'] == 'service_card') {
            //Get session 9
            $arrServiceCard = [];
            if (session()->get('service_card')) {
                $arrServiceCard = session()->get('service_card');
            }
            //Get session temp
            $arrServiceCardTemp = [];
            if (session()->get('service_card_temp')) {
                $arrServiceCardTemp = session()->get('service_card_temp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrServiceCardTemp, $arrServiceCard);
            $arrRemoveServiceCardTemp = [];
            //Unset phần tử
            if (count($data['arr_un_check']) > 0) {
                foreach ($data['arr_un_check'] as $v) {
                    unset($arrResult[$v['object_code']]);
                }
            }
            //Lưu session temp mới
            session()->forget('service_card_temp');
            session()->put('service_card_temp', $arrResult);
            //Get session remove temp
            if (session()->get('remove_service_card')) {
                $arrRemoveServiceCardTemp = session()->get('remove_service_card');
            }
            //Lưu session remove temp
            session()->forget('remove_service_card');
            session()->put('remove_service_card', $arrRemoveServiceCardTemp);
        }
    }

    /**
     * Bỏ chọn sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed|void
     */
    public function unChoose($data)
    {
        if ($data['type'] == 'product') {
            //Get session 9
            $arrProduct = [];
            if (session()->get('product')) {
                $arrProduct = session()->get('product');
            }
            //Get session temp
            $arrProductTemp = [];
            if (session()->get('product_temp')) {
                $arrProductTemp = session()->get('product_temp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrProductTemp, $arrProduct);
            //Unset phần tử
            unset($arrResult[$data['object_code']]);
            //Lưu session temp mới
            session()->forget('product_temp');
            session()->put('product_temp', $arrResult);
            //Get session remove temp
            $arrRemoveProductTemp = [];
            if (session()->get('remove_product')) {
                $arrRemoveProductTemp = session()->get('remove_product');
            }
            //Lưu session remove temp
            $arrRemoveProductTemp [] = $data['object_code'];
            session()->forget('remove_product');
            session()->put('remove_product', $arrRemoveProductTemp);
        } else if ($data['type'] == 'service') {
            //Get session 9
            $arrService = [];
            if (session()->get('service')) {
                $arrService = session()->get('service');
            }
            //Get session temp
            $arrServiceTemp = [];
            if (session()->get('service_temp')) {
                $arrServiceTemp = session()->get('service_temp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrServiceTemp, $arrService);
            //Unset phần tử
            unset($arrResult[$data['object_code']]);
            //Lưu session temp mới
            session()->forget('service_temp');
            session()->put('service_temp', $arrResult);
            //Get session remove temp
            $arrRemoveServiceTemp = [];
            if (session()->get('remove_service')) {
                $arrRemoveServiceTemp = session()->get('remove_service');
            }
            //Lưu session remove temp
            $arrRemoveServiceTemp [] = $data['object_code'];
            session()->forget('remove_service');
            session()->put('remove_service', $arrRemoveServiceTemp);
        } else if ($data['type'] == 'service_card') {
            //Get session 9
            $arrServiceCard = [];
            if (session()->get('service_card')) {
                $arrServiceCard = session()->get('service_card');
            }
            //Get session temp
            $arrServiceCardTemp = [];
            if (session()->get('service_card_temp')) {
                $arrServiceCardTemp = session()->get('service_card_temp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrServiceCardTemp, $arrServiceCard);
            //Unset phần tử
            unset($arrResult[$data['object_code']]);
            //Lưu session temp mới
            session()->forget('service_card_temp');
            session()->put('service_card_temp', $arrResult);
            //Get session remove temp
            $arrRemoveServiceCardTemp = [];
            if (session()->get('remove_service_card')) {
                $arrRemoveServiceCardTemp = session()->get('remove_service_card');
            }
            //Lưu session remove temp
            $arrRemoveServiceCardTemp [] = $data['object_code'];
            session()->forget('remove_service_card');
            session()->put('remove_service_card', $arrRemoveServiceCardTemp);
        }
    }

    /**
     * Submit chọn sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed|void
     */
    public function submitChoose($data)
    {
        $arrRemove = [];
        //Get session all
        $arrListAll = [];
        if (session()->get('list_all')) {
            $arrListAll = session()->get('list_all');
        }
        //Get session product
        $arrProduct = [];
        if (session()->get('product')) {
            $arrProduct = session()->get('product');
        }
        //Get session product term
        $arrProductTemp = [];
        if (session()->get('product_temp')) {
            $arrProductTemp = session()->get('product_temp');
        }
        //Merge product + product term
        $arrProductMerge = array_merge($arrProduct, $arrProductTemp);
        //Get session remove product
        $arrRemoveProduct = [];
        if (session()->get('remove_product')) {
            $arrRemoveProduct = session()->get('remove_product');
        }
        if (count($arrRemoveProduct) > 0) {
            foreach ($arrRemoveProduct as $v) {
                unset($arrProductMerge[$v]);
                $arrRemove [] = $v;
            }
        }
        //Lưu session product
        session()->forget('product');
        session()->put('product', $arrProductMerge);
        //Forget product term
        session()->forget('product_temp');
        //Get session service
        $arrService = [];
        if (session()->get('service')) {
            $arrService = session()->get('service');
        }
        //Get session service term
        $arrServiceTemp = [];
        if (session()->get('service_temp')) {
            $arrServiceTemp = session()->get('service_temp');
        }
        //Merge service + service term
        $arrServiceMerge = array_merge($arrService, $arrServiceTemp);
        //Get session remove service
        $arrRemoveService = [];
        if (session()->get('remove_service')) {
            $arrRemoveService = session()->get('remove_service');
        }
        if (count($arrRemoveService) > 0) {
            foreach ($arrRemoveService as $v) {
                unset($arrServiceMerge[$v]);
                $arrRemove [] = $v;
            }
        }
        //Lưu session service
        session()->forget('service');
        session()->put('service', $arrServiceMerge);
        //Forget service term
        session()->forget('service_temp');
        //Get session service card
        $arrServiceCard = [];
        if (session()->get('service_card')) {
            $arrServiceCard = session()->get('service_card');
        }
        //Get session service card term
        $arrServiceCardTemp = [];
        if (session()->get('service_card_temp')) {
            $arrServiceCardTemp = session()->get('service_card_temp');
        }
        //Merge service card + service card term
        $arrServiceCardMerge = array_merge($arrServiceCard, $arrServiceCardTemp);
        //Get session remove service card
        $arrRemoveServiceCard = [];
        if (session()->get('remove_service_card')) {
            $arrRemoveServiceCard = session()->get('remove_service_card');
        }
        if (count($arrRemoveServiceCard) > 0) {
            foreach ($arrRemoveServiceCard as $v) {
                unset($arrServiceCardMerge[$v]);
                $arrRemove [] = $v;
            }
        }
        //Lưu session service card
        session()->forget('service_card');
        session()->put('service_card', $arrServiceCardMerge);
        //Forget service card term
        session()->forget('service_card_temp');

        //Merge product + service + service card
        $arrMergeSvProSvCard = array_merge($arrProductMerge, $arrServiceMerge, $arrServiceCardMerge);
        //Merge all + all new
        $arrResult = array_merge($arrMergeSvProSvCard, $arrListAll);
        //Remove những param đã xóa trên arr tổng
        if (count($arrRemove) > 0) {
            foreach ($arrRemove as $v) {
                unset($arrResult[$v]);
            }
        }
        //Forget session remove
        session()->forget('remove_product');
        session()->forget('remove_service');
        session()->forget('remove_service_card');
        //Lưu session all
        session()->forget('list_all');
        session()->put('list_all', $arrResult);

        if ($data['promotion_type'] == 1) {
            $listResult = $this->listParam([
                'page' => 1,
                'discount_type' => $data['discount_type'],
                'discount_value_percent' => $data['discount_value_percent'],
                'discount_value_same' => $data['discount_value_same']
            ]);

            $html = \View::make('promotion::promotion.list-product-discount', [
                'list' => $listResult,
                'discount_type' => $data['discount_type']
            ])->render();

            return [
                'html' => $html,
            ];
        } else if ($data['promotion_type'] == 2) {
            $listResult = $this->listParam([
                'page' => 1
            ]);

            $html = \View::make('promotion::promotion.list-product-gift', [
                'list' => $listResult
            ])->render();

            return [
                'html' => $html,
            ];
        }
    }

    /**
     * Danh sách sp, dv, thẻ dv
     *
     * @param array $filter
     * @return LengthAwarePaginator
     */
    public function listParam($filter = [])
    {
        $arrResult = [];
        if (session()->get('list_all')) {
            $arrResult = session()->get('list_all');
        }

        $arrNew = [];

        //Format lại promotion giá
        if (isset($filter['discount_type']) && $filter['discount_type'] == 'same') {
            foreach ($arrResult as $v) {
                $v['promotion_price'] = $filter['discount_value_same'];

                $arrNew [$v['object_code']] = $v;
            }
        } else if (isset($filter['discount_type']) && $filter['discount_type'] == 'percent') {
            foreach ($arrResult as $v) {
                $v['promotion_price'] = $v['base_price'] / 100 * (100 - $filter['discount_value_percent']);

                $arrNew [$v['object_code']] = $v;
            }
        } else {
            $arrNew = $arrResult;
        }

        if (count($arrNew) > 0) {
            $mService = new \Modules\Promotion\Models\ServiceTable();
            $mProduct = new \Modules\Promotion\Models\ProductChildTable();
            $mServiceCard = new \Modules\Promotion\Models\ServiceCardTable();

            foreach ($arrNew as $v) {
                switch ($v['object_type']) {
                    case 'product':
                        //Lấy thống tin sản phẩm
                        $getProduct = $mProduct->getProduct($v['object_code']);
                        if($getProduct != null){
                            $v['object_name'] = $getProduct['product_child_name'];
                        }
                        break;
                    case 'service':
                        //Lấy thông tin dịch vụ
                        $getService = $mService->getService($v['object_code']);
                        $v['object_name'] = $getService['service_name'];
                        break;
                    case 'service_card':
                        //Lấy thông tin thẻ dịch vụ
                        $getServiceCard = $mServiceCard->getServiceCard($v['object_code']);
                        $v['object_name'] = $getServiceCard['name'];
                        break;
                }
            }
        }

        //Lưu session all
        session()->forget('list_all');
        session()->put('list_all', $arrNew);

        //Phân trang
        $page = 1;

        if (isset($filter['page'])) {
            $page = $filter['page'];
        }

        // Get current page form url e.x. &page=1
        $currentPage = intval($page);

        // Create a new Laravel collection from the array data
        $itemCollection = collect($arrNew);

        // Tổng item trên 1 trang
        $perPage = 10;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath(url()->current());

        return $paginatedItems;
    }

    /**
     * Phân trang ds discount sp, dv, thẻ dv
     *
     * @param array $filter
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDiscount($filter = [])
    {
        $listResult = $this->listParam([
            'page' => $filter['page'],
            'discount_type' => $filter['discount_type'],
            'discount_value_percent' => $filter['discount_value_percent'],
            'discount_value_same' => $filter['discount_value_same']
        ]);

        return view('promotion::promotion.list-product-discount', [
            'list' => $listResult,
            'page' => $filter['page'],
            'discount_type' => $filter['discount_type']
        ]);
    }

    /**
     * Thay đổi giá khuyến mãi
     *
     * @param $data
     * @return mixed|void
     */
    public function changePrice($data)
    {
        //Get session all
        $arrListAll = [];
        if (session()->get('list_all')) {
            $arrListAll = session()->get('list_all');
        }

        if (isset($arrListAll[$data['object_code']])) {
            $arrListAll[$data['object_code']]['promotion_price'] = str_replace(',', '', $data['promotion_price']);
        }

        session()->forget('list_all');
        session()->put('list_all', $arrListAll);
    }

    /**
     * Xóa dòng table sp, dv, thẻ db
     *
     * @param $data
     * @return mixed|void
     */
    public function removeTr($data)
    {
        //Get session all
        $arrListAll = [];
        if (session()->get('list_all')) {
            $arrListAll = session()->get('list_all');
        }

        unset($arrListAll[$data['object_code']]);

        session()->forget('list_all');
        session()->put('list_all', $arrListAll);

        if ($data['object_type'] == 'product') {
            //Get session product
            $arrProduct = [];
            if (session()->get('product')) {
                $arrProduct = session()->get('product');
            }

            unset($arrProduct[$data['object_code']]);

            session()->forget('product');
            session()->put('product', $arrProduct);
        } else if ($data['object_type'] == 'service') {
            //Get session service
            $arrService = [];
            if (session()->get('service')) {
                $arrService = session()->get('service');
            }

            unset($arrService[$data['object_code']]);

            session()->forget('service');
            session()->put('service', $arrService);
        } else if ($data['object_type'] == 'service_card') {
            //Get session service
            $arrServiceCard = [];
            if (session()->get('service_card')) {
                $arrServiceCard = session()->get('service_card');
            }

            unset($arrServiceCard[$data['object_code']]);

            session()->forget('service_card');
            session()->put('service_card', $arrServiceCard);
        }
    }

    /**
     * Thay đổi trạng thái table sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed|void
     */
    public function changeStatusTr($data)
    {
        //Get session all
        $arrListAll = [];
        if (session()->get('list_all')) {
            $arrListAll = session()->get('list_all');
        }

        if (isset($arrListAll[$data['object_code']])) {
            $arrListAll[$data['object_code']]['is_actived'] = $data['is_actived'];
        }

        session()->forget('list_all');
        session()->put('list_all', $arrListAll);
    }

    /**
     * Option sp, dv, thẻ dv
     *
     * @param array $filter
     * @return array|mixed
     */
    public function listOption($filter = [])
    {
        $mProduct = new ProductChildTable();
        $mService = new ServiceTable();
        $mServiceCard = new ServiceCard();

        if ($filter['type'] == 'product' || $filter['type'] == 'product_gift') {
            $filter['search_keyword'] = isset($filter['search']) ? $filter['search'] : '';

            unset($filter['search'], $filter['type']);

            $data = $mProduct->getListChildOrderPaginate($filter);

            return [
                'items' => $data->items(),
                'pagination' => range($data->currentPage(),
                    $data->lastPage()) ? true : false
            ];
        } else if ($filter['type'] == 'service' || $filter['type'] == 'service_gift') {
            unset($filter['type']);

            $data = $mService->getList($filter);

            return [
                'items' => $data->items(),
                'pagination' => range($data->currentPage(),
                    $data->lastPage()) ? true : false
            ];
        } else if ($filter['type'] == 'service_card' || $filter['type'] == 'service_card_gift') {
            $filter['search_keyword'] = isset($filter['search']) ? $filter['search'] : '';

            unset($filter['search'], $filter['type']);

            $data = $mServiceCard->getList($filter);

            return [
                'items' => $data->items(),
                'pagination' => range($data->currentPage(),
                    $data->lastPage()) ? true : false
            ];
        }
    }

    /**
     * Thay đổi loại quà tặng
     *
     * @param $data
     * @return mixed|void
     */
    public function changeGiftType($data)
    {
        //Get session all
        $arrListAll = [];
        if (session()->get('list_all')) {
            $arrListAll = session()->get('list_all');
        }

        if (isset($arrListAll[$data['object_code']])) {
            $arrListAll[$data['object_code']]['gift_object_type'] = $data['gift_object_type'];
            $arrListAll[$data['object_code']]['gift_object_name'] = null;
            $arrListAll[$data['object_code']]['gift_object_id'] = null;
            $arrListAll[$data['object_code']]['gift_object_code'] = null;
        }

        session()->forget('list_all');
        session()->put('list_all', $arrListAll);
    }

    /**
     * Phân trang ds gift sp, dv, thẻ dv
     *
     * @param array $filter
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listGift($filter = [])
    {
        $listResult = $this->listParam([
            'page' => $filter['page'],
        ]);

        return view('promotion::promotion.list-product-gift', [
            'list' => $listResult,
            'page' => $filter['page']
        ]);
    }

    /**
     * Thay đổi quà tặng
     *
     * @param $data
     * @return mixed|void
     */
    public function changeGift($data)
    {
        $mProduct = new ProductChildTable();
        $mService = new ServiceTable();
        $mServiceCard = new ServiceCard();

        $gift_object_id = null;
        $gift_object_code = null;
        $gift_object_name = null;

        if ($data['gift_object_type'] == "product") {
            $getItem = $mProduct->getItem($data['gift_object_id']);

            if ($getItem != null) {
                $gift_object_name = $getItem['product_child_name'];
                $gift_object_id = $getItem['product_child_id'];
                $gift_object_code = $getItem['product_code'];
            }
        } else if ($data['gift_object_type'] == "service") {
            $getItem = $mService->getItem($data['gift_object_id']);

            if ($getItem != null) {
                $gift_object_name = $getItem['service_name'];
                $gift_object_id = $getItem['service_id'];
                $gift_object_code = $getItem['service_code'];
            }
        } else if ($data['gift_object_type'] == "service_card") {
            $getItem = $mServiceCard->getItemDetail($data['gift_object_id']);

            if ($getItem != null) {
                $gift_object_name = $getItem['name'];
                $gift_object_id = $getItem['service_card_id'];
                $gift_object_code = $getItem['code'];
            }
        }

        if ($gift_object_id != null && $gift_object_code != null && $gift_object_name != null) {
            //Get session all
            $arrListAll = [];
            if (session()->get('list_all')) {
                $arrListAll = session()->get('list_all');
            }

            if (isset($arrListAll[$data['object_code']])) {
                $arrListAll[$data['object_code']]['gift_object_name'] = $gift_object_name;
                $arrListAll[$data['object_code']]['gift_object_id'] = $gift_object_id;
                $arrListAll[$data['object_code']]['gift_object_code'] = $gift_object_code;
            }

            session()->forget('list_all');
            session()->put('list_all', $arrListAll);
        }
    }

    /**
     * Thay đổi số lượng cần mua
     *
     * @param $data
     * @return mixed|void
     */
    public function changeQuantityBuy($data)
    {
        //Get session all
        $arrListAll = [];
        if (session()->get('list_all')) {
            $arrListAll = session()->get('list_all');
        }

        if (isset($arrListAll[$data['object_code']])) {
            $arrListAll[$data['object_code']]['quantity_buy'] = $data['quantity_buy'];
        }

        session()->forget('list_all');
        session()->put('list_all', $arrListAll);
    }

    /**
     * Thay đổi số lượng quà tặng
     *
     * @param $data
     * @return mixed|void
     */
    public function changeNumberGift($data)
    {
        //Get session all
        $arrListAll = [];
        if (session()->get('list_all')) {
            $arrListAll = session()->get('list_all');
        }

        if (isset($arrListAll[$data['object_code']])) {
            $arrListAll[$data['object_code']]['quantity_gift'] = $data['quantity_gift'];
        }

        session()->forget('list_all');
        session()->put('list_all', $arrListAll);
    }

    /**
     * Submit thêm CTKM
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mPromotionDetail = new PromotionDetailTable();
            $mDailyTime = new PromotionDailyTimeTable();
            $mMonthlyTime = new PromotionMonthlyTimeTable();
            $mWeeklyTime = new PromotionWeeklyTimeTable();
            $mDateTime = new PromotionDateTimeTable();
            $mObjectApply = new PromotionObjectApplyTable();

            //Get session all
            $arrListAll = [];
            if (session()->get('list_all')) {
                $arrListAll = session()->get('list_all');
            }

            if (count($arrListAll) == 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Hãy chọn sản phẩm, dịch vụ, thẻ dịch vụ'),
                ]);
            }

            //Check start time > end time
            if (Carbon::createFromFormat('d/m/Y H:i', $input['start_date'])->format('Y-m-d H:i')
                >= Carbon::createFromFormat('d/m/Y H:i', $input['end_date'])->format('Y-m-d H:i')) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày bắt đầu chương trình phải lớn hơn ngày kết thúc chương trình'),
                ]);
            }

            $promotionDiscountValue = null;

            if ($input['promotion_type'] == 1 && $input['promotion_type_discount'] == "percent") {
                $promotionDiscountValue = str_replace(',', '', $input['promotion_type_discount_percent']);
            } else if ($input['promotion_type'] == 1 && $input['promotion_type_discount'] == "same") {
                $promotionDiscountValue = str_replace(',', '', $input['promotion_type_discount_same']);
            }

            $positionFeature = null;

            if ($input['is_feature'] == 1 && isset($input['positionId'])) {
                foreach ($input['positionId'] as $k => $v) {
                    if ($v != 'current' && $v != null) {
                        //Cập nhật lại vị trí của các promotion khác
                        $this->promotion->edit([
                            'position_feature' => $k + 1
                        ], $v);
                    } else if ($v == 'current') {
                        $positionFeature = $k + 1;
                    }
                }
            }

            //Insert promotion master
            $dataMaster = [
                "promotion_name" => $input['promotion_name'],
                "start_date" => Carbon::createFromFormat('d/m/Y H:i', $input['start_date'])->format('Y-m-d H:i'),
                "end_date" => Carbon::createFromFormat('d/m/Y H:i', $input['end_date'])->format('Y-m-d H:i'),
                "is_display" => $input['is_display'],
                "is_time_campaign" => $input['is_time_campaign'],
                "time_type" => isset($input['time_type']) ? $input['time_type'] : null,
                "image" => $input['image'],
                "branch_apply" => $input['branch_apply'] != "all" ? implode(",", $input['branch_apply']) : $input['branch_apply'],
                "is_feature" => $input['is_feature'],
                "position_feature" => $positionFeature,
                "promotion_type" => $input['promotion_type'],
                "promotion_type_discount" => $input['promotion_type'] == 1 ? $input['promotion_type_discount'] : null,
                "promotion_type_discount_value" => $promotionDiscountValue,
                "order_source" => $input['order_source'],
                "quota" => str_replace(',', '', $input['quota']),
                "promotion_apply_to" => $input['promotion_apply_to'],
                "description" => $input['description'],
                "description_detail" => $input['description_detail'],
                "type_display_app" => $input['type_display_app'],
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ];
            $promotionId = $this->promotion->add($dataMaster);
            //Update promotion code
            $promotionCode = 'PROMOTION_' . date('dmY') . sprintf("%02d", $promotionId);
            $this->promotion->edit([
                'promotion_code' => $promotionCode
            ], $promotionId);

            if ($input['is_time_campaign'] == 1) {
                switch ($input['time_type']) {
                    case 'D':
                        //Insert promotion daily
                        $mDailyTime->add([
                            'promotion_id' => $promotionId,
                            'promotion_code' => $promotionCode,
                            'start_time' => isset($input['arrDataDaily']['start_time']) ? $input['arrDataDaily']['start_time'] : null,
                            'end_time' => isset($input['arrDataDaily']['end_time']) ? $input['arrDataDaily']['end_time'] : null,
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id()
                        ]);
                        break;
                    case 'W':
                        //Insert promotion weekly
                        $mWeeklyTime->add([
                            'promotion_id' => $promotionId,
                            'promotion_code' => $promotionCode,
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id(),
                            'default_start_time' => isset($input['arrDataWeekly']['default_start_time']) ? $input['arrDataWeekly']['default_start_time'] : null,
                            'default_end_time' => isset($input['arrDataWeekly']['default_end_time']) ? $input['arrDataWeekly']['default_end_time'] : null,
                            'is_monday' => isset($input['arrDataWeekly']['is_monday']) ? $input['arrDataWeekly']['is_monday'] : null,
                            'is_other_monday' => isset($input['arrDataWeekly']['is_other_monday']) ? $input['arrDataWeekly']['is_other_monday'] : null,
                            'is_other_monday_start_time' => isset($input['arrDataWeekly']['is_other_monday_start_time']) ? $input['arrDataWeekly']['is_other_monday_start_time'] : null,
                            'is_other_monday_end_time' => isset($input['arrDataWeekly']['is_other_monday_end_time']) ? $input['arrDataWeekly']['is_other_monday_end_time'] : null,
                            'is_tuesday' => isset($input['arrDataWeekly']['is_tuesday']) ? $input['arrDataWeekly']['is_tuesday'] : null,
                            'is_other_tuesday' => isset($input['arrDataWeekly']['is_other_tuesday']) ? $input['arrDataWeekly']['is_other_tuesday'] : null,
                            'is_other_tuesday_start_time' => isset($input['arrDataWeekly']['is_other_tuesday_start_time']) ? $input['arrDataWeekly']['is_other_tuesday_start_time'] : null,
                            'is_other_tuesday_end_time' => isset($input['arrDataWeekly']['is_other_tuesday_end_time']) ? $input['arrDataWeekly']['is_other_tuesday_end_time'] : null,
                            'is_wednesday' => isset($input['arrDataWeekly']['is_wednesday']) ? $input['arrDataWeekly']['is_wednesday'] : null,
                            'is_other_wednesday' => isset($input['arrDataWeekly']['is_other_wednesday']) ? $input['arrDataWeekly']['is_other_wednesday'] : null,
                            'is_other_wednesday_start_time' => isset($input['arrDataWeekly']['is_other_wednesday_start_time']) ? $input['arrDataWeekly']['is_other_wednesday_start_time'] : null,
                            'is_other_wednesday_end_time' => isset($input['arrDataWeekly']['is_other_wednesday_end_time']) ? $input['arrDataWeekly']['is_other_wednesday_end_time'] : null,
                            'is_thursday' => isset($input['arrDataWeekly']['is_thursday']) ? $input['arrDataWeekly']['is_thursday'] : null,
                            'is_other_thursday' => isset($input['arrDataWeekly']['is_other_thursday']) ? $input['arrDataWeekly']['is_other_thursday'] : null,
                            'is_other_thursday_start_time' => isset($input['arrDataWeekly']['is_other_thursday_start_time']) ? $input['arrDataWeekly']['is_other_thursday_start_time'] : null,
                            'is_other_thursday_end_time' => isset($input['arrDataWeekly']['is_other_thursday_end_time']) ? $input['arrDataWeekly']['is_other_thursday_end_time'] : null,
                            'is_friday' => isset($input['arrDataWeekly']['is_friday']) ? $input['arrDataWeekly']['is_friday'] : null,
                            'is_other_friday' => isset($input['arrDataWeekly']['is_other_friday']) ? $input['arrDataWeekly']['is_other_friday'] : null,
                            'is_other_friday_start_time' => isset($input['arrDataWeekly']['is_other_friday_start_time']) ? $input['arrDataWeekly']['is_other_friday_start_time'] : null,
                            'is_other_friday_end_time' => isset($input['arrDataWeekly']['is_other_friday_end_time']) ? $input['arrDataWeekly']['is_other_friday_end_time'] : null,
                            'is_saturday' => isset($input['arrDataWeekly']['is_saturday']) ? $input['arrDataWeekly']['is_saturday'] : null,
                            'is_other_saturday' => isset($input['arrDataWeekly']['is_other_saturday']) ? $input['arrDataWeekly']['is_other_saturday'] : null,
                            'is_other_saturday_end_time' => isset($input['arrDataWeekly']['is_other_saturday_end_time']) ? $input['arrDataWeekly']['is_other_saturday_end_time'] : null,
                            'is_sunday' => isset($input['arrDataWeekly']['is_sunday']) ? $input['arrDataWeekly']['is_sunday'] : null,
                            'is_other_sunday' => isset($input['arrDataWeekly']['is_other_sunday']) ? $input['arrDataWeekly']['is_other_sunday'] : null,
                            'is_other_sunday_start_time' => isset($input['arrDataWeekly']['is_other_sunday_start_time']) ? $input['arrDataWeekly']['is_other_sunday_start_time'] : null,
                            'is_other_sunday_end_time' => isset($input['arrDataWeekly']['is_other_sunday_end_time']) ? $input['arrDataWeekly']['is_other_sunday_end_time'] : null
                        ]);
                        break;
                    case 'M':
                        if (!isset($input['arrDataMonthly']) || count($input['arrDataMonthly']) == 0) {
                            return response()->json([
                                'error' => true,
                                'message' => __('Hãy chọn thời gian khuyến mãi hàng tháng'),
                            ]);
                        }
                        $dataMonthly = [];

                        foreach ($input['arrDataMonthly'] as $v) {
                            $dataMonthly [] = [
                                'promotion_id' => $promotionId,
                                'promotion_code' => $promotionCode,
                                "created_by" => Auth()->id(),
                                "updated_by" => Auth()->id(),
                                'run_date' => Carbon::createFromFormat('d/m/Y', $v['run_date'])->format('Y-m-d'),
                                'start_time' => $v['start_time'],
                                'end_time' => $v['end_time'],
                                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                            ];
                        }
                        //Insert promotion monthly
                        $mMonthlyTime->insert($dataMonthly);
                        break;
                    case 'R':
                        //Insert promotion date time
                        $mDateTime->add([
                            'promotion_id' => $promotionId,
                            'promotion_code' => $promotionCode,
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id(),
                            'form_date' =>
                                isset($input['arrDataFromTo']['form_date']) ? Carbon::createFromFormat('d/m/Y', $input['arrDataFromTo']['form_date'])->format('Y-m-d') : null,
                            'to_date' =>
                                isset($input['arrDataFromTo']['to_date']) ? Carbon::createFromFormat('d/m/Y', $input['arrDataFromTo']['to_date'])->format('Y-m-d') : null,
                            'start_time' => isset($input['arrDataFromTo']['start_time']) ? $input['arrDataFromTo']['start_time'] : null,
                            'end_time' => isset($input['arrDataFromTo']['end_time']) ? $input['arrDataFromTo']['end_time'] : null
                        ]);
                        break;
                }
            }

            $dataObjectApply = [];
            if ($input['promotion_apply_to'] == 2) {
                if (count($input['member_level_id']) > 0) {
                    foreach ($input['member_level_id'] as $v) {
                        $dataObjectApply [] = [
                            'promotion_id' => $promotionId,
                            'promotion_code' => $promotionCode,
                            'object_type' => 1,
                            'object_id' => $v,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }
            } else if ($input['promotion_apply_to'] == 3) {
                if (count($input['customer_group_id']) > 0) {
                    foreach ($input['customer_group_id'] as $v) {
                        $dataObjectApply [] = [
                            'promotion_id' => $promotionId,
                            'promotion_code' => $promotionCode,
                            'object_type' => 2,
                            'object_id' => $v,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }
            } else if ($input['promotion_apply_to'] == 4) {
                if (count($input['customer_id']) > 0) {
                    foreach ($input['customer_id'] as $v) {
                        $dataObjectApply [] = [
                            'promotion_id' => $promotionId,
                            'promotion_code' => $promotionCode,
                            'object_type' => 3,
                            'object_id' => $v,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }
            }
            //Insert promotion object apply
            $mObjectApply->insert($dataObjectApply);

            $arrDataDetail = [];
            $errorDetail = '';
            foreach ($arrListAll as $v) {
//                $check = $mPromotionDetail->checkDetailUsing(
//                    $input['promotion_type'],
//                    $promotionCode,
//                    $input['start_date'],
//                    $input['end_date'],
//                    $v['object_type'],
//                    $v['object_code']
//                );
//
//                if ($check != null) {
//                    $errorDetail .=  $v['object_name'].' '. __('đã tồn tại ở chương trình khác') . '<br>';
//                }

                if ($input['promotion_type'] == 1 && str_replace(',', '', $v['promotion_price']) < 0) {
                    $errorDetail .= $v['object_name'] . ' ' . __('tiền khuyến mãi không hợp lệ') . '<br>';
                }

                $arrDataDetail [] = [
                    'promotion_id' => $promotionId,
                    'promotion_code' => $promotionCode,
                    'object_type' => $v['object_type'],
                    'object_id' => $v['object_id'],
                    'object_code' => $v['object_code'],
                    'object_name' => $v['object_name'],
                    'base_price' => $input['promotion_type'] == 1 ? str_replace(',', '', $v['base_price']) : null,
                    'promotion_price' => $input['promotion_type'] == 1 ? str_replace(',', '', $v['promotion_price']) : null,
                    'quantity_buy' => $input['promotion_type'] == 2 ? $v['quantity_buy'] : null,
                    'quantity_gift' => $input['promotion_type'] == 2 ? $v['quantity_gift'] : null,
                    'gift_object_type' => $input['promotion_type'] == 2 ? $v['gift_object_type'] : null,
                    'gift_object_id' => $input['promotion_type'] == 2 ? $v['gift_object_id'] : null,
                    'gift_object_code' => $input['promotion_type'] == 2 ? $v['gift_object_code'] : null,
                    'gift_object_name' => $input['promotion_type'] == 2 ? $v['gift_object_name'] : null,
                    'is_actived' => $v['is_actived'],
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];
            }

            if ($errorDetail != '') {
                return response()->json([
                    'error' => true,
                    'message' => $errorDetail,
                ]);
            }

            //Insert promotion detail
            $mPromotionDetail->insert($arrDataDetail);

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Tạo thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Tạo thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Move ảnh từ folder temp sang folder chính
     *
     * @param $filename
     * @param $PATH
     * @return mixed|string
     */
    public function moveImage($filename, $PATH)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = $PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory($PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    /**
     * Data view edit
     *
     * @param $promotionId
     * @return array|mixed
     */
    public function dataEdit($promotionId)
    {
        $mPromotionDetail = new PromotionDetailTable();
        $mDailyTime = new PromotionDailyTimeTable();
        $mMonthlyTime = new PromotionMonthlyTimeTable();
        $mWeeklyTime = new PromotionWeeklyTimeTable();
        $mDateTime = new PromotionDateTimeTable();
        $mObjectApply = new PromotionObjectApplyTable();
        $mBranch = new BranchTable();
        $mMemberLevel = new MemberLevelTable();
        $mCustomerGroup = new CustomerGroupTable();
        $mCustomer = new CustomerTable();

        //Data edit
        $promotionMaster = $this->promotion->getInfo($promotionId);
        $promotionDetail = $mPromotionDetail->getDetail($promotionMaster['promotion_code']);
        $daily = $mDailyTime->getDailyByPromotion($promotionMaster['promotion_code']);
        $monthly = $mMonthlyTime->getMonthlyByPromotion($promotionMaster['promotion_code']);
        $weekly = $mWeeklyTime->getWeeklyByPromotion($promotionMaster['promotion_code']);
        $dateTime = $mDateTime->getDateTimeByPromotion($promotionMaster['promotion_code']);
        $objectApply = $mObjectApply->getObjectApplyByPromotion($promotionMaster['promotion_code']);

        $arrObjectApply = [];
        if (count($objectApply) > 0) {
            foreach ($objectApply as $v) {
                $arrObjectApply [] = $v['object_id'];
            }
        }

        $arrDetail = [];
        $arrService = [];
        $arrProduct = [];
        $arrServiceCard = [];

        session()->forget('product');
        session()->forget('service');
        session()->forget('service_card');
        session()->forget('list_all');

        session()->forget('product_temp');
        session()->forget('service_temp');
        session()->forget('service_card_temp');
        session()->forget('remove_product');
        session()->forget('remove_service');
        session()->forget('remove_service_card');

        if (count($promotionDetail) > 0) {
            foreach ($promotionDetail as $v) {
                $data = [
                    'object_type' => $v['object_type'],
                    'object_id' => $v['object_id'],
                    'object_code' => $v['object_code'],
                    'object_name' => $v['object_name'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'quantity_buy' => $v['quantity_buy'],
                    'quantity_gift' => $v['quantity_gift'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'gift_object_name' => $v['gift_object_name'],
                    'is_actived' => $v['is_actived']
                ];

                $arrDetail[$v['object_code']] = $data;

                if ($v['object_type'] == 'product') {
                    $arrProduct[$v['object_code']] = $data;
                } else if ($v['object_type'] == 'service') {
                    $arrService[$v['object_code']] = $data;
                } else if ($v['object_type'] == 'service_card') {
                    $arrServiceCard[$v['object_code']] = $data;
                }
            }
        }

        session()->put('product', $arrProduct);
        session()->put('service', $arrService);
        session()->put('service_card', $arrServiceCard);
        session()->put('list_all', $arrDetail);

        //Danh sách param
        $listResult = $this->listParam([
            'page' => 1,
            'discount_type' => $promotionMaster['promotion_type_discount'],
            'discount_value_percent' => $promotionMaster['promotion_type_discount_value'],
            'discount_value_same' => $promotionMaster['promotion_type_discount_value']
        ]);


        //Get các option
        $branch = $mBranch->getBranchOption();
        $memberLevel = $mMemberLevel->getOptionMemberLevel();
        $customerGroup = $mCustomerGroup->getOption();
        $customer = $mCustomer->getCustomerOption();

        //Vị trí hiển thị nổi bật
        $position1 = $this->promotion->getPosition(1);
        $position2 = $this->promotion->getPosition(2);
        $position3 = $this->promotion->getPosition(3);
        $position4 = $this->promotion->getPosition(4);
        $position5 = $this->promotion->getPosition(5);

        return [
            'item' => $promotionMaster,
            'branch' => $branch,
            'memberLevel' => $memberLevel,
            'customerGroup' => $customerGroup,
            'customer' => $customer,
            'position1' => $position1,
            'position2' => $position2,
            'position3' => $position3,
            'position4' => $position4,
            'position5' => $position5,
            'daily' => $daily,
            'weekly' => $weekly,
            'monthly' => $monthly,
            'dateTime' => $dateTime,
            'arrObjectApply' => $arrObjectApply,
            'listResult' => $listResult
        ];
    }

    /**
     * Submit chỉnh sửa CTKM
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mPromotionDetail = new PromotionDetailTable();
            $mDailyTime = new PromotionDailyTimeTable();
            $mMonthlyTime = new PromotionMonthlyTimeTable();
            $mWeeklyTime = new PromotionWeeklyTimeTable();
            $mDateTime = new PromotionDateTimeTable();
            $mObjectApply = new PromotionObjectApplyTable();

            //Get session all
            $arrListAll = [];
            if (session()->get('list_all')) {
                $arrListAll = session()->get('list_all');
            }

            if (count($arrListAll) == 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Hãy chọn sản phẩm, dịch vụ, thẻ dịch vụ'),
                ]);
            }


            //Check start time > end time
            if (Carbon::createFromFormat('d/m/Y H:i', $input['start_date'])->format('Y-m-d H:i')
                >= Carbon::createFromFormat('d/m/Y H:i', $input['end_date'])->format('Y-m-d H:i')) {
                return response()->json([
                    'error' => true,
                    'message' => __('Ngày bắt đầu chương trình phải lớn hơn ngày kết thúc chương trình'),
                ]);
            }

            $promotionDiscountValue = null;

            if ($input['promotion_type'] == 1 && $input['promotion_type_discount'] == "percent") {
                $promotionDiscountValue = str_replace(',', '', $input['promotion_type_discount_percent']);
            } else if ($input['promotion_type'] == 1 && $input['promotion_type_discount'] == "same") {
                $promotionDiscountValue = str_replace(',', '', $input['promotion_type_discount_same']);
            }

            $positionFeature = null;

            if ($input['is_feature'] == 1 && isset($input['positionId'])) {
                foreach ($input['positionId'] as $k => $v) {
                    if ($v != 'current' && $v != null) {
                        //Cập nhật lại vị trí của các promotion khác
                        $this->promotion->edit([
                            'position_feature' => $k + 1
                        ], $v);
                    } else if ($v == 'current') {
                        $positionFeature = $k + 1;
                    }
                }
            }

            $promotionId = $input['promotion_id'];

            $dataMaster = [
                "promotion_name" => $input['promotion_name'],
                "start_date" => Carbon::createFromFormat('d/m/Y H:i', $input['start_date'])->format('Y-m-d H:i'),
                "end_date" => Carbon::createFromFormat('d/m/Y H:i', $input['end_date'])->format('Y-m-d H:i'),
                "is_display" => $input['is_display'],
                "is_time_campaign" => $input['is_time_campaign'],
                "time_type" => isset($input['time_type']) ? $input['time_type'] : null,
                "branch_apply" => $input['branch_apply'] != "all" ? implode(",", $input['branch_apply']) : $input['branch_apply'],
                "is_feature" => $input['is_feature'],
                "position_feature" => $positionFeature,
                "promotion_type" => $input['promotion_type'],
                "promotion_type_discount" => $input['promotion_type'] == 1 ? $input['promotion_type_discount'] : null,
                "promotion_type_discount_value" => $promotionDiscountValue,
                "order_source" => $input['order_source'],
                "quota" => str_replace(',', '', $input['quota']),
                "promotion_apply_to" => $input['promotion_apply_to'],
                "description" => $input['description'],
                "description_detail" => $input['description_detail'],
                "updated_by" => Auth()->id(),
                "is_actived" => $input['is_actived'],
                "type_display_app" => $input['type_display_app']
            ];

            if ($input['image'] != null) {
                $dataMaster['image'] = $input['image'];
            }

            //update promotion master
            $this->promotion->edit($dataMaster, $input['promotion_id']);

            $mDailyTime->removeDaily($input['promotion_code']);
            $mWeeklyTime->removeWeekly($input['promotion_code']);
            $mMonthlyTime->removeMonthly($input['promotion_code']);
            $mDateTime->removeDateTime($input['promotion_code']);

            if ($input['is_time_campaign'] == 1) {
                switch ($input['time_type']) {
                    case 'D':
                        //Insert promotion daily
                        $mDailyTime->add([
                            'promotion_id' => $promotionId,
                            'promotion_code' => $input['promotion_code'],
                            'start_time' => isset($input['arrDataDaily']['start_time']) ? $input['arrDataDaily']['start_time'] : null,
                            'end_time' => isset($input['arrDataDaily']['end_time']) ? $input['arrDataDaily']['end_time'] : null,
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id()
                        ]);
                        break;
                    case 'W':
                        //Insert promotion weekly
                        $mWeeklyTime->add([
                            'promotion_id' => $promotionId,
                            'promotion_code' => $input['promotion_code'],
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id(),
                            'default_start_time' => isset($input['arrDataWeekly']['default_start_time']) ? $input['arrDataWeekly']['default_start_time'] : null,
                            'default_end_time' => isset($input['arrDataWeekly']['default_end_time']) ? $input['arrDataWeekly']['default_end_time'] : null,
                            'is_monday' => isset($input['arrDataWeekly']['is_monday']) ? $input['arrDataWeekly']['is_monday'] : null,
                            'is_other_monday' => isset($input['arrDataWeekly']['is_other_monday']) ? $input['arrDataWeekly']['is_other_monday'] : null,
                            'is_other_monday_start_time' => isset($input['arrDataWeekly']['is_other_monday_start_time']) ? $input['arrDataWeekly']['is_other_monday_start_time'] : null,
                            'is_other_monday_end_time' => isset($input['arrDataWeekly']['is_other_monday_end_time']) ? $input['arrDataWeekly']['is_other_monday_end_time'] : null,
                            'is_tuesday' => isset($input['arrDataWeekly']['is_tuesday']) ? $input['arrDataWeekly']['is_tuesday'] : null,
                            'is_other_tuesday' => isset($input['arrDataWeekly']['is_other_tuesday']) ? $input['arrDataWeekly']['is_other_tuesday'] : null,
                            'is_other_tuesday_start_time' => isset($input['arrDataWeekly']['is_other_tuesday_start_time']) ? $input['arrDataWeekly']['is_other_tuesday_start_time'] : null,
                            'is_other_tuesday_end_time' => isset($input['arrDataWeekly']['is_other_tuesday_end_time']) ? $input['arrDataWeekly']['is_other_tuesday_end_time'] : null,
                            'is_wednesday' => isset($input['arrDataWeekly']['is_wednesday']) ? $input['arrDataWeekly']['is_wednesday'] : null,
                            'is_other_wednesday' => isset($input['arrDataWeekly']['is_other_wednesday']) ? $input['arrDataWeekly']['is_other_wednesday'] : null,
                            'is_other_wednesday_start_time' => isset($input['arrDataWeekly']['is_other_wednesday_start_time']) ? $input['arrDataWeekly']['is_other_wednesday_start_time'] : null,
                            'is_other_wednesday_end_time' => isset($input['arrDataWeekly']['is_other_wednesday_end_time']) ? $input['arrDataWeekly']['is_other_wednesday_end_time'] : null,
                            'is_thursday' => isset($input['arrDataWeekly']['is_thursday']) ? $input['arrDataWeekly']['is_thursday'] : null,
                            'is_other_thursday' => isset($input['arrDataWeekly']['is_other_thursday']) ? $input['arrDataWeekly']['is_other_thursday'] : null,
                            'is_other_thursday_start_time' => isset($input['arrDataWeekly']['is_other_thursday_start_time']) ? $input['arrDataWeekly']['is_other_thursday_start_time'] : null,
                            'is_other_thursday_end_time' => isset($input['arrDataWeekly']['is_other_thursday_end_time']) ? $input['arrDataWeekly']['is_other_thursday_end_time'] : null,
                            'is_friday' => isset($input['arrDataWeekly']['is_friday']) ? $input['arrDataWeekly']['is_friday'] : null,
                            'is_other_friday' => isset($input['arrDataWeekly']['is_other_friday']) ? $input['arrDataWeekly']['is_other_friday'] : null,
                            'is_other_friday_start_time' => isset($input['arrDataWeekly']['is_other_friday_start_time']) ? $input['arrDataWeekly']['is_other_friday_start_time'] : null,
                            'is_other_friday_end_time' => isset($input['arrDataWeekly']['is_other_friday_end_time']) ? $input['arrDataWeekly']['is_other_friday_end_time'] : null,
                            'is_saturday' => isset($input['arrDataWeekly']['is_saturday']) ? $input['arrDataWeekly']['is_saturday'] : null,
                            'is_other_saturday' => isset($input['arrDataWeekly']['is_other_saturday']) ? $input['arrDataWeekly']['is_other_saturday'] : null,
                            'is_other_saturday_end_time' => isset($input['arrDataWeekly']['is_other_saturday_end_time']) ? $input['arrDataWeekly']['is_other_saturday_end_time'] : null,
                            'is_sunday' => isset($input['arrDataWeekly']['is_sunday']) ? $input['arrDataWeekly']['is_sunday'] : null,
                            'is_other_sunday' => isset($input['arrDataWeekly']['is_other_sunday']) ? $input['arrDataWeekly']['is_other_sunday'] : null,
                            'is_other_sunday_start_time' => isset($input['arrDataWeekly']['is_other_sunday_start_time']) ? $input['arrDataWeekly']['is_other_sunday_start_time'] : null,
                            'is_other_sunday_end_time' => isset($input['arrDataWeekly']['is_other_sunday_end_time']) ? $input['arrDataWeekly']['is_other_sunday_end_time'] : null
                        ]);
                        break;
                    case 'M':
                        if (!isset($input['arrDataMonthly']) || count($input['arrDataMonthly']) == 0) {
                            return response()->json([
                                'error' => true,
                                'message' => __('Hãy chọn thời gian khuyến mãi hàng tháng'),
                            ]);
                        }
                        $dataMonthly = [];
                        foreach ($input['arrDataMonthly'] as $v) {
                            $dataMonthly [] = [
                                'promotion_id' => $promotionId,
                                'promotion_code' => $input['promotion_code'],
                                "created_by" => Auth()->id(),
                                "updated_by" => Auth()->id(),
                                'run_date' => Carbon::createFromFormat('d/m/Y', $v['run_date'])->format('Y-m-d'),
                                'start_time' => $v['start_time'],
                                'end_time' => $v['end_time'],
                                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                            ];
                        }
                        //Insert promotion monthly
                        $mMonthlyTime->insert($dataMonthly);
                        break;
                    case 'R':
                        //Insert promotion date time
                        $mDateTime->add([
                            'promotion_id' => $promotionId,
                            'promotion_code' => $input['promotion_code'],
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id(),
                            'form_date' =>
                                isset($input['arrDataFromTo']['form_date']) ? Carbon::createFromFormat('d/m/Y', $input['arrDataFromTo']['form_date'])->format('Y-m-d') : null,
                            'to_date' =>
                                isset($input['arrDataFromTo']['to_date']) ? Carbon::createFromFormat('d/m/Y', $input['arrDataFromTo']['to_date'])->format('Y-m-d') : null,
                            'start_time' => isset($input['arrDataFromTo']['start_time']) ? $input['arrDataFromTo']['start_time'] : null,
                            'end_time' => isset($input['arrDataFromTo']['end_time']) ? $input['arrDataFromTo']['end_time'] : null
                        ]);
                        break;
                }
            }

            $dataObjectApply = [];
            if ($input['promotion_apply_to'] == 2) {
                if (count($input['member_level_id']) > 0) {
                    foreach ($input['member_level_id'] as $v) {
                        $dataObjectApply [] = [
                            'promotion_id' => $promotionId,
                            'promotion_code' => $input['promotion_code'],
                            'object_type' => 1,
                            'object_id' => $v,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }
            } else if ($input['promotion_apply_to'] == 3) {
                if (count($input['customer_group_id']) > 0) {
                    foreach ($input['customer_group_id'] as $v) {
                        $dataObjectApply [] = [
                            'promotion_id' => $promotionId,
                            'promotion_code' => $input['promotion_code'],
                            'object_type' => 2,
                            'object_id' => $v,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }
            } else if ($input['promotion_apply_to'] == 4) {
                if (count($input['customer_id']) > 0) {
                    foreach ($input['customer_id'] as $v) {
                        $dataObjectApply [] = [
                            'promotion_id' => $promotionId,
                            'promotion_code' => $input['promotion_code'],
                            'object_type' => 3,
                            'object_id' => $v,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }
            }
            $mObjectApply->removeObjectApply($input['promotion_code']);
            //Insert promotion object apply
            $mObjectApply->insert($dataObjectApply);

            $arrDataDetail = [];
            $errorDetail = '';
            foreach ($arrListAll as $v) {
                //Check sp, dv, thẻ dv đã tồn tại ở event khác chưa
//                if ($input['is_actived'] == 1) {
//                    $check = $mPromotionDetail->checkDetailUsing(
//                        $input['promotion_type'],
//                        $input['promotion_code'],
//                        $input['start_date'],
//                        $input['end_date'],
//                        $v['object_type'],
//                        $v['object_code']
//                    );
//
//                    if ($check != null) {
//                        $errorDetail .=  $v['object_name'].' '. __('đã tồn tại ở chương trình khác') . '<br>';
//                    }
//                }

                if ($input['promotion_type'] == 1 && str_replace(',', '', $v['promotion_price']) < 0) {
                    $errorDetail .= $v['object_name'] . ' ' . __('tiền khuyến mãi không hợp lệ') . '<br>';
                }

                $arrDataDetail [] = [
                    'promotion_id' => $promotionId,
                    'promotion_code' => $input['promotion_code'],
                    'object_type' => $v['object_type'],
                    'object_id' => $v['object_id'],
                    'object_code' => $v['object_code'],
                    'object_name' => $v['object_name'],
                    'base_price' => $input['promotion_type'] == 1 ? str_replace(',', '', $v['base_price']) : null,
                    'promotion_price' => $input['promotion_type'] == 1 ? str_replace(',', '', $v['promotion_price']) : null,
                    'quantity_buy' => $input['promotion_type'] == 2 ? $v['quantity_buy'] : null,
                    'quantity_gift' => $input['promotion_type'] == 2 ? $v['quantity_gift'] : null,
                    'gift_object_type' => $input['promotion_type'] == 2 ? $v['gift_object_type'] : null,
                    'gift_object_id' => $input['promotion_type'] == 2 ? $v['gift_object_id'] : null,
                    'gift_object_code' => $input['promotion_type'] == 2 ? $v['gift_object_code'] : null,
                    'gift_object_name' => $input['promotion_type'] == 2 ? $v['gift_object_name'] : null,
                    'is_actived' => $v['is_actived'],
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];
            }
            if ($errorDetail != '') {
                return response()->json([
                    'error' => true,
                    'message' => $errorDetail,
                ]);
            }

            $mPromotionDetail->removeDetail($input['promotion_code']);
            //Insert promotion detail
            $mPromotionDetail->insert($arrDataDetail);
            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xóa CTKM
     *
     * @param $input
     * @return mixed|void
     */
    public function destroy($input)
    {
        try {
            $this->promotion->edit([
                'is_deleted' => 1
            ], $input['promotion_id']);

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
     * Thay đổi trạng thái CTKM
     *
     * @param $input
     * @return array|mixed
     */
    public function changeStatusPromotion($input)
    {
        try {
            $mPromotionDetail = new PromotionDetailTable();

            $this->promotion->edit([
                'is_actived' => $input['status']
            ], $input['promotion_id']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Phân trang ds gift sp, dv, thẻ dv
     *
     * @param array $filter
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDiscountDetail($filter = [])
    {
        $listResult = $this->listParam([
            'page' => $filter['page'],
            'discount_type' => $filter['discount_type'],
            'discount_value_percent' => $filter['discount_value_percent'],
            'discount_value_same' => $filter['discount_value_same']
        ]);

        return view('promotion::promotion.list-discount-detail', [
            'list' => $listResult,
            'page' => $filter['page'],
            'discount_type' => $filter['discount_type']
        ]);
    }

    /**
     * Phân trang ds gift sp, dv, thẻ dv
     *
     * @param array $filter
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listGiftDetail($filter = [])
    {
        $listResult = $this->listParam([
            'page' => $filter['page'],
        ]);

        return view('promotion::promotion.list-gift-detail', [
            'list' => $listResult,
            'page' => $filter['page']
        ]);
    }

    /**
     * Load session all
     *
     * @param $promotionCode
     * @return mixed|void
     */
    public function loadSessionAll($promotionCode)
    {
        $mPromotionDetail = new PromotionDetailTable();

        $arrDetail = [];
        $arrService = [];
        $arrProduct = [];
        $arrServiceCard = [];

        $promotionDetail = $mPromotionDetail->getDetail($promotionCode);

        if (count($promotionDetail) > 0) {
            foreach ($promotionDetail as $v) {
                $mService = new \Modules\Promotion\Models\ServiceTable();
                $mProduct = new \Modules\Promotion\Models\ProductChildTable();
                $mServiceCard = new \Modules\Promotion\Models\ServiceCardTable();

                switch ($v['object_type']) {
                    case 'product':
                        //Lấy thống tin sản phẩm
                        $getProduct = $mProduct->getProduct($v['object_code']);
                        $v['object_name'] = $getProduct['product_child_name'];
                        break;
                    case 'service':
                        //Lấy thông tin dịch vụ
                        $getService = $mService->getService($v['object_code']);
                        $v['object_name'] = $getService['service_name'];
                        break;
                    case 'service_card':
                        //Lấy thông tin thẻ dịch vụ
                        $getServiceCard = $mServiceCard->getServiceCard($v['object_code']);
                        $v['object_name'] = $getServiceCard['name'];
                        break;
                }

                $data = [
                    'object_type' => $v['object_type'],
                    'object_id' => $v['object_id'],
                    'object_code' => $v['object_code'],
                    'object_name' => $v['object_name'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'quantity_buy' => $v['quantity_buy'],
                    'quantity_gift' => $v['quantity_gift'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'gift_object_name' => $v['gift_object_name'],
                    'is_actived' => $v['is_actived']
                ];

                $arrDetail[$v['object_code']] = $data;

                if ($v['object_type'] == 'product') {
                    $arrProduct[$v['object_code']] = $data;
                } else if ($v['object_type'] == 'service') {
                    $arrService[$v['object_code']] = $data;
                } else if ($v['object_type'] == 'service_card') {
                    $arrServiceCard[$v['object_code']] = $data;
                }
            }
        }

        session()->put('product', $arrProduct);
        session()->put('service', $arrService);
        session()->put('service_card', $arrServiceCard);
        session()->put('list_all', $arrDetail);

        return response()->json($arrDetail);
    }
}
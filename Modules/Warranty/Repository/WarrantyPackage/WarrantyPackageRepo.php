<?php

namespace Modules\Warranty\Repository\WarrantyPackage;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Warranty\Models\ProductCategoryTable;
use Modules\Warranty\Models\ProductChildTable;
use Modules\Warranty\Models\ServiceCardGroupTable;
use Modules\Warranty\Models\ServiceCardTable;
use Modules\Warranty\Models\ServiceCategoryTable;
use Modules\Warranty\Models\ServiceTable;
use Modules\Warranty\Models\WarrantyPackageDetailTable;
use Modules\Warranty\Models\WarrantyPackageTable;

class WarrantyPackageRepo implements WarrantyPackageRepoInterface
{
    protected $warrantyPackage;

    public function __construct(WarrantyPackageTable $warrantyPackage)
    {
        $this->warrantyPackage = $warrantyPackage;
    }

    public function list(array $filters = [])
    {
        $list = $this->warrantyPackage->getList($filters);

        return [
            "list" => $list,
        ];
    }

    /**
     * Data cho view thêm mới
     *
     * @return bool|mixed
     */
    public function dataViewCreate()
    {
        session()->forget('product_tmp');
        session()->forget('service_tmp');
        session()->forget('service_card_tmp');
        session()->forget('delete_product');
        session()->forget('delete_service');
        session()->forget('delete_service_card');

        session()->forget('product');
        session()->forget('service');
        session()->forget('service_card');
        session()->forget('list_all');
        return true;
    }

    /**
     * Lưu gói bảo hành
     *
     * @param $input
     * @return array|mixed
     */
    public function store($input)
    {
        try {
            // Check gía trị bảo hành và số tiền tối đa được bảo hành
            $percent = str_replace(',', '', $input['percent']);
            $requiredPrice = str_replace(',', '', $input['moneyMaximum']);
            if ($percent < 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Giá trị bảo hành không được âm'),
                ]);
            } else if ($percent > 100) {
                return response()->json([
                    'error' => true,
                    'message' => __('Giá trị bảo hành không vượt quá 100%'),
                ]);
            }
            if ($requiredPrice < 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Số tiền tối đa được bảo hành không được âm'),
                ]);
            }
            // Check product, service, service card
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
            // Check đối tượng (sp, dv, thẻ dv) đã nằm trong gói bảo hành nầo hay chưa
            $checkObject = $this->checkObjectExist($arrListAll);
            if (count($checkObject['arrResult']) > 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Những đối tượng dưới đây đã nằm trong gói bảo hành khác') . $checkObject['message']
                ]);
            }

            $time = 0;
            $timeWarranty = str_replace(',', '', $input['timeWarranty']);
            $numberWarranty = str_replace(',', '', $input['numberWarranty']);
            switch ($input['timeType']) {
                case 'day': $time = $timeWarranty; break;
                case 'week': $time = $timeWarranty * 7; break;
                case 'month': $time = $timeWarranty * 30; break;
                case 'year': $time = $timeWarranty * 365; break;
                case 'infinitive': break;
            }
            $dataInsert = [
                'packed_name' => $input['packageName'],
                'time_type' => $input['timeType'],
                'time' => (int)$time,
                'percent' => $percent,
                'quota' => (int)$numberWarranty,
                'required_price' => $requiredPrice,
                'slug' => str_slug($input['packageName']),
                'description' => $input['shortDescription'],
                'detail_description' => $input['detailDescription'],
                'created_by' => Auth::id(),
            ];
            $id = $this->warrantyPackage->add($dataInsert);
            // update warranty code
            $code = 'PACKED_' . date('dmY') . sprintf("%02d", $id);
            $this->warrantyPackage->edit(['packed_code' => $code], $id);

            // insert warranty detail
            $warrantyPackageDetail = new WarrantyPackageDetailTable();
            foreach ($arrListAll as $item) {
                $dataDetail = [
                    'warranty_packed_code' => $code,
                    'object_type' => $item['object_type'],
                    'object_id' => $item['object_id'],
                    'object_code' => $item['object_code'],
                ];
                $warrantyPackageDetail->add($dataDetail);
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
     * Data cho view chỉnh sửa gói bảo hành
     *
     * @param $warrantyPackageId
     * @return mixed
     */
    public function dataViewEdit($warrantyPackageId)
    {
        $arrDetail = [];
        $arrService = [];
        $arrProduct = [];
        $arrServiceCard = [];
        // Xoá session
        session()->forget('product_tmp');
        session()->forget('service_tmp');
        session()->forget('service_card_tmp');
        session()->forget('delete_product');
        session()->forget('delete_service');
        session()->forget('delete_service_card');

        session()->forget('product');
        session()->forget('service');
        session()->forget('service_card');
        session()->forget('list_all');
        // Lấy thông tin gói bảo hành
        $warrantyInfo = $this->warrantyPackage->getInfoById($warrantyPackageId);
        $warrantyDetail = new WarrantyPackageDetailTable();
        $warrantyInfoDetail = $warrantyDetail->getDetailByPackageCode($warrantyInfo['packed_code']);
        // Put session
        foreach ($warrantyInfoDetail as $item) {
            $data = [
                'object_type' => $item['object_type'],
                'object_id' => $item['object_id'],
                'object_code' => $item['object_code'],
                'object_name' => $item['object_name'],
                'base_price' => $item['base_price'],
            ];

            $arrDetail[$item['object_code']] = $data;

            if ($item['object_type'] == 'product') {
                $arrProduct[$item['object_code']] = $data;
            } else if ($item['object_type'] == 'service') {
                $arrService[$item['object_code']] = $data;
            } else if ($item['object_type'] == 'service_card') {
                $arrServiceCard[$item['object_code']] = $data;
            }
        }
        session()->put('product', $arrProduct);
        session()->put('service', $arrService);
        session()->put('service_card', $arrServiceCard);
        session()->put('list_all', $arrDetail);
        //Danh sách param
        $listResult = $this->listParam([
            'page' => 1,
        ]);

        return [
            'data' => $warrantyInfo,
            'dataDetail' => $warrantyInfoDetail,
            'listResult' => $listResult
        ];
    }

    /**
     * Chỉah sửa gói bảo hành
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            // Check gía trị bảo hành và số tiền tối đa được bảo hành
            $percent = str_replace(',', '', $input['percent']);
            $requiredPrice = str_replace(',', '', $input['moneyMaximum']);
            if ($percent < 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Giá trị bảo hành không được âm'),
                ]);
            }  else if ($percent > 100) {
                return response()->json([
                    'error' => true,
                    'message' => __('Giá trị bảo hành không vượt quá 100%'),
                ]);
            }
            if ($requiredPrice < 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Số tiền tối đa được bảo hành không được âm'),
                ]);
            }
            // Check time type
            if (!isset($input['timeType'])) {
                return response()->json([
                    'error' => true,
                    'message' => __('Vui lòng chọn loại thời hạn bảo hành'),
                ]);
            }

            // Check product, service, service card
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
            // Check đối tượng (sp, dv, thẻ dv) đã nằm trong gói bảo hành nầo hay chưa
            $checkObject = $this->checkObjectExist($arrListAll, $input['packageCode']);
            if (count($checkObject['arrResult']) > 0) {
                return response()->json([
                    'error' => true,
                    'message' => __('Những đối tượng dưới đây đã nằm trong gói bảo hành khác') . $checkObject['message']
                ]);
            }

            $time = 0;
            $timeWarranty = str_replace(',', '', $input['timeWarranty']);
            $numberWarranty = str_replace(',', '', $input['numberWarranty']);
            switch ($input['timeType']) {
                case 'day': $time = $timeWarranty; break;
                case 'week': $time = $timeWarranty * 7; break;
                case 'month': $time = $timeWarranty * 30; break;
                case 'year': $time = $timeWarranty * 365; break;
                case 'infinitive': break;
            }
            // Update warranty
            $dataUpdate = [
                'packed_name' => $input['packageName'],
                'time_type' => $input['timeType'],
                'time' => (int)$time,
                'percent' => $percent,
                'quota' => (int)$numberWarranty,
                'required_price' => $requiredPrice,
                'slug' => str_slug($input['packageName']),
                'description' => $input['shortDescription'],
                'detail_description' => $input['detailDescription'],
                'updated_by' => Auth::id(),
            ];
            $this->warrantyPackage->editByPackageCode($dataUpdate, $input['packageCode']);
            // Update warranty detail (remove old -> insert new)
            $warrantyPackageDetail = new WarrantyPackageDetailTable();
            $warrantyPackageDetail->removeByPackageCode($input['packageCode']);
            foreach ($arrListAll as $item) {
                $dataDetail = [
                    'warranty_packed_code' => $input['packageCode'],
                    'object_type' => $item['object_type'],
                    'object_id' => $item['object_id'],
                    'object_code' => $item['object_code'],
                ];
                $warrantyPackageDetail->add($dataDetail);
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
     * Xoá gói bảo hành
     *
     * @param $input
     * @return array|mixed
     */
    public function delete($input)
    {
        try {
            $id = $input['packageId'];
            $this->warrantyPackage->edit([
                'is_deleted' => 1,
                'updated_by' => Auth::id()
            ], $id);
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
     * Cập nhật trạng thái bảo hành
     *
     * @param $input
     * @return array|mixed
     */
    public function updateStatus($input)
    {
        try {
            $id = $input['packageId'];
            $isActive = $input['is_actived'];
            $this->warrantyPackage->edit(['is_actived' => $isActive], $id);
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
     * Data cho view chi tiết gói bảo hành
     *
     * @param $warrantyPackageId
     * @return mixed
     */
    public function dataViewDetail($warrantyPackageId)
    {
        $arrDetail = [];
        $arrService = [];
        $arrProduct = [];
        $arrServiceCard = [];
        // Xoá session
        session()->forget('product_tmp');
        session()->forget('service_tmp');
        session()->forget('service_card_tmp');
        session()->forget('delete_product');
        session()->forget('delete_service');
        session()->forget('delete_service_card');

        session()->forget('product');
        session()->forget('service');
        session()->forget('service_card');
        session()->forget('list_all');
        // Lấy thông tin gói bảo hành
        $warrantyInfo = $this->warrantyPackage->getInfoById($warrantyPackageId);
        $warrantyDetail = new WarrantyPackageDetailTable();
        $warrantyInfoDetail = $warrantyDetail->getDetailByPackageCode($warrantyInfo['packed_code']);
        // Put session
        foreach ($warrantyInfoDetail as $item) {
            $data = [
                'object_type' => $item['object_type'],
                'object_id' => $item['object_id'],
                'object_code' => $item['object_code'],
                'object_name' => $item['object_name'],
                'base_price' => $item['base_price'],
            ];

            $arrDetail[$item['object_code']] = $data;

            if ($item['object_type'] == 'product') {
                $arrProduct[$item['object_code']] = $data;
            } else if ($item['object_type'] == 'service') {
                $arrService[$item['object_code']] = $data;
            } else if ($item['object_type'] == 'service_card') {
                $arrServiceCard[$item['object_code']] = $data;
            }
        }
        session()->put('product', $arrProduct);
        session()->put('service', $arrService);
        session()->put('service_card', $arrServiceCard);
        session()->put('list_all', $arrDetail);
        //Danh sách param
        $listResult = $this->listParam([
            'page' => 1,
        ]);

        return [
            'data' => $warrantyInfo,
            'dataDetail' => $warrantyInfoDetail,
            'listResult' => $listResult
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
        $mProduct = new ProductChildTable();
        $mService = new ServiceTable();
        $mServiceCard = new ServiceCardTable();

        if ($data['type'] == 'product') {
            //Get session product
            $arrProductTemp = [];
            if (session()->get('product')) {
                $arrProductTemp = session()->get('product');
            }

            session()->forget('product_tmp');
            session()->put('product_tmp', $arrProductTemp);
            session()->forget('delete_product');

            $list = $mProduct->getListChildOrderPaginate([]);

            $html = \View::make('warranty::warranty-package.popup.pop-product', [
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

            session()->forget('service_tmp');
            session()->put('service_tmp', $arrServiceTemp);
            session()->forget('delete_service');

            $list = $mService->getList([]);

            $html = \View::make('warranty::warranty-package.popup.pop-service', [
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

            session()->forget('service_card_tmp');
            session()->put('service_card_tmp', $arrServiceCardTemp);
            session()->forget('delete_service_card');

            $list = $mServiceCard->getList([]);

            $html = \View::make('warranty::warranty-package.popup.pop-service-card', [
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
        $mServiceCardGroup = new ServiceCardGroupTable();

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
        $mProduct = new ProductChildTable();

        $list = $mProduct->getListChildOrderPaginate($filter);

        //Get session product temp
        $arrProductTemp = [];
        if (session()->get('product_tmp')) {
            $arrProductTemp = session()->get('product_tmp');
        }

        return view('warranty::warranty-package.popup.list-product', [
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
        $mService = new ServiceTable();

        $list = $mService->getList($filter);

        //Get session service temp
        $arrServiceTemp = [];
        if (session()->get('service_tmp')) {
            $arrServiceTemp = session()->get('service_tmp');
        }

        return view('warranty::warranty-package.popup.list-service', [
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
        if (session()->get('service_card_tmp')) {
            $arrServiceCardTemp = session()->get('service_card_tmp');
        }

        return view('warranty::warranty-package.popup.list-service-card', [
            'list' => $list,
            'page' => $filter['page'],
            'arrServiceCardTemp' => $arrServiceCardTemp
        ]);
    }

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
            if (session()->get('product_tmp')) {
                $arrProductTemp = session()->get('product_tmp');
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
                    ];
                }
            }
            //Merge 2 array temp + new
            $arrProductTempNew = array_merge($arrProductTemp, $arrProductNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrProductTempNew, $arrProduct);
            //Lưu session temp mới
            session()->forget('product_tmp');
            session()->put('product_tmp', $arrResult);
        } else if ($data['type'] == 'service') {
            //Get session 9
            $arrService = [];
            if (session()->get('service')) {
                $arrService = session()->get('service');
            }
            //Get session temp
            $arrServiceTemp = [];
            if (session()->get('service_tmp')) {
                $arrServiceTemp = session()->get('service_tmp');
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
                    ];
                }
            }
            //Merge 2 array temp + new
            $arrServiceTempNew = array_merge($arrServiceTemp, $arrServiceNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrServiceTempNew, $arrService);
            //Lưu session temp mới
            session()->forget('service_tmp');
            session()->put('service_tmp', $arrResult);
        } else if ($data['type'] == 'service_card') {
            //Get session 9
            $arrServiceCard = [];
            if (session()->get('service_card')) {
                $arrServiceCard = session()->get('service_card');
            }
            //Get session temp
            $arrServiceCardTemp = [];
            if (session()->get('service_card_tmp')) {
                $arrServiceCardTemp = session()->get('service_card_tmp');
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
                    ];
                }
            }
            //Merge 2 array temp + new
            $arrServiceCardTempNew = array_merge($arrServiceCardNew, $arrServiceCard);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrServiceCardTempNew, $arrServiceCard);
            //Lưu session temp mới
            session()->forget('service_card_tmp');
            session()->put('service_card_tmp', $arrResult);
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
            if (session()->get('product_tmp')) {
                $arrProductTemp = session()->get('product_tmp');
            }
            //Merge vào array temp
            $arrProductNew = [
                $data['object_code'] => [
                    'object_type' => $data['type'],
                    'object_id' => $data['object_id'],
                    'object_code' => $data['object_code'],
                    'object_name' => $data['object_name'],
                    'base_price' => $data['base_price'],
                ]
            ];
            //Merge 2 array temp + new
            $arrProductTempNew = array_merge($arrProductTemp, $arrProductNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrProductTempNew, $arrProduct);
            //Lưu session temp mới
            session()->forget('product_tmp');
            session()->put('product_tmp', $arrResult);
        } else if ($data['type'] == 'service') {
            //Get session 9
            $arrService = [];
            if (session()->get('service')) {
                $arrService = session()->get('service');
            }
            //Get session temp
            $arrServiceTemp = [];
            if (session()->get('service_tmp')) {
                $arrServiceTemp = session()->get('service_tmp');
            }
            //Merge vào array temp
            $arrServiceNew = [
                $data['object_code'] => [
                    'object_type' => $data['type'],
                    'object_id' => $data['object_id'],
                    'object_code' => $data['object_code'],
                    'object_name' => $data['object_name'],
                    'base_price' => $data['base_price'],
                ]
            ];
            //Merge 2 array temp + new
            $arrServiceTempNew = array_merge($arrServiceTemp, $arrServiceNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrServiceTempNew, $arrService);
            //Lưu session temp mới
            session()->forget('service_tmp');
            session()->put('service_tmp', $arrResult);
        } else if ($data['type'] == 'service_card') {
            //Get session 9
            $arrServiceCard = [];
            if (session()->get('service_card')) {
                $arrServiceCard = session()->get('service_card');
            }
            //Get session temp
            $arrServiceCardTemp = [];
            if (session()->get('service_card_tmp')) {
                $arrServiceCardTemp = session()->get('service_card_tmp');
            }
            //Merge vào array temp
            $arrServiceCardNew = [
                $data['object_code'] => [
                    'object_type' => $data['type'],
                    'object_id' => $data['object_id'],
                    'object_code' => $data['object_code'],
                    'object_name' => $data['object_name'],
                    'base_price' => $data['base_price'],
                ]
            ];
            //Merge 2 array temp + new
            $arrServiceCardTempNew = array_merge($arrServiceCardTemp, $arrServiceCardNew);
            //Merge array 9 + arr new temp
            $arrResult = array_merge($arrServiceCardTempNew, $arrServiceCard);
            //Lưu session temp mới
            session()->forget('service_card_tmp');
            session()->put('service_card_tmp', $arrResult);
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
            if (session()->get('product_tmp')) {
                $arrProductTemp = session()->get('product_tmp');
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
            session()->forget('product_tmp');
            session()->put('product_tmp', $arrResult);
            //Get session remove temp
            if (session()->get('delete_product')) {
                $arrRemoveProductTemp = session()->get('delete_product');
            }
            //Lưu session remove temp
            session()->forget('delete_product');
            session()->put('delete_product', $arrRemoveProductTemp);
        } else if ($data['type'] == 'service') {
            //Get session 9
            $arrService = [];
            if (session()->get('service')) {
                $arrService = session()->get('service');
            }
            //Get session temp
            $arrServiceTemp = [];
            if (session()->get('service_tmp')) {
                $arrServiceTemp = session()->get('service_tmp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrServiceTemp, $arrService);
            $arrRemoveServiceTemp = [];
            //Unset phần tử
            if (count($data['arr_un_check']) > 0) {
                foreach ($data['arr_un_check'] as $v) {
                    $arrRemoveServiceTemp [] = $v['object_code'];
                    unset($arrResult[$v['object_code']]);
                }
            }
            //Lưu session temp mới
            session()->forget('service_tmp');
            session()->put('service_tmp', $arrResult);
            //Get session remove temp
            if (session()->get('delete_service')) {
                $arrRemoveServiceTemp = session()->get('delete_service');
            }
            //Lưu session remove temp
            session()->forget('delete_service');
            session()->put('delete_service', $arrRemoveServiceTemp);
        } else if ($data['type'] == 'service_card') {
            //Get session 9
            $arrServiceCard = [];
            if (session()->get('service_card')) {
                $arrServiceCard = session()->get('service_card');
            }
            //Get session temp
            $arrServiceCardTemp = [];
            if (session()->get('service_card_tmp')) {
                $arrServiceCardTemp = session()->get('service_card_tmp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrServiceCardTemp, $arrServiceCard);
            $arrRemoveServiceCardTemp = [];
            //Unset phần tử
            if (count($data['arr_un_check']) > 0) {
                foreach ($data['arr_un_check'] as $v) {
                    $arrRemoveServiceCardTemp [] = $v['object_code'];
                    unset($arrResult[$v['object_code']]);
                }
            }
            //Lưu session temp mới
            session()->forget('service_card_tmp');
            session()->put('service_card_tmp', $arrResult);
            //Get session remove temp
            if (session()->get('delete_service_card')) {
                $arrRemoveServiceCardTemp = session()->get('delete_service_card');
            }
            //Lưu session remove temp
            session()->forget('delete_service_card');
            session()->put('delete_service_card', $arrRemoveServiceCardTemp);
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
            if (session()->get('product_tmp')) {
                $arrProductTemp = session()->get('product_tmp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrProductTemp, $arrProduct);
            //Unset phần tử
            unset($arrResult[$data['object_code']]);
            //Lưu session temp mới
            session()->forget('product_tmp');
            session()->put('product_tmp', $arrResult);
            //Get session remove temp
            $arrRemoveProductTemp = [];
            if (session()->get('delete_product')) {
                $arrRemoveProductTemp = session()->get('delete_product');
            }
            //Lưu session remove temp
            $arrRemoveProductTemp [] = $data['object_code'];
            session()->forget('delete_product');
            session()->put('delete_product', $arrRemoveProductTemp);
        } else if ($data['type'] == 'service') {
            //Get session 9
            $arrService = [];
            if (session()->get('service')) {
                $arrService = session()->get('service');
            }
            //Get session temp
            $arrServiceTemp = [];
            if (session()->get('service_tmp')) {
                $arrServiceTemp = session()->get('service_tmp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrServiceTemp, $arrService);
            //Unset phần tử
            unset($arrResult[$data['object_code']]);
            //Lưu session temp mới
            session()->forget('service_tmp');
            session()->put('service_tmp', $arrResult);
            //Get session remove temp
            $arrRemoveServiceTemp = [];
            if (session()->get('delete_service')) {
                $arrRemoveServiceTemp = session()->get('delete_service');
            }
            //Lưu session remove temp
            $arrRemoveServiceTemp [] = $data['object_code'];
            session()->forget('delete_service');
            session()->put('delete_service', $arrRemoveServiceTemp);
        } else if ($data['type'] == 'service_card') {
            //Get session 9
            $arrServiceCard = [];
            if (session()->get('service_card')) {
                $arrServiceCard = session()->get('service_card');
            }
            //Get session temp
            $arrServiceCardTemp = [];
            if (session()->get('service_card_tmp')) {
                $arrServiceCardTemp = session()->get('service_card_tmp');
            }
            //Merge 2 array 9 + temp
            $arrResult = array_merge($arrServiceCardTemp, $arrServiceCard);
            //Unset phần tử
            unset($arrResult[$data['object_code']]);
            //Lưu session temp mới
            session()->forget('service_card_tmp');
            session()->put('service_card_tmp', $arrResult);
            //Get session remove temp
            $arrRemoveServiceCardTemp = [];
            if (session()->get('delete_service_card')) {
                $arrRemoveServiceCardTemp = session()->get('delete_service_card');
            }
            //Lưu session remove temp
            $arrRemoveServiceCardTemp [] = $data['object_code'];
            session()->forget('delete_service_card');
            session()->put('delete_service_card', $arrRemoveServiceCardTemp);
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
        if (session()->get('product_tmp')) {
            $arrProductTemp = session()->get('product_tmp');
        }
        //Merge product + product term
        $arrProductMerge = array_merge($arrProduct, $arrProductTemp);
        //Get session remove product
        $arrRemoveProduct = [];
        if (session()->get('delete_product')) {
            $arrRemoveProduct = session()->get('delete_product');
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
        session()->forget('product_tmp');
        //Get session service
        $arrService = [];
        if (session()->get('service')) {
            $arrService = session()->get('service');
        }
        //Get session service term
        $arrServiceTemp = [];
        if (session()->get('service_tmp')) {
            $arrServiceTemp = session()->get('service_tmp');
        }
        //Merge service + service term
        $arrServiceMerge = array_merge($arrService, $arrServiceTemp);
        //Get session remove service
        $arrRemoveService = [];
        if (session()->get('delete_service')) {
            $arrRemoveService = session()->get('delete_service');
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
        session()->forget('service_tmp');
        //Get session service card
        $arrServiceCard = [];
        if (session()->get('service_card')) {
            $arrServiceCard = session()->get('service_card');
        }
        //Get session service card term
        $arrServiceCardTemp = [];
        if (session()->get('service_card_tmp')) {
            $arrServiceCardTemp = session()->get('service_card_tmp');
        }
        //Merge service card + service card term
        $arrServiceCardMerge = array_merge($arrServiceCard, $arrServiceCardTemp);
        //Get session remove service card
        $arrRemoveServiceCard = [];
        if (session()->get('delete_service_card')) {
            $arrRemoveServiceCard = session()->get('delete_service_card');
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
        session()->forget('service_card_tmp');

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
        session()->forget('delete_product');
        session()->forget('delete_service');
        session()->forget('delete_service_card');
        //Lưu session all
        session()->forget('list_all');
        session()->put('list_all', $arrResult);

        $listResult = $this->listParam([
            'page' => 1,
        ]);

        $html = \View::make('warranty::warranty-package.list-object', [
            'list' => $listResult,
        ])->render();
        return [
            'html' => $html,
        ];

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
        if (count($arrResult) > 0) {
            $mService = new ServiceTable();
            $mProduct = new ProductChildTable();
            $mServiceCard = new ServiceCardTable();
            foreach ($arrResult as $k => $v) {
                switch ($v['object_type']) {
                    case 'product':
                        //Lấy thống tin sản phẩm
                        $getProduct = $mProduct->getProduct($v['object_code']);
                        $v['object_name'] = $getProduct['product_child_name'];
                        $v['base_price'] = $getProduct['price'];
                        break;
                    case 'service':
                        //Lấy thông tin dịch vụ
                        $getService = $mService->getService($v['object_code']);
                        $v['object_name'] = $getService['service_name'];
                        $v['base_price'] = $getService['price_standard'];
                        break;
                    case 'service_card':
                        //Lấy thông tin thẻ dịch vụ
                        $getServiceCard = $mServiceCard->getServiceCard($v['object_code']);
                        $v['object_name'] = $getServiceCard['name'];
                        $v['base_price'] = $getServiceCard['price'];
                        break;
                }
                $arrNew [$k] = $v;
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
        ]);

        return view('warranty::warranty-package.list-object', [
            'list' => $listResult,
            'page' => $filter['page'],
        ]);
    }

    /**
     * Phân trang ds discount sp, dv, thẻ dv cho view chi tiết
     *
     * @param array $filter
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDiscountDetail($filter = [])
    {
        $listResult = $this->listParam([
            'page' => $filter['page'],
        ]);

        return view('warranty::warranty-package.list-object-view-detail', [
            'list' => $listResult,
            'page' => $filter['page'],
        ]);
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
     * Check đối tượng của gói bảo hành
     *
     * @param $listObject
     * @param $warrantyPackedCode
     * @return array
     */
    public function checkObjectExist($listObject, $warrantyPackedCode = null)
    {
        $arrAllObject = [];  // Mảng những đối tượng đã có trong gói bảo hành
        $arrResult = []; // Mảng những đối tượng trùng (đã chọn và đã có trong các gói bảo hành)
        $message = '';
        $mWarrantyPackageDetail = new WarrantyPackageDetailTable();
        $getAllObject = $mWarrantyPackageDetail->getAllObject($warrantyPackedCode)->toArray();
        foreach ($getAllObject as $item) {
            $arrAllObject [] = $item['object_code'];
        }

        if (count($listObject) > 0) {
            foreach ($listObject as $item) {
                if (in_array($item['object_code'], $arrAllObject)) {
                    $arrResult [] = [
                        'object_code' => $item['object_code'],
                        'object_name' => $item['object_name']
                    ];
                    $message .= "</br>" . $item['object_name'];
                }
            }
        }
        return [
            'arrResult' => $arrResult,
            'message' => $message
        ];
    }
}
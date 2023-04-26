<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/21/2021
 * Time: 11:10 AM
 * @author nhandt
 */


namespace Modules\Contract\Repositories\ContractAnnex;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractAnnexFileModel;
use Modules\Contract\Models\ContractAnnexFollowMapTable;
use Modules\Contract\Models\ContractAnnexGeneralTable;
use Modules\Contract\Models\ContractAnnexGoodsTable;
use Modules\Contract\Models\ContractAnnexLogGoodsTable;
use Modules\Contract\Models\ContractAnnexLogTable;
use Modules\Contract\Models\ContractAnnexModel;
use Modules\Contract\Models\ContractAnnexPartnerTable;
use Modules\Contract\Models\ContractAnnexPaymentTable;
use Modules\Contract\Models\ContractAnnexSignMapTable;
use Modules\Contract\Models\ContractAnnexTagMapTable;
use Modules\Contract\Models\ContractCareTable;
use Modules\Contract\Models\ContractCategoriesTable;
use Modules\Contract\Models\ContractCategoryConfigTabTable;
use Modules\Contract\Models\ContractCategoryStatusTable;
use Modules\Contract\Models\ContractCategoryStatusUpdateTable;
use Modules\Contract\Models\ContractExpectedRevenueLogTable;
use Modules\Contract\Models\ContractExpectedRevenueTable;
use Modules\Contract\Models\ContractFollowMapTable;
use Modules\Contract\Models\ContractGoodsTable;
use Modules\Contract\Models\ContractLogGeneralTable;
use Modules\Contract\Models\ContractLogGoodsTable;
use Modules\Contract\Models\ContractLogPartnerTable;
use Modules\Contract\Models\ContractLogPaymentTable;
use Modules\Contract\Models\ContractLogReceiptSpendTable;
use Modules\Contract\Models\ContractLogTable;
use Modules\Contract\Models\ContractMapOrderTable;
use Modules\Contract\Models\ContractOverviewLogTable;
use Modules\Contract\Models\ContractPartnerTable;
use Modules\Contract\Models\ContractPaymentTable;
use Modules\Contract\Models\ContractReceiptDetailTable;
use Modules\Contract\Models\ContractReceiptTable;
use Modules\Contract\Models\ContractSignMapTable;
use Modules\Contract\Models\ContractSpendTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Models\ContractTagMapTable;
use Modules\Contract\Models\ContractTagTable;
use Modules\Contract\Models\CustomerTable;
use Modules\Contract\Models\DealTable;
use Modules\Contract\Models\OrderDetailTable;
use Modules\Contract\Models\OrderLogTable;
use Modules\Contract\Models\OrderTable;
use Modules\Contract\Models\PaymentMethodTable;
use Modules\Contract\Models\PaymentUnitTable;
use Modules\Contract\Models\ReceiptDetailTable;
use Modules\Contract\Models\ReceiptTable;
use Modules\Contract\Models\StaffTable;
use Modules\Contract\Models\SupplierTable;
use Modules\Contract\Models\UnitTable;
use Modules\Contract\Repositories\Contract\ContractRepoInterface;

class ContractAnnexRepo implements ContractAnnexRepoInterface
{
    protected $contractAnnex;
    protected $contractAnnexFile;
    protected $contractAnnexGeneral;
    protected $contractAnnexPartner;
    protected $contractAnnexPayment;
    protected $contractAnnexSignMap;
    protected $contractAnnexFollowMap;
    protected $contractAnnexTagMap;
    protected $contractAnnexLog;
    protected $contractAnnexLogGoods;
    const GOODS = "goods";

    public function __construct(ContractAnnexModel $contractAnnex,
                                ContractAnnexFileModel $contractAnnexFile,
                                ContractAnnexGeneralTable $contractAnnexGeneral,
                                ContractAnnexPaymentTable $contractAnnexPayment,
                                ContractAnnexPartnerTable $contractAnnexPartner,
                                ContractAnnexSignMapTable $contractAnnexSignMap,
                                ContractAnnexFollowMapTable $contractAnnexFollowMap,
                                ContractAnnexTagMapTable $contractAnnexTagMap,
                                ContractAnnexLogTable $contractAnnexLog,
                                ContractAnnexLogGoodsTable $contractAnnexLogGoods)
    {
        $this->contractAnnex = $contractAnnex;
        $this->contractAnnexFile = $contractAnnexFile;
        $this->contractAnnexGeneral = $contractAnnexGeneral;
        $this->contractAnnexPartner = $contractAnnexPartner;
        $this->contractAnnexPayment = $contractAnnexPayment;
        $this->contractAnnexSignMap = $contractAnnexSignMap;
        $this->contractAnnexFollowMap = $contractAnnexFollowMap;
        $this->contractAnnexTagMap = $contractAnnexTagMap;
        $this->contractAnnexLog = $contractAnnexLog;
        $this->contractAnnexLogGoods = $contractAnnexLogGoods;
    }

    /**
     * render popup add annex
     *
     * @param $data
     * @return array
     */
    public function getPopupAddAnnex($data)
    {
        if (isset($data['contract_annex_id']) && $data['contract_annex_id'] != '') {
            $item = $this->contractAnnex->getItem($data['contract_annex_id']);
            $html = \View::make('contract::contract.pop.annex.edit-annex', [
                'contractId' => $data['contract_id'],
                'item' => $item
            ])->render();
        } else {
            $mContract = app()->get(ContractTable::class);
            //Lấy thông tin HĐ
            $infoContract = $mContract->getInfo($data['contract_id']);

            $html = \View::make('contract::contract.pop.annex.add-annex', [
                'contractId' => $data['contract_id'],
                'dealCode' => isset($data['deal_code']) ? $data['deal_code'] : '',
                'infoContract' => $infoContract
            ])->render();
        }
        return [
            'html' => $html,
        ];
    }

    /**
     * save 1 annex of contract
     *
     * @param $data
     * @return mixed
     */
    protected function saveAnnex($data)
    {
        $lstFileName = isset($data['contract_annex_list_name_files']) ? $data['contract_annex_list_name_files'] : [];
        $lstLink = isset($data['contract_annex_list_files']) ? $data['contract_annex_list_files'] : [];
        unset($data['contract_annex_list_name_files']);
        unset($data['contract_annex_list_files']);
        $dataAnnex = [
            'contract_id' => $data['contract_id'],
            'contract_annex_code' => $data['contract_annex_code'],
            'sign_date' => Carbon::createFromFormat('d/m/Y', $data['sign_date'])->format('Y-m-d'),
            'effective_date' => Carbon::createFromFormat('d/m/Y', $data['effective_date'])->format('Y-m-d'),
            'expired_date' => Carbon::createFromFormat('d/m/Y', $data['expired_date'])->format('Y-m-d'),
            'adjustment_type' => $data['adjustment_type'],
            'content' => $data['content'],
            'is_active' => $data['is_active'],
            'created_by' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_by' => auth()->id(),
            'updated_at' => Carbon::now(),
        ];
        $contractAnnexId = $this->contractAnnex->createData($dataAnnex);

        if (count($lstLink) > 0) {
            $dataFile = [];
            foreach ($lstLink as $key => $value) {
                $dataFile[] = [
                    'contract_annex_id' => $contractAnnexId,
                    'link' => $lstLink[$key],
                    'name' => $lstFileName[$key],
                    'created_by' => auth()->id(),
                    'created_at' => Carbon::now(),
                    'updated_by' => auth()->id(),
                    'updated_at' => Carbon::now(),
                ];
            }
            $this->contractAnnexFile->insertData($dataFile);
        }
        return $contractAnnexId;
    }

    /**
     * function update info annex + log
     *
     * @param $data
     * @return mixed
     */
    protected function updateAnnex($data)
    {
        // check and save log
        $curAnnex = $this->contractAnnex->getItemFormatDateByCode($data['contract_annex_code']);
        $contractAnnexLog = [];
        foreach ($data as $key => $value) {
            if ($key != 'contract_annex_list_files' && $key != 'contract_annex_list_name_files') {
                if ($data[$key] != $curAnnex[$key]) {
                    $contractAnnexLog[] = [
                        "object_type" => 'annex',
                        "contract_annex_id" => $curAnnex['contract_annex_id'],
                        "key_table" => 'contract_annex',
                        "key" => $key,
                        "key_name" => "",
                        "value_old" => $curAnnex[$key],
                        "value_new" => $data[$key],
                        "created_by" => auth()->id(),
                        "updated_by" => auth()->id(),
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                    ];
                }
            }
        }
        $this->contractAnnexLog->insertData($contractAnnexLog);
        $lstFileName = isset($data['contract_annex_list_name_files']) ? $data['contract_annex_list_name_files'] : [];
        $lstLink = isset($data['contract_annex_list_files']) ? $data['contract_annex_list_files'] : [];
        unset($data['contract_annex_list_name_files']);
        unset($data['contract_annex_list_files']);
        $dataAnnex = [
            'contract_id' => $data['contract_id'],
            'contract_annex_code' => $data['contract_annex_code'],
            'sign_date' => Carbon::createFromFormat('d/m/Y', $data['sign_date'])->format('Y-m-d'),
            'effective_date' => Carbon::createFromFormat('d/m/Y', $data['effective_date'])->format('Y-m-d'),
            'expired_date' => Carbon::createFromFormat('d/m/Y', $data['expired_date'])->format('Y-m-d'),
            'adjustment_type' => $data['adjustment_type'],
            'content' => $data['content'],
            'is_active' => $data['is_active'],
            'created_by' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'updated_by' => auth()->id(),
        ];
        $this->contractAnnex->updateDataByCode($dataAnnex, $data['contract_annex_code']);
        $this->contractAnnexFile->deleteData($curAnnex['contract_annex_id']);
        if (count($lstLink) > 0) {
            $dataFile = [];
            foreach ($lstLink as $key => $value) {
                $dataFile[] = [
                    'contract_annex_id' => $curAnnex['contract_annex_id'],
                    'link' => $lstLink[$key],
                    'name' => $lstFileName[$key],
                    'created_by' => auth()->id(),
                    'created_at' => Carbon::now(),
                    'updated_by' => auth()->id(),
                    'updated_at' => Carbon::now(),
                ];
            }
            $this->contractAnnexFile->insertData($dataFile);
        }
        return $curAnnex['contract_annex_id'];
    }

    /**
     * save 1 annex of contract
     *
     * @param $data
     * @return array
     */
    public function submitSaveAnnex($data)
    {
        try {
            $this->saveAnnex($data);
            return [
                'error' => false,
                'message' => __('Tạo phụ lục hợp đồng thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * update annex
     *
     * @param $data
     * @return array
     */
    public function submitUpdateAnnex($data)
    {
        try {

            $this->updateAnnex($data);
            return [
                'error' => false,
                'message' => __('Chỉnh sửa phụ lục hợp đồng thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * get data 3 tab info
     *
     * @param $categoryId
     * @return array
     */
    private function _loadDataConfigTab($categoryId)
    {
        $mConfigTab = app()->get(ContractCategoryConfigTabTable::class);

        //Lấy cấu hình trường dữ liệu theo tab
        $getConfigTab = $mConfigTab->getConfigTabByCategory($categoryId)->toArray();

        $tabGeneral = [];
        $tabPartner = [];
        $tabPayment = [];

        if (count($getConfigTab) > 0) {
            foreach ($getConfigTab as $v) {
                if ($v['tab'] == 'general') {
                    $tabGeneral [] = $v;
                } else if ($v['tab'] == 'partner') {
                    $tabPartner [] = $v;
                } else if ($v['tab'] == 'payment') {
                    $tabPayment [] = $v;
                }
            }
        }

        return [
            'tabGeneral' => $tabGeneral,
            'tabPartner' => $tabPartner,
            'tabPayment' => $tabPayment
        ];
    }

    /**
     * xử lý khi type là cập nhật/bổ sung/gia hạn
     *
     * @param $data
     * @return array
     */
    public function actionContinueAnnex($data)
    {
        try {
            $finalData['contract_id'] = $data['contract_id'];
            $finalData['dataAnnexLocal'] = $data['dataAnnexLocal'];
            $mContract = new ContractTable();
            $mContractMapOrder = new ContractMapOrderTable();
            $info = $mContract->getInfo($data['contract_id']);
            if ($data['adjustment_type'] == 'renew_contract' && $data['is_active'] == 1) {
                // check đơn hàng của hợp đồng phải được thanh toán rồi
                // check có đơn hàng không?
                // nếu có thì check đơn hàng đã thanh toán chưa?
                // nếu đơn hàng chưa thanh toán thì thông báo lỗi, không cho tạo
                if ($info['type'] == 'sell') {
                    $dataMap = $mContractMapOrder->getOrderMap($info['contract_code']);
                    if ($dataMap != null) {
                        $dataOrder = $mContractMapOrder->getOrderMapByContract($info['contract_code'], $dataMap['order_code']);
                        if ($dataOrder != null) {
                            return [
                                'error' => true,
                                'message' => __('Hợp đồng chưa được thanh toán hết')
                            ];
                        }
                    }
                } else {
                    $mContractSpend = app()->get(ContractSpendTable::class);

                    $mContractPayment = app()->get(ContractPaymentTable::class);

                    //Lấy giá trị hợp đồng
                    $payment = $mContractPayment->getPaymentByContract($data['contract_id']);

                    $lastTotalAmount = $payment['last_total_amount'] != null ? floatval($payment['last_total_amount']) : 0;
                    //Lấy tiền đã thu của HĐ
                    $getAmountPaid = $mContractSpend->getAmountSpend($data['contract_id']);

                    $amountPaid = $getAmountPaid != null ? floatval($getAmountPaid['total_amount']) : 0;

                    //Nếu hđ mua thì check đã chi hết tiền chua mới cho update
                    if (($lastTotalAmount - $amountPaid) > 0) {
                        return [
                            'error' => true,
                            'message' => __('Hợp đồng chưa được thanh toán hết')
                        ];
                    }
                }
            }
            return [
                'error' => false,
                'finalData' => $finalData
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function actionContinueUpdateAnnex($data)
    {
        try {

            $finalData['contract_id'] = $data['contract_id'];
            $finalData['dataAnnexLocal'] = $data['dataAnnexLocal'];
            $mContract = new ContractTable();
            $mContractMapOrder = new ContractMapOrderTable();
            $info = $mContract->getInfo($data['contract_id']);
            if ($info['type'] == 'sell') {

                if ($data['adjustment_type'] == 'renew_contract' && $data['is_active'] == 1) {
                    // check đơn hàng của hợp đồng phải được thanh toán rồi
                    // check có đơn hàng không?
                    // nếu có thì check đơn hàng đã thanh toán chưa?
                    // nếu đơn hàng chưa thanh toán thì thông báo lỗi, không cho tạo
                    $dataMap = $mContractMapOrder->getOrderMap($info['contract_code']);
                    if ($dataMap != null) {
                        $dataOrder = $mContractMapOrder->getOrderMapByContract($info['contract_code'], $dataMap['order_code']);
                        if ($dataOrder != null) {
                            return [
                                'error' => true,
                                'message' => __('Hợp đồng chưa được thanh toán hết')
                            ];
                        }
                    }
                }
            } else {
                $mContractSpend = app()->get(ContractSpendTable::class);

                $mContractPayment = app()->get(ContractPaymentTable::class);

                //Lấy giá trị hợp đồng
                $payment = $mContractPayment->getPaymentByContract($data['contract_id']);

                $lastTotalAmount = $payment['last_total_amount'] != null ? floatval($payment['last_total_amount']) : 0;
                //Lấy tiền đã thu của HĐ
                $getAmountPaid = $mContractSpend->getAmountSpend($data['contract_id']);

                $amountPaid = $getAmountPaid != null ? floatval($getAmountPaid['total_amount']) : 0;

                //Nếu hđ mua thì check đã chi hết tiền chua mới cho update
                if (($lastTotalAmount - $amountPaid) > 0) {
                    return [
                        'error' => true,
                        'message' => __('Hợp đồng chưa được thanh toán hết')
                    ];
                }
            }
            return [
                'error' => false,
                'finalData' => $finalData
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * render new view to edit contract annex temp
     *
     * @param $data
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getViewEditContractAnnex($data)
    {
        session()->put('is_edit_annex', 1);
        $data = (array)json_decode($data);


        $mContractCategory = app()->get(ContractCategoriesTable::class);
        $mContract = app()->get(ContractTable::class);
        $mStaff = app()->get(StaffTable::class);
        $mContractTag = app()->get(ContractTagTable::class);
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mPaymentUnit = app()->get(PaymentUnitTable::class);
        $mContractStatus = app()->get(ContractCategoryStatusTable::class);
        $mContractTagMap = app()->get(ContractTagMapTable::class);
        $mContractFollowMap = app()->get(ContractFollowMapTable::class);
        $mContractSignMap = app()->get(ContractSignMapTable::class);
        $mContractPartner = app()->get(ContractPartnerTable::class);
        $mContractPayment = app()->get(ContractPaymentTable::class);
        $mCustomer = app()->get(CustomerTable::class);
        $mSupplier = app()->get(SupplierTable::class);
        $mUnit = app()->get(UnitTable::class);
        $mContractMapOrder = new ContractMapOrderTable();

        $dataAnnexLocal = count($data) > 0 ? (array)json_decode($data['dataAnnexLocal']) : [];

        // lưu thông tin phụ lục
        $infoAnnex = $this->contractAnnex->getInfoByCode($dataAnnexLocal['contract_annex_code']);
        $infoGeneral = $this->contractAnnexGeneral->getInfo($infoAnnex != null ? $infoAnnex['contract_annex_id'] : '');

        $contractAnnexId = '';
        if ($infoAnnex == null) {
            //Lấy thông tin HĐ
            $info = $mContract->getInfo($data['contract_id']);
            //Lấy dữ liệu load động của tab thông tin HĐ
            $dataCommon = $this->_loadDataConfigTab($info['contract_category_id']);
            //Lấy option loại HĐ
            $optionCategory = $mContractCategory->getOption();
            //Lấy option Nhân viên
            $optionStaff = $mStaff->getOption();
            //Lấy option tag
            $optionTag = $mContractTag->getOption();
            //Lấy option phương thức thanh toán
            $optionPaymentMethod = $mPaymentMethod->getOption();
            //Lấy option đơn vị thanh toán
            $optionPaymentUnit = $mPaymentUnit->getOption();
            //Lấy option trạng thái HĐ
            $optionStatus = $mContractStatus->getOptionByCategory($info['contract_category_id']);
            //Lấy thông tin đối tác
            $infoPartner = $mContractPartner->getPartnerByContract($data['contract_id']);
            //Lấy thông tin thanh toán
            $infoPayment = $mContractPayment->getPaymentByContract($data['contract_id']);
            //Lấy option đơn vị tính
            $optionUnit = $mUnit->getOption();
            //Lấy thông tin loại HĐ
            $infoCategory = $mContractCategory->getItem($info['contract_category_id']);

            $arrTagMap = [];
            $arrFollowMap = [];
            $arrSignMap = [];

            //Lấy tag map theo HĐ
            $getTagMap = $mContractTagMap->getTagMapByContract($data['contract_id']);

            if (count($getTagMap) > 0) {
                foreach ($getTagMap as $v) {
                    $arrTagMap [] = $v['tag_id'];
                }
            }
            //Lấy người theo dõi map theo HĐ
            $getFollowMap = $mContractFollowMap->getFollowMapByContract($data['contract_id']);

            if (count($getFollowMap) > 0) {
                foreach ($getFollowMap as $v) {
                    $arrFollowMap [] = $v['follow_by'];
                }
            }
            //Lấy người ký map theo HĐ
            $getSignMap = $mContractSignMap->getSignMapByContract($data['contract_id']);

            if (count($getSignMap) > 0) {
                foreach ($getSignMap as $v) {
                    $arrSignMap [] = $v['sign_by'];
                }
            }

            //Lấy option đối tác ăn theo loại đối tác
            if ($infoPartner['partner_object_type'] == "supplier") {
                //Nhà cung cấp
                $optionPartnerObject = $mSupplier->getOption();
            } else {
                //Khách hàng (cá nhân or doanh nghiệp)
                $optionPartnerObject = $mCustomer->getCustomer($infoPartner['partner_object_type']);
            }
        } else {//Lấy thông tin HĐ
            if ($infoGeneral == null) {
                //Lấy thông tin HĐ
                $info = $mContract->getInfo($data['contract_id']);
                //Lấy dữ liệu load động của tab thông tin HĐ
                $dataCommon = $this->_loadDataConfigTab($info['contract_category_id']);
                //Lấy option loại HĐ
                $optionCategory = $mContractCategory->getOption();
                //Lấy option Nhân viên
                $optionStaff = $mStaff->getOption();
                //Lấy option tag
                $optionTag = $mContractTag->getOption();
                //Lấy option phương thức thanh toán
                $optionPaymentMethod = $mPaymentMethod->getOption();
                //Lấy option đơn vị thanh toán
                $optionPaymentUnit = $mPaymentUnit->getOption();
                //Lấy option trạng thái HĐ
                $optionStatus = $mContractStatus->getOptionByCategory($info['contract_category_id']);
                //Lấy thông tin đối tác
                $infoPartner = $mContractPartner->getPartnerByContract($data['contract_id']);
                //Lấy thông tin thanh toán
                $infoPayment = $mContractPayment->getPaymentByContract($data['contract_id']);
                //Lấy option đơn vị tính
                $optionUnit = $mUnit->getOption();
                //Lấy thông tin loại HĐ
                $infoCategory = $mContractCategory->getItem($info['contract_category_id']);

                $arrTagMap = [];
                $arrFollowMap = [];
                $arrSignMap = [];

                //Lấy tag map theo HĐ
                $getTagMap = $mContractTagMap->getTagMapByContract($data['contract_id']);

                if (count($getTagMap) > 0) {
                    foreach ($getTagMap as $v) {
                        $arrTagMap [] = $v['tag_id'];
                    }
                }
                //Lấy người theo dõi map theo HĐ
                $getFollowMap = $mContractFollowMap->getFollowMapByContract($data['contract_id']);

                if (count($getFollowMap) > 0) {
                    foreach ($getFollowMap as $v) {
                        $arrFollowMap [] = $v['follow_by'];
                    }
                }
                //Lấy người ký map theo HĐ
                $getSignMap = $mContractSignMap->getSignMapByContract($data['contract_id']);

                if (count($getSignMap) > 0) {
                    foreach ($getSignMap as $v) {
                        $arrSignMap [] = $v['sign_by'];
                    }
                }

                //Lấy option đối tác ăn theo loại đối tác
                if ($infoPartner['partner_object_type'] == "supplier") {
                    //Nhà cung cấp
                    $optionPartnerObject = $mSupplier->getOption();
                } else {
                    //Khách hàng (cá nhân or doanh nghiệp)
                    $optionPartnerObject = $mCustomer->getCustomer($infoPartner['partner_object_type']);
                }
            } else {
                $info = $this->contractAnnexGeneral->getInfo($infoAnnex['contract_annex_id']);
                $info['contract_id'] = $data['contract_id'];
                //Lấy dữ liệu load động của tab thông tin HĐ
                $dataCommon = $this->_loadDataConfigTab($info['contract_category_id']);
                //Lấy option loại HĐ
                $optionCategory = $mContractCategory->getOption();
                //Lấy option Nhân viên
                $optionStaff = $mStaff->getOption();
                //Lấy option tag
                $optionTag = $mContractTag->getOption();
                //Lấy option phương thức thanh toán
                $optionPaymentMethod = $mPaymentMethod->getOption();
                //Lấy option đơn vị thanh toán
                $optionPaymentUnit = $mPaymentUnit->getOption();
                //Lấy option trạng thái HĐ
                $optionStatus = $mContractStatus->getOptionByCategory($info['contract_category_id']);
                //Lấy thông tin đối tác
                $infoPartner = $this->contractAnnexPartner->getInfo($infoAnnex['contract_annex_id']);
                //Lấy thông tin thanh toán
                $infoPayment = $this->contractAnnexPayment->getInfo($infoAnnex['contract_annex_id']);
                //Lấy option đơn vị tính
                $optionUnit = $mUnit->getOption();
                //Lấy thông tin loại HĐ
                $infoCategory = $mContractCategory->getItem($info['contract_category_id']);

                $arrTagMap = [];
                $arrFollowMap = [];
                $arrSignMap = [];

                //Lấy tag map theo HĐ
                $getTagMap = $this->contractAnnexTagMap->getTagMapByContract($infoAnnex['contract_annex_id']);

                if (count($getTagMap) > 0) {
                    foreach ($getTagMap as $v) {
                        $arrTagMap [] = $v['tag_id'];
                    }
                }
                //Lấy người theo dõi map theo HĐ
                $getFollowMap = $this->contractAnnexFollowMap->getFollowMapByContract($infoAnnex['contract_annex_id']);

                if (count($getFollowMap) > 0) {
                    foreach ($getFollowMap as $v) {
                        $arrFollowMap [] = $v['follow_by'];
                    }
                }
                //Lấy người ký map theo HĐ
                $getSignMap = $this->contractAnnexSignMap->getSignMapByContractAnnex($infoAnnex['contract_annex_id']);

                if (count($getSignMap) > 0) {
                    foreach ($getSignMap as $v) {
                        $arrSignMap [] = $v['sign_by'];
                    }
                }

                //Lấy option đối tác ăn theo loại đối tác
                if ($infoPartner['partner_object_type'] == "supplier") {
                    //Nhà cung cấp
                    $optionPartnerObject = $mSupplier->getOption();
                } else {
                    //Khách hàng (cá nhân or doanh nghiệp)
                    $optionPartnerObject = $mCustomer->getCustomer($infoPartner['partner_object_type']);
                }
            }

        }

        $finalData = [
            'dataAnnexLocal' => $dataAnnexLocal,
            "tabGeneral" => $dataCommon['tabGeneral'],
            "tabPartner" => $dataCommon['tabPartner'],
            "tabPayment" => $dataCommon['tabPayment'],
            'optionCategory' => $optionCategory,
            'optionStaff' => $optionStaff,
            'optionTag' => $optionTag,
            'categoryId' => $info['contract_category_id'],
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionPaymentUnit' => $optionPaymentUnit,
            'optionStatus' => $optionStatus,
            "infoGeneral" => $info,
            "arrTagMap" => $arrTagMap,
            "arrFollowMap" => $arrFollowMap,
            "arrSignMap" => $arrSignMap,
            "optionPartnerObject" => $optionPartnerObject,
            "infoPartner" => $infoPartner,
            "infoPayment" => $infoPayment,
            "optionUnit" => $optionUnit,
            'infoCategory' => $infoCategory,
        ];
        return view('contract::contract.edit-contract-annex', $finalData);
    }

    /**
     * save contract and save log
     *
     * @param $input
     * @return array
     */
    public function submitEditContractAnnex($input)
    {
        try {
            DB::beginTransaction();
            if (isset($input['dataAnnexLocal'])) {
                $dataAnnexLocal = (array)json_decode($input['dataAnnexLocal']);
                // lưu thông tin phụ lục
                $infoAnnex = $this->contractAnnex->getInfoByCode($dataAnnexLocal['contract_annex_code']);
                $contractAnnexId = '';
                if ($infoAnnex == null) {
                    $contractAnnexId = $this->saveAnnex($dataAnnexLocal);
                } else {
                    $contractAnnexId = $infoAnnex['contract_annex_id'];
                    $this->updateAnnex($dataAnnexLocal);
                }

                // chăm sóc thành công
                $mContractCare = new ContractCareTable();
                $mContractCare->updateDataByContract([
                    'status' => 'success'
                ],$input['contract_id']);

                if ($dataAnnexLocal['is_active'] == 1) {
                    // kiểm tra khác thông tin thì log lại
                    $mContract = new ContractTable();
                    $mContractPartner = new ContractPartnerTable();
                    $mContractPayment = new ContractPaymentTable();
                    $mContractTagMap = new ContractTagMapTable();
                    $mContractFollowMap = new ContractFollowMapTable();
                    $mContractSignMap = new ContractSignMapTable();
                    //Lấy thông tin HĐ
                    $infoContractGeneral = $mContract->getInfo($input['contract_id']);
                    $infoContractGeneral['tag'] = $mContractTagMap->getTagMapByContract($input['contract_id'])->toArray();
                    $infoContractGeneral['follow_by'] = $mContractFollowMap->getFollowMapByContract($input['contract_id'])->toArray();
                    $infoContractGeneral['sign_by'] = $mContractSignMap->getSignMapByContract($input['contract_id'])->toArray();
                    $infoContractPartner = $mContractPartner->getPartnerByContract($input['contract_id']);
                    $infoContractPayment = $mContractPayment->getPaymentByContract($input['contract_id']);
                    $newGeneral = $input['dataGeneral'];
                    $newGeneral['status_code'] = $input['status_code'];
                    $newGeneral['is_renew'] = $input['is_renew'];
                    $newGeneral['number_day_renew'] = $input['number_day_renew'];
                    $newGeneral['is_created_ticket'] = $input['is_created_ticket'];
                    $newGeneral['status_code_created_ticket'] = $input['status_code_created_ticket'];
                    $newGeneral['is_value_goods'] = $input['is_value_goods'];

                    $newPartner = $input['dataPartner'];
                    $newPayment = $input['dataPayment'];
                    $this->_logGeneralContractAnnex($infoContractGeneral, $newGeneral, $contractAnnexId);
                    $this->_logPartnerContractAnnex($infoContractPartner, $newPartner, $contractAnnexId);
                    $this->_logPaymentContractAnnex($infoContractPayment, $newPayment, $contractAnnexId);

                    // lưu thông tin hợp đồng
                    $this->_saveInfoContract($input);

                    // nếu loại là gia hạn thì lưu log gia hạn
                    $mContractOverviewLog = new ContractOverviewLogTable();
                    $mContractCategoryStatus = new ContractCategoryStatusTable();
                    // kiểm tra trạng thái đang thực hiện
                    $dataContractCategoryStatus = $mContractCategoryStatus->getStatusNameByCode($newGeneral['status_code']);
                    if($dataAnnexLocal['adjustment_type'] == 'renew_contract'
                        && $newGeneral['effective_date'] != ''
                        && $newGeneral['performer_by'] != ''
                        && $dataContractCategoryStatus['default_system'] == 'processing'){
                        $dataContractPayment = $mContractPayment->getPaymentByContract($input['contract_id']);
                        // check exitst log
                        $checkLog = $mContractOverviewLog->checkExistsLog($input['contract_id'], 'recare');
                        if($checkLog == null){
                            $dataContractOverviewLog = [
                                'contract_id' => $input['contract_id'],
                                'contract_overview_type' => 'recare',
                                'effective_date' => $newGeneral['effective_date'],
                                'performer_by' => $newGeneral['performer_by'],
                                'total_amount' => isset($dataContractPayment['last_total_amount']) ? $dataContractPayment['last_total_amount'] : 0,
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ];
                            $mContractOverviewLog->createDataLog($dataContractOverviewLog);
                        }
                    }

                }
                // lưu thông tin phụ lục hợp đồng (thông tin tương tự hợp đồng)
                $this->_saveInfoContractAnnex($input, $contractAnnexId);
            }

            DB::commit();
            return [
                'error' => false,
                'message' => __('Chỉnh sửa hợp đồng thành công')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => $e->getMessage() . ' ' . $e->getLine()
            ];
        }
    }

    /**
     * save log general annex
     *
     * @param $curGeneral
     * @param $newGeneral
     * @param $contractAnnexId
     */
    protected function _logGeneralContractAnnex($curGeneral, $newGeneral, $contractAnnexId)
    {
        $keyTable = 'contract_annex_general';
        $objectType = 'contract';
        $contractAnnexLog = [];
        foreach ($newGeneral as $key => $value) {
            if ($key != "tag" && $key != "sign_by" && $key != "follow_by") {
                if ($newGeneral[$key] != $curGeneral[$key]) {
                    $contractAnnexLog[] = [
                        "object_type" => $objectType,
                        "contract_annex_id" => $contractAnnexId,
                        "key_table" => $keyTable,
                        "key" => $key,
                        "key_name" => "",
                        "value_old" => $curGeneral[$key],
                        "value_new" => $newGeneral[$key],
                        "created_by" => auth()->id(),
                        "updated_by" => auth()->id(),
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                    ];
                }
            } else {
                $lstJsonOld = $lstJsonNew = "";
                switch ($key) {
                    case('tag'):
                        $mTag = new ContractTagTable();
                        $arrTagNew = [];
                        foreach ($newGeneral[$key] as $k => $v) {
                            $dataTag = $mTag->getInfo($v);
                            $arrTagNew[] = [
                                $v => $dataTag['name']
                            ];
                        }
                        $lstJsonNew = json_encode($arrTagNew);
                        $arrTagOld = [];
                        foreach ($curGeneral[$key] as $k => $v) {
                            $dataTag = $mTag->getInfo($v['tag_id']);
                            $arrTagOld[] = [
                                $v['tag_id'] => $dataTag['name']
                            ];
                        }
                        $lstJsonOld = json_encode($arrTagOld);
                        break;
                    case('sign_by'):
                        $mSign = new \Modules\Admin\Models\StaffTable();
                        $arrSignNew = [];
                        foreach ($newGeneral[$key] as $k => $v) {
                            $dataSign = $mSign->getDetail($v);
                            $arrSignNew[] = [
                                $v => $dataSign['full_name']
                            ];
                        }
                        $lstJsonNew = json_encode($arrSignNew);
                        $arrSignOld = [];
                        foreach ($curGeneral[$key] as $k => $v) {
                            $dataSign = $mSign->getDetail($v['sign_by']);
                            $arrSignOld[] = [
                                $v['sign_by'] => $dataSign['full_name']
                            ];
                        }
                        $lstJsonOld = json_encode($arrSignOld);
                        break;
                    case('follow_by'):
                        $mFollow = new \Modules\Admin\Models\StaffTable();
                        $arrFollowNew = [];
                        foreach ($newGeneral[$key] as $k => $v) {
                            $dataFollow = $mFollow->getDetail($v);
                            $arrFollowNew[] = [
                                $v => $dataFollow['full_name']
                            ];
                        }
                        $lstJsonNew = json_encode($arrFollowNew);
                        $arrFollowOld = [];
                        foreach ($curGeneral[$key] as $k => $v) {
                            $dataFollow = $mFollow->getDetail($v['follow_by']);
                            $arrFollowOld[] = [
                                $v['follow_by'] => $dataFollow['full_name']
                            ];
                        }
                        $lstJsonOld = json_encode($arrFollowOld);
                        break;
                }
                if ($lstJsonNew != $lstJsonOld) {
                    $contractAnnexLog[] = [
                        "object_type" => $objectType,
                        "contract_annex_id" => $contractAnnexId,
                        "key_table" => $keyTable,
                        "key" => $key,
                        "key_name" => "",
                        "value_old" => $lstJsonOld,
                        "value_new" => $lstJsonNew,
                        "created_by" => auth()->id(),
                        "updated_by" => auth()->id(),
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                    ];
                }
            }
        }
        $this->contractAnnexLog->insertData($contractAnnexLog);
    }

    /**
     * save log partner annex
     *
     * @param $curPartner
     * @param $newPartner
     * @param $contractAnnexId
     */
    protected function _logPartnerContractAnnex($curPartner, $newPartner, $contractAnnexId)
    {
        $keyTable = 'contract_annex_partner';
        $objectType = 'contract';
        $contractAnnexLog = [];
        foreach ($newPartner as $key => $value) {
            if ($newPartner[$key] != $curPartner[$key]) {
                $contractAnnexLog[] = [
                    "object_type" => $objectType,
                    "contract_annex_id" => $contractAnnexId,
                    "key_table" => $keyTable,
                    "key" => $key,
                    "key_name" => "",
                    "value_old" => $curPartner[$key],
                    "value_new" => $newPartner[$key],
                    "created_by" => auth()->id(),
                    "updated_by" => auth()->id(),
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ];
            }
        }
        $this->contractAnnexLog->insertData($contractAnnexLog);
    }

    /**
     * save log payment annex
     *
     * @param $curPayment
     * @param $newPayment
     * @param $contractAnnexId
     */
    protected function _logPaymentContractAnnex($curPayment, $newPayment, $contractAnnexId)
    {
        $keyTable = 'contract_annex_payment';
        $objectType = 'contract';
        $contractAnnexLog = [];
        foreach ($newPayment as $key => $value) {
            if ($newPayment[$key] != $curPayment[$key]) {
                $contractAnnexLog[] = [
                    "object_type" => $objectType,
                    "contract_annex_id" => $contractAnnexId,
                    "key_table" => $keyTable,
                    "key" => $key,
                    "key_name" => "",
                    "value_old" => $curPayment[$key],
                    "value_new" => $newPayment[$key],
                    "created_by" => auth()->id(),
                    "updated_by" => auth()->id(),
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ];
            }
        }
        $this->contractAnnexLog->insertData($contractAnnexLog);
    }

    /**
     * save general, partner, payment of annex
     *
     * @param $input
     * @param $contractAnnexId
     */
    protected function _saveInfoContractAnnex($input, $contractAnnexId)
    {
        $tag = isset($input['dataGeneral']['tag']) ? $input['dataGeneral']['tag'] : [];
        $follow = isset($input['dataGeneral']['follow_by']) ? $input['dataGeneral']['follow_by'] : [];
        $sign = isset($input['dataGeneral']['sign_by']) ? $input['dataGeneral']['sign_by'] : [];

        unset($input['dataGeneral']['tag'], $input['dataGeneral']['follow_by'], $input['dataGeneral']['sign_by']);

        $input['dataGeneral']['contract_annex_id'] = $contractAnnexId;
        $input['dataGeneral']['status_code'] = $input['status_code'];
        $input['dataGeneral']['is_renew'] = $input['is_renew'];
        $input['dataGeneral']['number_day_renew'] = $input['number_day_renew'];
        $input['dataGeneral']['is_created_ticket'] = $input['is_created_ticket'];
        $input['dataGeneral']['status_code_created_ticket'] = $input['status_code_created_ticket'];
        $input['dataGeneral']['is_value_goods'] = $input['is_value_goods'];
        $input['dataGeneral']['updated_by'] = Auth()->id();

        //Chỉnh sửa hoặc tạo thông tin HĐ
        if (!$this->contractAnnexGeneral->getInfo($contractAnnexId)) {
            $this->contractAnnexGeneral->add($input['dataGeneral']);
        } else {
            $this->contractAnnexGeneral->edit($input['dataGeneral'], $contractAnnexId);
        }
        //Xoá tag HĐ
        $this->contractAnnexTagMap->removeTagByContractAnnex($contractAnnexId);
        $arrTag = [];

        if (count($tag) > 0) {
            foreach ($tag as $v) {
                $arrTag [] = [
                    "contract_annex_id" => $contractAnnexId,
                    "tag_id" => $v
                ];
            }
        }
        //Thêm tag HĐ
        $this->contractAnnexTagMap->insert($arrTag);
        //Xoá người ký
        $this->contractAnnexSignMap->removeSignByContractAnnex($contractAnnexId);

        $arrSign = [];

        if (count($sign) > 0) {
            foreach ($sign as $v) {
                $arrSign [] = [
                    "contract_annex_id" => $contractAnnexId,
                    "sign_by" => $v
                ];
            }
        }
        //Thêm người ký
        $this->contractAnnexSignMap->insert($arrSign);
        //Xoá người theo dõi
        $this->contractAnnexFollowMap->removeFollowByContractAnnex($contractAnnexId);

        $arrFollow = [];

        if (count($follow) > 0) {
            foreach ($follow as $v) {
                $arrFollow [] = [
                    "contract_annex_id" => $contractAnnexId,
                    "follow_by" => $v
                ];
            }
        }
        //Thêm người theo dõi
        $this->contractAnnexFollowMap->insert($arrFollow);

        //Chỉnh sửa thông tin đối tác
        $input['dataPartner']['contract_annex_id'] = $contractAnnexId;
        if (!$this->contractAnnexPartner->getInfo($contractAnnexId)) {
            $this->contractAnnexPartner->add($input['dataPartner']);
        } else {
            $this->contractAnnexPartner->edit($input['dataPartner'], $contractAnnexId);
        }

        //Chỉnh sửa thông tin thanh toán
        $input['dataPayment']['contract_annex_id'] = $contractAnnexId;
        if (!$this->contractAnnexPayment->getInfo($contractAnnexId)) {
            $this->contractAnnexPayment->add($input['dataPayment']);
        } else {
            $this->contractAnnexPayment->edit($input['dataPayment'], $contractAnnexId);
        }
    }

    /**
     * save info contract if annex is active = 1
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    protected function _saveInfoContract($input)
    {
        $mContract = new ContractTable();
        $mContractTagMap = new ContractTagMapTable();
        $mContractFollowMap = new ContractFollowMapTable();
        $mContractSignMap = new ContractSignMapTable();
        $mContractPartner = new ContractPartnerTable();
        $mContractPayment = new ContractPaymentTable();

        //Lấy thông tin HĐ
        $infoContract = $mContract->getInfo($input['contract_id']);

//        //Kiểm tra trạng thái có được chỉnh sửa không
//        $checkStatusUpdate = $this->_validateStatusUpdate($infoContract, $input['status_code']);
//
//        if ($checkStatusUpdate == false) {
//            return response()->json([
//                "error" => true,
//                "message" => __("Trạng thái không được phép cập nhật")
//            ]);
//        }

        $tag = isset($input['dataGeneral']['tag']) ? $input['dataGeneral']['tag'] : [];
        $follow = isset($input['dataGeneral']['follow_by']) ? $input['dataGeneral']['follow_by'] : [];
        $sign = isset($input['dataGeneral']['sign_by']) ? $input['dataGeneral']['sign_by'] : [];

        unset($input['dataGeneral']['tag'], $input['dataGeneral']['follow_by'], $input['dataGeneral']['sign_by']);

        $input['dataGeneral']['status_code'] = $input['status_code'];
        $input['dataGeneral']['is_renew'] = $input['is_renew'];
        $input['dataGeneral']['number_day_renew'] = $input['number_day_renew'];
        $input['dataGeneral']['is_created_ticket'] = $input['is_created_ticket'];
        $input['dataGeneral']['status_code_created_ticket'] = $input['status_code_created_ticket'];
        $input['dataGeneral']['is_value_goods'] = $input['is_value_goods'];
        $input['dataGeneral']['updated_by'] = Auth()->id();

        //Chỉnh sửa thông tin HĐ
        $mContract->edit($input['dataGeneral'], $input['contract_id']);
        //Xoá tag HĐ
        $mContractTagMap->removeTagByContract($input['contract_id']);
        $arrTag = [];

        if (count($tag) > 0) {
            foreach ($tag as $v) {
                $arrTag [] = [
                    "contract_id" => $input['contract_id'],
                    "tag_id" => $v
                ];
            }
        }
        //Thêm tag HĐ
        $mContractTagMap->insert($arrTag);
        //Xoá người ký
        $mContractSignMap->removeSignByContract($input['contract_id']);

        $arrSign = [];

        if (count($sign) > 0) {
            foreach ($sign as $v) {
                $arrSign [] = [
                    "contract_id" => $input['contract_id'],
                    "sign_by" => $v
                ];
            }
        }
        //Thêm người ký
        $mContractSignMap->insert($arrSign);
        //Xoá người theo dõi
        $mContractFollowMap->removeFollowByContract($input['contract_id']);

        $arrFollow = [];

        if (count($follow) > 0) {
            foreach ($follow as $v) {
                $arrFollow [] = [
                    "contract_id" => $input['contract_id'],
                    "follow_by" => $v
                ];
            }
        }
        //Thêm người theo dõi
        $mContractFollowMap->insert($arrFollow);
        //Chỉnh sửa thông tin đối tác
        $mContractPartner->edit($input['dataPartner'], $input['contract_id']);
        //Chỉnh sửa thông tin thanh toán
        $mContractPayment->edit($input['dataPayment'], $input['contract_id']);

        $mContractExpectedRevenue = app()->get(ContractExpectedRevenueTable::class);

        //Lấy thông tin dự kiến thu-chi của HĐ
        $getExpectedRevenue = $mContractExpectedRevenue->getExpectedRevenueByContract($infoContract['contract_id']);

        if (count($getExpectedRevenue) > 0) {
            foreach ($getExpectedRevenue as $v) {
                //Insert log nhắc thu - chi
                $this->_insertLogRevenue($infoContract['contract_id'], $v, $v['contract_expected_revenue_id']);
            }
        }
    }

    /**
     * Lưu log nhắc dự kiến thu - chi
     *
     * @param $infoContract
     * @param $infoExpectedRevenue
     */
    protected function _insertLogRevenue($infoContract, $infoExpectedRevenue)
    {
        $mRevenueLog = app()->get(ContractExpectedRevenueLogTable::class);
        $mContract = app()->get(ContractTable::class);
        $infoContract = $mContract->getInfo($infoContract);

        $arrLog = [];

        if ($infoExpectedRevenue['send_type'] == 'after' && $infoContract['sign_date'] != null) {
            //Sau ngày ký HĐ
            $date = Carbon::parse($infoContract['sign_date'])->addDays($infoExpectedRevenue['send_value'])->format('Y-m-d');

            $arrLog =[
                "contract_expected_revenue_id" => $infoExpectedRevenue['contract_expected_revenue_id'],
                "contract_id" => $infoContract['contract_id'],
                "date_send" => $date
            ];
        }

        if ($infoExpectedRevenue['send_type'] == 'hard' && $infoContract['effective_date'] != null && $infoContract['expired_date']) {
            //Cố định
            $dtStart = Carbon::parse($infoContract['effective_date']);
            $dtEnd = Carbon::parse($infoContract['expired_date']);
            $monthStart = Carbon::parse($infoContract['effective_date'])->format('Y-m');
            $monthStart = Carbon::parse($monthStart);
            $monthEnd = Carbon::parse($infoContract['expired_date'])->format('Y-m');
            $monthEnd = Carbon::parse($monthEnd);
            // get diff month
            $part1 = ($monthStart->format('Y') * 12) + $monthStart->format('m');
            $part2 = ($monthEnd->format('Y') * 12) + $monthEnd->format('m');
            $diffMonth = abs($part1 - $part2);
//            dd(dump($diff));
//            $diffMonth = $monthStart->diffInMonths($monthEnd);
            if ($diffMonth > 0 && $infoExpectedRevenue['send_value_child'] <= $diffMonth) {
                //Chia mỗi chu kỳ (làm tròn)
                $number = intval($diffMonth/$infoExpectedRevenue['send_value_child']);

                for ($i = 1; $i <= $number; $i++) {
                    $format = Carbon::parse($monthStart)->addMonths($i);
                    $date = Carbon::parse($monthStart)->addMonths($i)->format('Y-m') .'-'. sprintf("%02d", $infoExpectedRevenue['send_value']);
                    //Check ngày có tồn tại ko
                    if (checkdate($format->format('m'), sprintf("%02d", $infoExpectedRevenue['send_value']), $format->format('Y')) == true) {
                        if($dtStart->lte($format) && $dtEnd->gte($format)){
                            $arrLog [] = [
                                "contract_expected_revenue_id" => $infoExpectedRevenue['contract_expected_revenue_id'],
                                "contract_id" => $infoContract['contract_id'],
                                "date_send" => $date
                            ];
                        }
                    }
                }

            }
        }

        if ($infoExpectedRevenue['send_type'] == 'custom') {
            //Lấy ngày custom của log
            $getLog = $mRevenueLog->getLogByRevenue($infoExpectedRevenue['contract_expected_revenue_id']);

            //Tuỳ chọn ngày
            foreach ($getLog as $v) {
                if ($v['date_send'] != null) {
                    $arrLog [] = [
                        "contract_expected_revenue_id" => $infoExpectedRevenue['contract_expected_revenue_id'],
                        "contract_id" => $infoContract['contract_id'],
                        "date_send" => $v['date_send']
                    ];
                }
            }
        }

        //Xoá log nhắc thu - chi
        $mRevenueLog->removeLogByRevenue($infoExpectedRevenue['contract_expected_revenue_id']);
        //Insert log
        $mRevenueLog->insert($arrLog);
    }

    /**
     * validate status to update? yes or no update
     *
     * @param $infoContract
     * @param $statusUpdate
     * @return bool
     */
    protected function _validateStatusUpdate($infoContract, $statusUpdate)
    {
        if ($infoContract['status_code'] == $statusUpdate) {
            //Cập nhật được chính nó
            return true;
        }

        $mStatusUpdate = app()->get(ContractCategoryStatusUpdateTable::class);

        //Lấy trạng thái được cập nhật
        $getStatusUpdate = $mStatusUpdate->getStatusUpdate($infoContract['status_code']);

        if (count($getStatusUpdate) == 0) {
            return false;
        } else {
            $arrayUpdate = [];

            foreach ($getStatusUpdate as $v) {
                $arrayUpdate [] = $v['status_code_update'];
            }

            if (!in_array($statusUpdate, $arrayUpdate)) {
                return false;
            }
        }

        return true;
    }

    /**
     * process annex goods
     *
     * @param $input
     * @return array
     */
    public function storeAnnexGood($input)
    {
        try {
            DB::beginTransaction();
            if (isset($input['dataAnnexLocal'])) {
                $dataAnnexLocal = (array)json_decode($input['dataAnnexLocal']);
                // lưu thông tin phụ lục
                $infoAnnex = $this->contractAnnex->getInfoByCode($dataAnnexLocal['contract_annex_code']);
                $contractAnnexId = '';
                if ($infoAnnex == null) {
                    $contractAnnexId = $this->saveAnnex($dataAnnexLocal);
                } else {
                    $contractAnnexId = $infoAnnex['contract_annex_id'];
                }
                // chăm sóc thành công
                $mContractCare = new ContractCareTable();
                $mContractCare->updateDataByContract([
                    'status' => 'success'
                ],$input['contract_id']);

                $orderCodeLoad = isset($input['order_code']) ? $input['order_code'] : null;

                if ($dataAnnexLocal['is_active'] == 1) {
                    // lưu log ở annex
                    $lastTotalAmount = 0;
                    $mContractAnnexLogGoods = new ContractAnnexLogGoodsTable();
                    $mContractAnnexLogGoods->deleteAnnexLogGoods($contractAnnexId);
                    // goods old - get from contract current
                    $mContractGoods = new ContractGoodsTable();
                    $lstCurrentGoods = $mContractGoods->getList($input);
                    // goods new - get just added
                    $lstNewGoods = $input['arrData'];
                    $lstAnnexLogGoods = [];
                    foreach ($lstCurrentGoods as $k => $v) {
                        $lstAnnexLogGoods[] = [
                            "contract_annex_id" => $contractAnnexId,
                            "version" => 'old',
                            "object_type" => $v['object_type'],
                            "object_name" => $v['object_name'],
                            "object_code" => $v['object_code'],
                            "object_id" => $v['object_id'],
                            "unit_id" => $v['unit_id'],
                            "quantity" => $v['quantity'],
                            "price" => $v['price'],
                            "tax" => $v['tax'],
                            "discount" => $v['discount'],
                            "amount" => $v['amount'],
                            "note" => $v['note'],
                            "order_code" => isset($v['order_code']) && $v['order_code'] != null ? $v['order_code'] : $orderCodeLoad,
                            "staff_id" => isset($v['staff_id']) ?? '',
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id()
                        ];
                    }
                    foreach ($lstNewGoods as $k => $v) {
                        $lstAnnexLogGoods[] = [
                            "contract_annex_id" => $contractAnnexId,
                            "version" => 'new',
                            "object_type" => $v['object_type'],
                            "object_name" => $v['object_name'],
                            "object_code" => $v['object_code'],
                            "object_id" => $v['object_id'],
                            "unit_id" => $v['unit_id'],
                            "quantity" => $v['quantity'],
                            "price" => $v['price'],
                            "tax" => $v['tax'],
                            "discount" => $v['discount'],
                            "amount" => $v['amount'],
                            "note" => $v['note'],
                            "order_code" => isset($v['order_code']) && $v['order_code'] != null ? $v['order_code'] : $orderCodeLoad,
                            "staff_id" => isset($v['staff_id']) ?? '',
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id()
                        ];
                        $lastTotalAmount += $v['amount'];
                    }
                    $mContractAnnexLogGoods->insertList($lstAnnexLogGoods);
                    // save info contract goods
                    $this->_saveInfoContractGoods($input);
                }
                // lưu thông tin hàng hoá (tương tự hàng hoá hợp đồng)
                $this->_saveInfoAnnexGoods($input, $contractAnnexId);
            }

            DB::commit();
            return [
                'error' => false,
                'message' => __('Chỉnh sửa hợp đồng thành công')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

    }

    /**
     * save contract goods if annex active
     *
     * @param $input
     */
    protected function _saveInfoContractGoods($input)
    {
        $mContract = new ContractTable();
        $mContractGoods = new ContractGoodsTable();
        $mLog = new ContractLogTable();
        $mLogGoods = new ContractLogGoodsTable();
        $mContractMapOrder = new ContractMapOrderTable();
        $mContractCategory = new ContractCategoriesTable();
        $mOrder = new OrderTable();
        $mOrderDetail = new OrderDetailTable();
        $mContractPayment = new ContractPaymentTable();

        //Lấy thông tin HĐ
        $infoContract = $mContract->getInfo($input['contract_id']);
        //Lấy thông tin loại HĐ
        $infoCategory = $mContractCategory->getItem($infoContract['contract_category_id']);
        $totalAmount = 0;
        $totalVAT = 0;
        $totalDiscount = 0;
        $lastTotalAmount = 0;
        $countNoKpi = 0;

        $orderCodeLoad = isset($input['order_code']) ? $input['order_code'] : null;
        //Xoá hàng hoá cũ
        $mContractGoods->removeGoodsByContract($input['contract_id']);
        if (isset($input['arrData']) && count($input['arrData']) > 0) {
            foreach ($input['arrData'] as $v) {
                //Lưu thông tin hàng hoá
                $goodsId = $mContractGoods->add([
                    "contract_id" => $input['contract_id'],
                    "object_type" => $v['object_type'],
                    "object_name" => $v['object_name'],
                    "object_id" => $v['object_id'],
                    "object_code" => $v['object_code'],
                    "unit_id" => $v['unit_id'],
                    "price" => $v['price'],
                    "quantity" => $v['quantity'],
                    "discount" => $v['discount'],
                    "tax" => $v['tax'],
                    "amount" => $v['amount'],
                    "note" => $v['note'],
                    "is_applied_kpi" => $v['is_applied_kpi'],
                    "order_code" => isset($v['order_code']) && $v['order_code'] != null ? $v['order_code'] : $orderCodeLoad,
                    "staff_id" => isset($v['staff_id']) ?? '',
                    "created_by" => Auth()->id(),
                    "updated_by" => Auth()->id()
                ]);

                $totalAmount += $v['price'] * $v['quantity'];
                $totalVAT += $v['tax'];
                $totalDiscount += $v['discount'];
                $lastTotalAmount += $v['amount'];

                $countNoKpi += (int)$v['is_applied_kpi'];
            }
        }
        $mContract->edit([
            'is_applied_kpi' => $countNoKpi == 0 ? 0 : 1
        ], $input['contract_id']);

        if ($infoCategory['type'] == 'sell') {
            $input['total_amount'] = $totalAmount;
            $input['total_discount'] = $totalDiscount;
            $input['total_tax'] = $totalVAT;
            $input['last_total_amount'] = $lastTotalAmount;

            //Lấy thông tin đơn hàng
            $infoOrder = $mOrder->getInfoByCode($orderCodeLoad);

            //Nếu order_code = null thì tạo đơn hàng mới
            if ($infoOrder == null && $orderCodeLoad == null) {
                //Tạo đơn hàng mới
                $infoOrder = $this->_insertOrder($infoContract, $input);
            }

            //Xoá sản phâm của đơn hàng
            $mOrderDetail->removeDetailByOrder($infoOrder['order_id']);
            //Update thông tin đơn hàng
            if (isset($input['arrData']) && count($input['arrData']) > 0) {
                foreach ($input['arrData'] as $v) {
                    $mOrderDetail->add([
                        "order_id" => $infoOrder['order_id'],
                        "object_id" => $v['object_id'],
                        "object_name" => $v['object_name'],
                        "object_type" => $v['object_type'],
                        "object_code" => $v['object_code'],
                        "staff_id" => isset($v['staff_id']) ?? '',
                        "price" => $v['price'],
                        "quantity" => $v['quantity'],
                        "discount" => $v['discount'],
                        "amount" => $v['amount'],
                        "tax" => $v['tax']
                    ]);
                }
            }

            //Kiểm tra đơn hàng đã map với hợp đồng chưa
            $checkOrderMap = $mContractMapOrder->getOrderMapByContract($infoContract['contract_code'], $orderCodeLoad);

            if ($checkOrderMap == null) {
                //Insert map với hđ
                $mContractMapOrder->add([
                    'contract_code' => $infoContract['contract_code'],
                    'order_code' => $infoOrder['order_code']
                ]);
                //Lần đầu map với đơn hàng thì insert chi tiết thu
                if (in_array($infoOrder['process_status'], ['paysuccess', 'pay-half'])) {
                    //Nếu đơn hàng đã thanh toán - insert chi tiết thu
                    $this->_insertContractReceipt($infoContract, $infoOrder['order_id']);
                }
            }
            //Update giá trị đơn hàng
            $mOrder->edit([
                'total' => $totalAmount,
                'discount' => $totalDiscount,
                'amount' => $lastTotalAmount,
                'total_tax' => $totalVAT
            ], $infoOrder['order_id']);
        }

        //Update giá trị hợp đồng (nếu là hđ bán, or là hđ mua có check lấy giá trị)
        if ($infoCategory['type'] == 'sell' || $infoContract['is_value_goods'] == 1) {
            $mContractPayment->edit([
                'total_amount' => $totalAmount,
                'tax' => $totalVAT,
                'discount' => $totalDiscount,
                'last_total_amount' => $lastTotalAmount
            ], $infoContract['contract_id']);
        }
    }

    /**
     * save annex goods
     *
     * @param $input
     * @param $contractAnnexId
     */
    protected function _saveInfoAnnexGoods($input, $contractAnnexId)
    {
        $mContract = new ContractTable();
        $mContractCategory = new ContractCategoriesTable();

        //Lấy thông tin HĐ
        $infoContract = $mContract->getInfo($input['contract_id']);
        //Lấy thông tin loại HĐ
        $infoCategory = $mContractCategory->getItem($infoContract['contract_category_id']);

        $totalAmount = 0;
        $totalVAT = 0;
        $totalDiscount = 0;
        $lastTotalAmount = 0;
        $orderCodeLoad = isset($input['order_code']) ? $input['order_code'] : null;
        $mContractAnnexGoods = new ContractAnnexGoodsTable();
        $mContractAnnexGoods->deleteAnnexGoods($contractAnnexId);
        if (isset($input['arrData']) && count($input['arrData']) > 0) {
            foreach ($input['arrData'] as $v) {
                //Lưu thông tin hàng hoá
                $goodsId = $mContractAnnexGoods->add([
                    "contract_annex_id" => $contractAnnexId,
                    "object_type" => $v['object_type'],
                    "object_name" => $v['object_name'],
                    "object_id" => $v['object_id'],
                    "object_code" => $v['object_code'],
                    "unit_id" => $v['unit_id'],
                    "price" => $v['price'],
                    "quantity" => $v['quantity'],
                    "discount" => $v['discount'],
                    "tax" => $v['tax'],
                    "amount" => $v['amount'],
                    "note" => $v['note'],
                    "is_applied_kpi" => $v['is_applied_kpi'],
                    "order_code" => isset($v['order_code']) && $v['order_code'] != null ? $v['order_code'] : $orderCodeLoad,
                    "staff_id" => isset($v['staff_id']) ?? '',
                    "created_by" => Auth()->id(),
                    "updated_by" => Auth()->id()
                ]);
                $totalAmount += $v['price'];
                $totalVAT += $v['tax'];
                $totalDiscount += $v['discount'];
                $lastTotalAmount += $v['amount'];
            }
        }

        if ($infoCategory['type'] == 'sell' || $infoContract['is_value_goods'] == 1) {
            $this->contractAnnexPayment->edit([
                'total_amount' => $totalAmount,
                'tax' => $totalVAT,
                'discount' => $totalDiscount,
                'last_total_amount' => $lastTotalAmount
            ], $contractAnnexId);
        }
    }

    public function deleteAnnex($data)
    {
        try {
            DB::beginTransaction();
            $contractAnnexId = $data['contract_id'];
            $info = $this->contractAnnex->getItem($contractAnnexId);
            if ($info['is_active'] == 1 && $info['adjustment_type'] != 'update_info') {
                $mContractGoods = new ContractGoodsTable();
                $mContracts = new ContractTable();
                $mContractCategory = new ContractCategoriesTable();
                $mContractPartner = new ContractPartnerTable();
                $mContractPayment = new ContractPaymentTable();
                $mContractTagMap = new ContractTagMapTable();
                $mContractFollowMap = new ContractFollowMapTable();
                $mContractSignMap = new ContractSignMapTable();
                $mOrderDetail = new OrderDetailTable();
                $mOrder = new OrderTable();
                //Lấy thông tin HĐ
                $infoContract = $mContracts->getInfo($info['contract_id']);
                //Lấy thông tin loại HĐ
                $infoCategory = $mContractCategory->getItem($infoContract['contract_category_id']);
                $logInfo = $this->contractAnnexLog->getLogContractAnnexCommon('contract', $contractAnnexId);
                $logGoodsOld = $this->contractAnnexLogGoods->getLogGoodsContractAnnex($contractAnnexId, 'old');
                $logGoodsNew = $this->contractAnnexLogGoods->getLogGoodsContractAnnex($contractAnnexId, 'new');
                // back version contract info 3 tabs
                foreach ($logInfo as $v) {
                    switch ($v['key_table']) {
                        case('contract_annex_general'):
                            // back contracts
                            switch ($v['key']) {
                                case('tag'):
                                    //Xoá tag HĐ
                                    $mContractTagMap->removeTagByContract($info['contract_id']);
                                    $arrTag = [];
                                    $tag = json_decode($v['value_old']);
                                    if (count($tag) > 0) {
                                        foreach ($tag as $kt => $vt) {
                                            $vt = (array)$vt;
                                            $arrTag [] = [
                                                "contract_id" => $info['contract_id'],
                                                "tag_id" => array_key_first($vt)
                                            ];
                                        }
                                    }
                                    //Thêm tag HĐ
                                    $mContractTagMap->insert($arrTag);
                                    break;
                                case('sign_by'):
                                    $sign = json_decode($v['value_old']);
                                    //Xoá người ký
                                    $mContractSignMap->removeSignByContract($info['contract_id']);

                                    $arrSign = [];

                                    if (count($sign) > 0) {
                                        foreach ($sign as $ks => $vs) {
                                            ;
                                            $vs = (array)$vs;
                                            $arrSign [] = [
                                                "contract_id" => $info['contract_id'],
                                                "sign_by" => array_key_first($vs)
                                            ];
                                        }
                                    }
                                    //Thêm người ký
                                    $mContractSignMap->insert($arrSign);
                                    break;
                                case('follow_by'):
                                    $follow = json_decode($v['value_old']);
                                    //Xoá người theo dõi
                                    $mContractFollowMap->removeFollowByContract($info['contract_id']);
                                    $arrFollow = [];

                                    if (count($follow) > 0) {
                                        foreach ($follow as $kf => $vf) {
                                            $vf = (array)$vf;
                                            $arrFollow [] = [
                                                "contract_id" => $info['contract_id'],
                                                "follow_by" => array_key_first($vf)
                                            ];
                                        }
                                    }
                                    //Thêm người theo dõi
                                    $mContractFollowMap->insert($arrFollow);
                                    break;
                                default:
                                    $mContracts->edit([
                                        $v['key'] => $v['value_old']
                                    ], $info['contract_id']);
                                    break;
                            }
                            break;
                        case('contract_annex_partner'):
                            // back contract_partner
                            $mContractPartner->edit([
                                $v['key'] => $v['value_old']
                            ], $info['contract_id']);
                            break;
                        case('contract_annex_payment'):
                            // back contract_payment
                            $mContractPayment->edit([
                                $v['key'] => $v['value_old']
                            ], $info['contract_id']);
                            break;
                    }
                }

                foreach ($logGoodsOld as $v) {
                    //Lưu thông tin hàng hoá
                    $goodsId = $mContractGoods->add([
                        "contract_id" => $info['contract_id'],
                        "object_type" => $v['object_type'],
                        "object_name" => $v['object_name'],
                        "object_id" => $v['object_id'],
                        "object_code" => $v['object_code'],
                        "unit_id" => $v['unit_id'],
                        "price" => $v['price'],
                        "quantity" => $v['quantity'],
                        "discount" => $v['discount'],
                        "tax" => $v['tax'],
                        "amount" => $v['amount'],
                        "note" => $v['note'],
                        "order_code" => $v['order_code'],
                        "staff_id" => $v['staff_id'],
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id()
                    ]);
                    //Xoá sản phâm của đơn hàng
                    $orderCode = $mContractGoods->getOrderCodeByContract($info['contract_id']);
                    if ($orderCode != null) {
                        $infoOrder = $mOrder->getInfoByCode($orderCode);
                        if ($infoOrder != null) {
                            $mOrderDetail->removeDetailByOrder($infoOrder['order_id']);
                        }
                    }
                    // back version contract goods
                    $mContractGoods->removeGoodsByContract($info['contract_id']);

                    if ($orderCode != null) {
                        $infoOrder = $mOrder->getInfoByCode($orderCode);
                        if ($infoOrder != null) {
                            $mOrderDetail->add([
                                "order_id" => $infoOrder['order_id'],
                                "object_id" => $v['object_id'],
                                "object_name" => $v['object_name'],
                                "object_type" => $v['object_type'],
                                "object_code" => $v['object_code'],
                                "staff_id" => $v['staff_id'],
                                "price" => $v['price'],
                                "quantity" => $v['quantity'],
                                "discount" => $v['discount'],
                                "amount" => $v['amount'],
                                "tax" => $v['tax']
                            ]);
                        }
                    }
                }

                $mContractExpectedRevenue = app()->get(ContractExpectedRevenueTable::class);

                //Lấy thông tin dự kiến thu-chi của HĐ
                $getExpectedRevenue = $mContractExpectedRevenue->getExpectedRevenueByContract($infoContract['contract_id']);


                if (count($getExpectedRevenue) > 0) {
                    foreach ($getExpectedRevenue as $v) {
                        //Insert log nhắc thu - chi
                        $this->_insertLogRevenue($infoContract['contract_id'], $v, $v['contract_expected_revenue_id']);
                    }
                }
            }
            $this->contractAnnex->updateData([
                'is_deleted' => 1,
                'contract_annex_code' => ''
            ], $contractAnnexId);
            DB::commit();
            return [
                'error' => false,
                'message' => __('Xoá phụ lục hợp đồng thành công')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getDataViewDetail($contractAnnexId)
    {
        $item = $this->contractAnnex->getItem($contractAnnexId);
        $info = $this->contractAnnex->getInfoContractByAnnex($contractAnnexId);
        $infoGeneral = $this->contractAnnexLog->getLogContractAnnex($info['contract_category_id'], 'contract', $contractAnnexId, 'contract_annex_general');
        $infoPartner = $this->contractAnnexLog->getLogContractAnnex($info['contract_category_id'], 'contract', $contractAnnexId, 'contract_annex_partner');
        $infoPayment = $this->contractAnnexLog->getLogContractAnnex($info['contract_category_id'], 'contract', $contractAnnexId, 'contract_annex_payment');
        $logGoodsOld = $this->contractAnnexLogGoods->getLogGoodsContractAnnex($contractAnnexId, 'old');
        $logGoodsNew = $this->contractAnnexLogGoods->getLogGoodsContractAnnex($contractAnnexId, 'new');
        $logInfo = $this->contractAnnexLog->getLogContractAnnexCommon('annex', $contractAnnexId);
        return [
            'contract_id' => $item['contract_id'],
            'item' => $item,
            'infoGeneral' => $infoGeneral,
            'infoPartner' => $infoPartner,
            'infoPayment' => $infoPayment,
            'logGoodsOld' => $logGoodsOld,
            'logGoodsNew' => $logGoodsNew,
            'logInfo' => $logInfo,
        ];
    }

    private function _insertOrder($infoContract, $input)
    {
        $mContractPartner = app()->get(ContractPartnerTable::class);

        //Lấy thông tin đối tác
        $infoPartner = $mContractPartner->getPartnerByContract($infoContract['contract_id']);

        if ($infoPartner != null) {
            $mOrder = app()->get(OrderTable::class);
            $mOrderLog = app()->get(OrderLogTable::class);

            //Thêm đơn hàng
            $idOrder = $mOrder->add([
                'branch_id' => Auth()->user()->branch_id,
                'customer_id' => $infoPartner['partner_object_id'],
                'total' => $input['total_amount'],
                'discount' => $input['total_discount'],
                'amount' => $input['last_total_amount'],
                'tranport_charge' => 0,
                'total_tax' => $input['total_tax'],
                'order_source_id' => 1,
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);
            //Update order code
            $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $idOrder);
            $mOrder->edit([
                'order_code' => $orderCode
            ], $idOrder);
            //Insert order log đơn hàng mới
            $mOrderLog->insert([
                'order_id' => $idOrder,
                'created_type' => 'backend',
                'status' => 'new',
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'note_vi' => 'Đặt hàng thành công',
                'note_en' => 'Order success'
            ]);

            //Lấy thông tin đơn hàng
            return $mOrder->getOrderByCode($infoPartner['partner_object_id'], $orderCode);
        }
    }

    private function _insertContractReceipt($infoContract, $orderId)
    {
        $mReceipt = app()->get(ReceiptTable::class);
        $mReceiptDetail = app()->get(ReceiptDetailTable::class);
        $mContractReceipt = app()->get(ContractReceiptTable::class);
        $mContractReceiptDetail = app()->get(ContractReceiptDetailTable::class);
        $mLog = app()->get(ContractLogTable::class);
        $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);

        //Lấy thông tin thanh toán của đơn hàng
        $getReceipt = $mReceipt->getTotalReceipt($orderId);

        if (count($getReceipt) > 0) {
            foreach ($getReceipt as $v) {
                //Thêm đợt thu
                $contractReceiptId = $mContractReceipt->add([
                    'contract_id' => $infoContract['contract_id'],
                    'receipt_code' => $v['receipt_code'],
                    'content' => __("Thanh toán đơn hàng") . ' ' . $v['order_code'],
                    'collection_date' => Carbon::now()->format('Y-m-d'),
                    'collection_by' => $v['staff_id'],
                    'total_amount_receipt' => $v['amount_paid'],
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id()
                ]);
                //Lấy thông tin chi tiết thanh toán
                $getReceiptDetail = $mReceiptDetail->getReceiptDetail($v['receipt_id']);

                $arrReceiptDetail = [];

                if (count($getReceiptDetail) > 0) {
                    foreach ($getReceiptDetail as $v1) {
                        $arrReceiptDetail [] = [
                            "contract_receipt_id" => $contractReceiptId,
                            "amount_receipt" => $v1['amount'],
                            "payment_method_id" => $v1['payment_method_id'],
                            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                            "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }
                //Thêm chi tiết đợt thu
                $mContractReceiptDetail->insert($arrReceiptDetail);

                //Lưu log hợp đồng khi trigger thu - chi
                $logId = $mLog->add([
                    "contract_id" => $infoContract['contract_id'],
                    "change_object_type" => self::RECEIPT,
                    "note" => __('Thêm đợt thu'),
                    "created_by" => Auth()->id(),
                    "updated_by" => Auth()->id()
                ]);
                //Log detail
                $mLogReceipt->add([
                    "contract_log_id" => $logId,
                    "object_type" => self::RECEIPT,
                    "object_id" => $contractReceiptId
                ]);
            }

        }
    }
}
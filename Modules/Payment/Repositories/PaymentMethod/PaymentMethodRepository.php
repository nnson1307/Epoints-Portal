<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/08/2021
 * Time: 17:05 AM
 */

namespace Modules\Payment\Repositories\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Modules\Payment\Http\Api\PaymentOnline;
use Modules\Payment\Models\PaymentMethodTable;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    protected $paymentMethod;
    public function __construct(PaymentMethodTable $paymentMethod)
    {
        $this->paymentMethod=$paymentMethod;
    }

    /**
     * Danh sách HTTT, filter, paging
     *
     * @param array $filters
     * @return array
     */
    public function getList(array &$filters = [])
    {
        $list = $this->paymentMethod->getList($filters);
        return [
            "list" => $list,
        ];
    }

    /**
     * Lưu HTTT mới
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($input)
    {
        try {
            $dataInsert = [
                'payment_method_code' => $input["payment_method_code"],
                'payment_method_name_vi' => $input["payment_method_name_vi"],
                'payment_method_name_en' => $input["payment_method_name_en"],
                'payment_method_type' => $input["payment_method_type"],
                'created_by' => Auth::id(),
                'note' => $input['note']
            ];
            $this->paymentMethod->add($dataInsert);
            return response()->json([
                'error' => false,
                'message' => __('Thêm hình thức thanh toán thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thêm hình thức thanh toán thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * View Edit khi chọn Edit HTTT
     *
     * @param $warrantyPackageId
     * @return array|mixed
     */
    public function dataViewEdit($warrantyPackageId)
    {
        $data = $this->paymentMethod->getInfo($warrantyPackageId);
        if($data == null){
            return [];
        }
        else{
            return $data;
        }
    }

    /**
     * Cập nhật HTTT
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($input)
    {
        try {
            $dataUpdate = [
                'payment_method_id' => $input["payment_method_id"],
                'payment_method_name_vi' => $input["payment_method_name_vi"],
                'payment_method_name_en' => $input["payment_method_name_en"],
                'payment_method_type' => $input["payment_method_type"],
                'url' => isset($input["url"]) ? $input["url"] : '',
                'terminal_id' => isset($input["terminal_id"]) ? $input["terminal_id"] : '',
                'secret_key' => isset($input["secret_key"]) ? $input["secret_key"] : '',
                'access_key' => isset($input["access_key"]) ? $input["access_key"] : '',
                'is_active' => $input["is_active"],
                'updated_by' => Auth::id(),
                'note' => $input['note']
            ];
            $this->paymentMethod->edit($dataUpdate,$input["payment_method_id"]);
            if($input["payment_method_code"] == 'VNPAY'){

                $domain = request()->getHost();
                $brandCode = preg_replace('/(.+)' . DOMAIN_PIOSPA . '/', '$1', $domain);
                $mPaymentOnlineApi = new PaymentOnline();
                $dataSettingVnPay = [
                    'method' => 'vnpay',
                    'code' => $input["terminal_id"],
                    'secret' => $input["secret_key"],
                    'ipn_url' => sprintf(LOYALTY_API_URL, $brandCode) . '/order/ipn-payment-vn-pay',
                    'retry_url' => $input["url"],
                    'callback_url' => sprintf(LOYALTY_API_URL, $brandCode) . '/order/return-payment-vn-pay',
                ];
                $dataReturn = $mPaymentOnlineApi->settingPaymentVnPay($dataSettingVnPay);
            }
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa hình thức thanh toán thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa hình thức thanh toán thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xoá HTTT
     *
     * @param $input
     * @return mixed
     */
    public function delete($input)
    {
        return $this->paymentMethod->deleteType($input);
    }

    /**
     * Lấy thông tin select option HTTT
     *
     * @return array
     */
    public function getPaymentMethodOption()
    {
        $array=array();
        foreach ($this->paymentMethod->getPaymentMethodOption() as $item)
        {
            $array[$item['payment_method_code']]=$item['payment_method_name_vi'];
        }
        return $array;
    }
}
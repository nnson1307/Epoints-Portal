<?php


namespace Modules\FNB\Repositories\FNBCustomerRequest;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\FNBCustomerRequestTable;

class FNBCustomerRequestRepository implements FNBCustomerRequestRepositoryInterface
{

    private $status = [
        'new' => [
            'status' => 'new',
            'name' => 'Chưa xử lý',
            'color' => 'text-warning'
        ],
        'processing' => [
            'status' => 'processing',
            'name' => 'Đang xử lý',
            'color' => 'text-primary'
        ],
        'done' => [
            'status' => 'done',
            'name' => 'Hoàn thành',
            'color' => 'text-success'
        ],
    ];

    /**
     * Thay đổi trạng thái
     * @param $data
     * @return mixed|void
     */
    public function confirmCustomerRequest($data)
    {
        try {

            $mFNBCustomerRequest = app()->get(FNBCustomerRequestTable::class);

            $text = '';

            $status = $this->status;

//            Cập nhật trạng thái
            $mFNBCustomerRequest->editAction([
                'status' => $data['status'],
                'process_by' => Auth::id(),
                'process_at' => Carbon::now()
            ],$data['customerRequestId']);

            $item = $mFNBCustomerRequest->getDetail($data['customerRequestId']);

            $view = view('fnb::orders.inc.tr-customer-request',['item'=> $item])->render();

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công'),
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ];
        }
    }
}
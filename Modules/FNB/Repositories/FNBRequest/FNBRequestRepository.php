<?php


namespace Modules\FNB\Repositories\FNBRequest;
use Modules\FNB\Models\FNBCustomerRequestTable;
use Modules\FNB\Models\FNBTableTable;
use Modules\FNB\Models\PaymentMethodTable;
use Modules\FNB\Repositories\FNBRequest\FNBRequestRepositoryInterface;


class FNBRequestRepository implements FNBRequestRepositoryInterface
{
   public  function getListTable(){
       $mTable = app()->get(FNBTableTable::class);
       $listTable = $mTable->getListNoPage();
       return $listTable;
   }
    public  function getListRequest($input){
       $mRequest = app()->get(FNBCustomerRequestTable::class);
       $listRequest = $mRequest->allRequest($input);
       return $listRequest;
    }
    public  function getListPaymentMethod(){
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $listPaymentMethod = $mPaymentMethod->allPaymentMethod();
        return $listPaymentMethod;
    }

}
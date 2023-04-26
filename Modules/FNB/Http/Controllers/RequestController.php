<?php

namespace Modules\FNB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FNB\Repositories\FNBRequest\FNBRequestRepositoryInterface;


class RequestController extends Controller
{
    private $requestt;
    private $route = 'fnb.request';

    public function __construct(FNBRequestRepositoryInterface $requestt)
    {
        $this->request = $requestt;
    }
    public function index(Request $request){
        $input = $request->all();
        //danh sách bàn(filter)
        $listTable = $this->listTable();
        //danh sách phương thức thanh toán
        $listPaymentMethod = $this->listPaymentMethod();
        //danh sách yêu cầu
        $listRequest =  $this->request->getListRequest($input);
        return view('fnb::request.index',[
            'input' => $input,
            'listTable' => $listTable,
            'listRequest' => $listRequest,
            'listPaymentMethod' => $listPaymentMethod,
        ]);
    }

    public function list(Request $request){
        $input = $request->all();
        //danh sách bàn(filter)
        $listTable = $this->listTable();
        //danh sách phương thức thanh toán
        $listPaymentMethod = $this->listPaymentMethod();
        //danh sách yêu cầu
        $listRequest =  $this->request->getListRequest($input);
        return view('fnb::request.list',[
            'input' => $input,
            'listTable' => $listTable,
            'listRequest' => $listRequest,
            'listPaymentMethod' => $listPaymentMethod,
        ]);
    }

    public function listTable(){
        $data =  $this->request ->getListTable();
        return $data;
    }
    public function listPaymentMethod(){
        $data =  $this->request ->getListPaymentMethod();
        return $data;
    }


}

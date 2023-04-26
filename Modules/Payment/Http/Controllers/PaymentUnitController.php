<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payment\Http\Requests\PaymentUnit\StoreRequest;
use Modules\Payment\Http\Requests\PaymentUnit\UpdateRequest;
use Modules\Payment\Repositories\PaymentUnit\PaymentUnitRepositoryInterface;

class PaymentUnitController extends Controller
{
    protected $paymentUnit;

    public function __construct(PaymentUnitRepositoryInterface $paymentUnit)
    {
        $this->paymentUnit = $paymentUnit;
    }

    public function index()
    {
        $data = $this->paymentUnit->getList();
        return view('payment::payment-unit.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    public function filters()
    {
        return [

        ];
    }

    public function listAction(Request $request)
    {
        $filter = $request->all();
        $data = $this->paymentUnit->getList($filter);
        return view('payment::payment-unit.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }
    public function create()
    {
        return view('payment::payment-unit.create');
    }
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->paymentUnit->store($data);
    }
    public function edit($paymentUnitId)
    {
        $getData = $this->paymentUnit->dataViewEdit($paymentUnitId);
        return view('payment::payment-unit.edit', [
            'item' => $getData
        ]);
    }
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->paymentUnit->update($data);
    }
    public function delete(Request $request)
    {
        $data = $request->all();
        return $this->paymentUnit->delete($data);
    }
}
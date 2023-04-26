<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payment\Http\Requests\PaymentMethod\StoreRequest;
use Modules\Payment\Http\Requests\PaymentMethod\UpdateRequest;
use Modules\Payment\Repositories\PaymentMethod\PaymentMethodRepositoryInterface;

class PaymentMethodController extends Controller
{
    protected $paymentMethod;

    public function __construct(PaymentMethodRepositoryInterface $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function index()
    {
        $data = $this->paymentMethod->getList();
        return view('payment::payment-method.index', [
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
        $filter = $request->only([
            'page',
            'display',
            'search',
            'method_type',
        ]);
        $data = $this->paymentMethod->getList($filter);
        return view('payment::payment-method.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }
    public function create()
    {
        return view('payment::payment-method.create');
    }
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->paymentMethod->store($data);
    }
    public function edit($paymentMethodId)
    {
        $getData = $this->paymentMethod->dataViewEdit($paymentMethodId);
        return view('payment::payment-method.edit', [
            'item' => $getData
        ]);
    }
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->paymentMethod->update($data);
    }
    public function delete(Request $request)
    {
        $data = $request->all();
        return $this->paymentMethod->delete($data);
    }
}
<?php
namespace Modules\Delivery\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Delivery\Repositories\PickupAddress\PickupAddressRepoInterface;
use Modules\Delivery\Http\Requests\PickupAddress\StoreRequest;
use Modules\Delivery\Http\Requests\PickupAddress\UpdateRequest;

class PickupAddressController extends Controller
{
    protected $pickupAddress;
    public function __construct(PickupAddressRepoInterface $pickupAddress)
    {
        $this->pickupAddress = $pickupAddress;
    }

    /**
     * Danh sach dia chi lay hang
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->pickupAddress->list();
        return view('delivery::pickup-address.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {
        return [

        ];
    }

    /**
     * Ajax filter, phân trang ds địa chỉ lấy hàng
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at',
        ]);

        $data = $this->pickupAddress->list($filter);

        return view('delivery::pickup-address.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View tao dia chi lay hang
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function create()
    {
        return view('delivery::pickup-address.create');
    }

    /**
     * Luu dia chi lay hang moi
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->pickupAddress->store($data);
    }

    /**
     * View chinh sua dia chi lay hang
     *
     * @param $pickupAddressId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function edit($pickupAddressId)
    {
        $data = $this->pickupAddress->getDetail($pickupAddressId);
        return view('delivery::pickup-address.edit', [
            'data' => $data
        ]);
    }

    /**
     * Cap nhat dia chi lay hang
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->pickupAddress->update($data);
    }

    public function destroy(Request $request)
    {
        $id = $request->pickup_address_id;
        return $this->pickupAddress->destroy($id);
    }
}
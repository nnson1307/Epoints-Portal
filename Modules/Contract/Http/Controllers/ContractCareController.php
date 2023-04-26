<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 11/2/2021
 * Time: 2:59 PM
 * @author nhandt
 */


namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Repositories\ContractCare\ContractCareRepoInterface;

class ContractCareController extends Controller
{
    protected $contractCare;
    public function __construct(ContractCareRepoInterface $contractCare)
    {
        $this->contractCare = $contractCare;
    }

    /**
     * view chăm sóc hợp đồng hết hạn
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexExpireAction(Request $request)
    {
        $filter = $request->all();
        $filter['contract_type'] = 'expire';
        $data = $this->contractCare->getDataViewIndex($filter);
        return view('contract::contract-care.index-expire', $data);
    }

    /**
     * list chăm sóc hợp đồng hết hạn
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listExpireAction(Request $request)
    {
        $filter = $request->all();
        $filter['contract_type'] = 'expire';
        $data = $this->contractCare->getList($filter);
        $arrContractExpire = [];
        if (session()->get('contract-expire-temp')) {
            $arrContractExpire = session()->get('contract-expire-temp');
        }
        $arrContractSoonExpire = [];
        if (session()->get('contract-soon-expire-temp')) {
            $arrContractExpire = session()->get('contract-soon-expire-temp');
        }
        return view('contract::contract-care.list-expire', [
            'LIST' => $data,
            'page' => $filter['page'],
            'arrContractExpire' => $arrContractExpire,
            'arrContractSoonExpire' => $arrContractSoonExpire,
        ]);
    }

    /**
     * view chăm sóc hợp đồng sắp hết hạn
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexSoonExpireAction(Request $request)
    {
        $filter = $request->all();
        $filter['contract_type'] = 'soon_expire';
        $data = $this->contractCare->getDataViewIndex($filter);
        return view('contract::contract-care.index-soon-expire', $data);
    }

    /**
     * list chăm sóc hợp đồng sắp hết hạn
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listSoonExpireAction(Request $request)
    {
        $filter = $request->all();
        $filter['contract_type'] = 'soon_expire';
        $data = $this->contractCare->getList($filter);
        return view('contract::contract-care.list-soon-expire', [
            'LIST' => $data,
            'page' => $filter['page'],
        ]);
    }

    /**
     * chọn tất cả hợp đồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAllExpireAction(Request $request)
    {
        $data = $this->contractCare->chooseAllExpireAction($request->all());

        return response()->json($data);
    }

    /**
     * chọn 1 hợp đồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseExpireAction(Request $request)
    {
        $data = $this->contractCare->chooseExpireAction($request->all());

        return response()->json($data);
    }

    /**
     * bỏ chọn tất cả hợp đồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAllExpireAction(Request $request)
    {
        $data = $this->contractCare->unChooseAllExpireAction($request->all());

        return response()->json($data);
    }

    /**
     * bỏ chọn hợp đồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseExpireAction(Request $request)
    {
        $data = $this->contractCare->unChooseExpireAction($request->all());

        return response()->json($data);
    }

    /**
     * chọn tất cả hợp đồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseAllSoonExpireAction(Request $request)
    {
        $data = $this->contractCare->chooseAllSoonExpireAction($request->all());

        return response()->json($data);
    }

    /**
     * chọn hợp đồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseSoonExpireAction(Request $request)
    {
        $data = $this->contractCare->chooseSoonExpireAction($request->all());

        return response()->json($data);
    }

    /**
     * bỏ chọn tất cả hợp đồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseAllSoonExpireAction(Request $request)
    {
        $data = $this->contractCare->unChooseAllSoonExpireAction($request->all());

        return response()->json($data);
    }

    /**
     * bỏ chọn hợp đồng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unChooseSoonExpireAction(Request $request)
    {
        $data = $this->contractCare->unChooseSoonExpireAction($request->all());

        return response()->json($data);
    }
    public function getPopupPerformDeal(Request $request)
    {
        return $this->contractCare->dataViewPopup($request->all());
    }
    public function submitCreateDeal(Request $request)
    {
        return $this->contractCare->submitCreateDeal($request->all());
    }
}
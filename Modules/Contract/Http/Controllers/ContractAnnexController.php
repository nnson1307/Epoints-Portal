<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/21/2021
 * Time: 11:08 AM
 * @author nhandt
 */


namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Http\Requests\ContractAnnex\StoreRequest;
use Modules\Contract\Http\Requests\ContractAnnex\UpdateRequest;
use Modules\Contract\Models\ContractAnnexTable;
use Modules\Contract\Repositories\ContractAnnex\ContractAnnexRepoInterface;

class ContractAnnexController extends Controller
{
    protected $contractAnnex;
    public function __construct(ContractAnnexRepoInterface $contractAnnex)
    {
        $this->contractAnnex = $contractAnnex;
    }

    public function listAction(Request $request)
    {
        $mContractAnnex = new ContractAnnexTable();
        $lstAnnex = $mContractAnnex->getList($request->all());
        return view('contract::contract.list-annex', [
            'LIST_ANNEX' => $lstAnnex,
            'page' => $request->page
        ]);
    }

    /**
     * render popup add annex
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPopupAddAnnex(Request $request)
    {
        $data = $this->contractAnnex->getPopupAddAnnex($request->all());
        return response()->json($data);
    }

    /**
     * save annex
     *
     * @param Request $request
     * @return mixed
     */
    public function submitSaveAnnex(StoreRequest $request)
    {
        return $this->contractAnnex->submitSaveAnnex($request->all());
    }
    public function submitUpdateAnnex(UpdateRequest $request)
    {
        return $this->contractAnnex->submitUpdateAnnex($request->all());
    }

    /**
     * process action update/review or update_info
     *
     * @param Request $request
     * @return mixed
     */
    public function actionContinueAnnex(StoreRequest $request)
    {
        return $this->contractAnnex->actionContinueAnnex($request->all());
    }
    public function actionContinueUpdateAnnex(UpdateRequest $request)
    {
        return $this->contractAnnex->actionContinueUpdateAnnex($request->all());
    }

    /**
     * view edit 3 tab + goods
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getViewEditContractAnnex(Request $request)
    {
        return isset($request->all()['finalData']) ?
            $this->contractAnnex->getViewEditContractAnnex($request->all()['finalData']) :
            redirect()->route('contract.contract');
    }

    /**
     * submit save 3 tab + log
     *
     * @param Request $request
     * @return mixed
     */
    public function submitEditContractAnnex(Request $request)
    {
        return $this->contractAnnex->submitEditContractAnnex($request->all());
    }

    /**
     * save annex good + save log
     *
     * @param Request $request
     * @return mixed
     */
    public function storeAnnexGood(Request $request)
    {
        return $this->contractAnnex->storeAnnexGood($request->all());
    }
    public function deleteAnnex(Request $request)
    {
        return $this->contractAnnex->deleteAnnex($request->all());
    }
    public function detailAction(Request $request)
    {
        $dataDetail = $this->contractAnnex->getDataViewDetail($request->id);
        return view('contract::contract.detail-annex', $dataDetail);
    }
}
<?php

namespace Modules\Contract\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Contract\Http\Requests\ContractCategory\StoreRequest;
use Modules\Contract\Http\Requests\ContractCategory\UpdateRequest;
use Modules\Contract\Repositories\ContractCategories\ContractCategoryRepoInterface;

class ContractCategoryController extends Controller
{
    private $contractCategoryRepo;
    public function __construct(ContractCategoryRepoInterface $contractCategoryRepo)
    {
        $this->contractCategoryRepo = $contractCategoryRepo;
    }

    /**
     * load ds loại hợp đồng + view all
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexAction(Request $request)
    {
        $lst = $this->contractCategoryRepo->listContractCategory($request->all());
        return view('contract::contract-category.index_ct', [
            'LIST' => $lst
        ]);
    }

    /**
     * reload ds hợp đồng có filter
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $page    = (int) ($request->all()['page'] ?? 1);
        $lst = $this->contractCategoryRepo->listContractCategory($request->all());
        return view('contract::contract-category.list', [
            'LIST' => $lst,
            'page' => $page
        ]);
    }

    /**
     * delete loại hợp đồng (update is_deleted)
     *
     * @param Request $request
     * @return mixed
     */
    public function deleteAction(Request $request)
    {
        return $this->contractCategoryRepo->deleteContractCategory($request->contract_category_id);
    }

    /**
     * data view create contract category
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function createAction(Request $request)
    {
        $dataView = $this->contractCategoryRepo->dataViewCreate();
        return view('contract::contract-category.create', $dataView);
    }

    /**
     * Save tab information contract categỏy
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function submitCreateContractCategoryAction(StoreRequest $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->submitCreateContractCategoryAction($data);
    }

    /**
     * Save tab general, payment, partner by contract_category_id
     *
     * @param Request $request
     * @return mixed
     */
    public function submitCreateTabAction(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->submitCreateTabAction($data);
    }

    /**
     * Lưu thông tin tab status
     *
     * @param Request $request
     * @return mixed
     */
    public function submitStatusTabAction(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->submitStatusTabAction($data);
    }

    /**
     * Edit 1 remind
     *
     * @param Request $request
     * @return mixed
     */
    public function submitEditRemindAction(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->submitEditRemindAction($data);
    }

    /**
     * View add remind (popup)
     *
     * @param Request $request
     * @return mixed
     */
    public function getViewAddRemind(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->getViewAddRemind($data);
    }

    /**
     * Chỉnh sửa remind
     *
     * @param Request $request
     * @return mixed
     */
    public function getViewEditRemind(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->getViewEditRemind($data);
    }

    /**
     * save 1 remind in category
     *
     * @param Request $request
     * @return mixed
     */
    public function submitRemindTabAction(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->submitRemindTabAction($data);
    }

    /**
     * remove 1 remind in category
     *
     * @param Request $request
     * @return mixed
     */
    public function removeRemindAction(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->removeRemindAction($data);
    }

    /**
     * Load list status of category to define notify
     *
     * @param Request $request
     * @return mixed
     */
    public function loadStatusNotify(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->loadStatusNotify($data);
    }

    /**
     * save notify tab
     *
     * @param Request $request
     * @return mixed
     */
    public function submitNotifyTabAction(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->submitNotifyTabAction($data);
    }

    /**
     * modal change content notify
     *
     * @param Request $request
     * @return mixed
     */
    public function modalChangeContentNotify(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->modalChangeContentNotify($data);
    }

    /**
     * view edit contract category
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function editAction(Request $request)
    {
        $dataView = $this->contractCategoryRepo->dataViewEdit($request->id);
        return view('contract::contract-category.edit', $dataView);
    }
    public function detailAction(Request $request)
    {
        $dataView = $this->contractCategoryRepo->dataViewEdit($request->id);
        return view('contract::contract-category.detail', $dataView);
    }

    /**
     * save contract category
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function submitEditContractCategoryAction(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->submitEditContractCategoryAction($data);
    }
    public function submitChangeStatusAction(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->submitChangeStatusAction($data);
    }

    /**
     * save edit status tab
     *
     * @param Request $request
     * @return mixed
     */
    public function submitEditStatusTabAction(Request $request)
    {
        $data = $request->all();
        return $this->contractCategoryRepo->submitEditStatusTabAction($data);
    }
}

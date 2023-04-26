<?php

namespace Modules\Kpi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kpi\Repositories\Criteria\KpiCriteriaRepoInterface;

/**
 * class KpiController
 * @author HaoNMN
 * @since Jun 2022
 */
class KpiController extends Controller
{
    protected $repo;


    public function __construct(KpiCriteriaRepoInterface $kpiCriteriaRepoInterface)
    {
        $this->repo = $kpiCriteriaRepoInterface;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexAction()
    {
        /**
         * $criteriaUnit: lấy danh sách đơn vị tính cho những tiêu chí
         * $data: danh sách tất cả tiêu chí trong hệ thống 
         */
        $criteriaUnit = $this->repo->listUnit();
        $data         = $this->repo->listAction();
        return view('kpi::criteria.index', [
            'data'         => $data,
            'criteriaUnit' => $criteriaUnit,
        ]);
    }

    /**
     * Get a listing of the resource.
     * @return Response
     */
    public function listAction(Request $request)
    {
        $data = $this->repo->listAction($request->all());
        return view('kpi::criteria.components.list', [
            'data'         => $data,
            'page'         => $request->page
        ]);
    }

    /**
     * Save the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function submitAction(Request $request)
    {
        return $this->repo->save($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function editAction($id)
    {
        return view('kpi::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request)
    {
        $this->repo->update($request->all());
        return response()->json([
            'error' => 0,
            'message' => __('Cập nhật thành công')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function removeAction($id)
    {
        $this->repo->destroy($id);
        return response()->json([
            'error' => 0,
            'message' => __('Xóa thành công')
        ]);
    }

    /**
     * Lấy list pipeline & hành trình của tiêu chí lead quan tâm
     * @return Response
     */
    public function getLeadAction(Request $request)
    {
        $param = $request->all();
        return $this->repo->getLeadOption($param);
    }
}

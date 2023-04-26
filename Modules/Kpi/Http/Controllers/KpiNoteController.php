<?php

namespace Modules\Kpi\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kpi\Models\KpiNoteTable;
use Modules\Kpi\Repositories\Note\KpiNoteRepoInterface;
use Carbon\Carbon;

class KpiNoteController extends Controller
{
    protected $repo;


    public function __construct(KpiNoteRepoInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Trang chủ phiếu giao kpi
     * @return Response
     */
    public function indexAction()
    {
        $department = $this->repo->getDepartment(null);
        $data = $this->repo->list();
        return view('kpi::notes.index', [
            'data' => $data,
            'DEPARTMENT_LIST' => $department
        ]);
    }

    /**
     * Danh sách phiếu giao kpi
     * @return Response
     */
    public function listAction(Request $request)
    {
        $data = $this->repo->list($request->all());
        return view('kpi::notes.components.list', [
            'data' => $data,
            'page' => $request->page
        ]);
    }

    /**
     * Trang thêm phiếu giao
     * @return Response
     */
    public function addAction()
    {
        $branch     = $this->repo->getBranch();
        $department = $this->repo->getDepartment(null);
        $team       = $this->repo->getTeam(null);
        $staff      = $this->repo->getStaff(null);
        $criteria   = $this->repo->getCriteria(null);

        return view('kpi::notes.add', [
            'BRANCH_LIST'     => $branch,
            'DEPARTMENT_LIST' => $department,
            'TEAM_LIST'       => $team,
            'STAFF_LIST'      => $staff,
            'CRITERIA_LIST'   => $criteria
        ]);
    }

    /**
     * Lưu phiếu giao
     * @param Request
     * @return true
     */
    public function submitAction(Request $request)
    {
        $data = $request->all();

        try {
            return $this->repo->save($data);
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Chỉnh sửa phiếu giao
     */
    public function editAction(Request $request)
    {
        $id         = $request->id;
        $branch     = $this->repo->getBranch();
        $department = $this->repo->getDepartment(null);
        $team       = $this->repo->getTeam(null);
        $staff      = $this->repo->getStaff(null);
        $criteria   = $this->repo->getCriteria(null);

        $detailData = $this->repo->detail($id);
        return view('kpi::notes.edit', [
            'BRANCH_LIST'     => $branch,
            'DEPARTMENT_LIST' => $department,
            'TEAM_LIST'       => $team,
            'STAFF_LIST'      => $staff,
            'CRITERIA_LIST'   => $criteria,
            'DETAIL_DATA'     => $detailData
        ]);
    }

    /**
     * Lưu chỉnh sửa
     * @param $request
     */
    public function updateAction(Request $request)
    {
        $data = $request->all();
        return $this->repo->update($data);
    }

    /**
     * Lấy data bảng danh sách tiêu chí cho nhân viên
     * @param $request
     */
    public function listCurrentCriteriaAction(Request $request)
    {
        $id = $request->id;
        return $this->repo->listCriteriaTable($id);
    }

    /**
     * Chi tiết phiếu giao
     * @param Request
     * @return true
     */
    public function detailAction(Request $request)
    {
        $id         = $request->id;
        $branch     = $this->repo->getBranch();
        $department = $this->repo->getDepartment(null);
        $team       = $this->repo->getTeam(null);
        $staff      = $this->repo->getStaff(null);

        $detailData = $this->repo->detail($id);
        return view('kpi::notes.detail', [
            'BRANCH_LIST'     => $branch,
            'DEPARTMENT_LIST' => $department,
            'TEAM_LIST'       => $team,
            'STAFF_LIST'      => $staff,
            'DETAIL_DATA'     => $detailData
        ]);
    }

    /**
     * Xóa phiếu giao
     * @param int $id
     * @return Response
     */
    public function removeAction($id)
    {
        return $this->repo->remove($id);
    }

    /**
     * Lấy danh sách phòng ban theo chi nhánh
     */
    public function listDepartmentAction(Request $request)
    {
        $branchId = $request->branch_id;
        if (empty($branchId)) {
            $branchId = null;
        }
        return $this->repo->getDepartment($branchId);
    }

    /**
     * Lấy danh sách nhóm theo phòng ban
     */
    public function listTeamAction(Request $request)
    {
        $departmentId = $request->department_id;
        if (empty($departmentId)) {
            $departmentId = null;
        }
        return $this->repo->getTeam($departmentId);
    }

    /**
     * Lấy danh sách nhân viên theo chi nhánh/ phòng ban/ nhóm
     */
    public function listStaffAction(Request $request)
    {
        $param = $request->all();
        if (empty($param)) {
            $param = null;
        }
        return $this->repo->getStaff($param);
    }

    /**
     * Lấy danh sách tiêu chí
     */
    public function listCriteriaAction(Request $request)
    {
        $param = $request->all();
        if (empty($param)) {
            $param = null;
        }
        return $this->repo->getCriteria($param);
    }

    /**
     * Thêm record chỉ số kpi thực tế cho tiêu chí custom vào bảng calculate_kpi
     * @param $data
     * @return Response
     */
    public function addKpiCalculateAction(Request $request)
    {
        $this->repo->addKpiCalculate($request->all());
        return [
            'error'   => 0,
            'message' => __('Thêm thành công')
        ];
    }
}

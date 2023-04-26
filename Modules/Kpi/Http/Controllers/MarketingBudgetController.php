<?php

namespace Modules\Kpi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Kpi\Repositories\BudgetMarketing\BudgetMarketingRepoInterface;

class MarketingBudgetController extends Controller
{
    protected $repo;


    public function __construct(BudgetMarketingRepoInterface $budgetMarketingRepoInterface)
    {
        $this->repo = $budgetMarketingRepoInterface;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexMonthAction()
    {
        $department = $this->repo->getDepartment(null);
        $team       = $this->repo->getTeam(null);
        $data = $this->repo->list(null, 0);
        return view('kpi::budget-marketing.index-month', [
            'data'            => $data,
            'DEPARTMENT_LIST' => $department,
            'TEAM_LIST'       => $team
        ]);
    }

    public function indexDayAction()
    {
        $department = $this->repo->getDepartment(null);
        $team       = $this->repo->getTeam(null);
        $data = $this->repo->list(null, 1);
        return view('kpi::budget-marketing.index-day', [
            'data'            => $data,
            'DEPARTMENT_LIST' => $department,
            'TEAM_LIST'       => $team
        ]);
    }

    public function listMonthAction(Request $request)
    {
        $param = $request->all();
        if (isset ($param['effect_time'])) {
            $param['effect_time'] = date("Y-m-d",strtotime($param['effect_time']));
        }
        $data = $this->repo->list($param, 0);
        return view('kpi::budget-marketing.components.list-month', [
            'data' => $data,
            'page' => $request->page
        ]);
    }

    public function listDayAction(Request $request)
    {
        $param = $request->all();
        if (isset ($param['effect_time'])) {
            $param['effect_time'] = date("Y-m-d",strtotime($param['effect_time']));
        }
        $data = $this->repo->list($param, 1);
        return view('kpi::budget-marketing.components.list-day', [
            'data' => $data,
            'page' => $request->page
        ]);
    }

    public function submitAction(Request $request) 
    {
        $data = $request->all();
        return $this->repo->add($data);
    }

    public function submitDayAction(Request $request)
    {
        $data = $request->all();
        return $this->repo->addDay($data);
    }

    public function updateMonthAction(Request $request)
    {
        $data = $request->all();
        return $this->repo->update($data);
    }

    public function updateDayAction(Request $request)
    {
        $data = $request->all();
        return $this->repo->updateByDay($data);
    }

    public function removeAction($id)
    {
        return $this->repo->remove($id);
    }
}

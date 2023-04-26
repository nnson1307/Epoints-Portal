<?php

namespace Modules\CustomerLead\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CustomerLead\Repositories\Pipeline\PipelineRepoInterface;
use Modules\CustomerLead\Http\Requests\Pipeline\StoreRequest;
use Modules\CustomerLead\Http\Requests\Pipeline\UpdateRequest;

class PipelineController extends Controller
{
    protected $pipeline;

    public function __construct (PipelineRepoInterface $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * List pipeline
     */
    public function index()
    {
        $data = $this->pipeline->list();
        return view('customer-lead::pipeline.index', [
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
     * Ajax filter, phân trang ds pipeline
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
        $data = $this->pipeline->list($filter);

        return view('customer-lead::pipeline.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View create pipeline
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function create()
    {
        $listCategory = $this->pipeline->getListCategory();
        $listStaff = $this->pipeline->getOptionStaff();
        return view('customer-lead::pipeline.create', [
            'listCategory' => $listCategory,
            'listStaff' => $listStaff
        ]);
    }

    /**
     * Store pipeline
     *
     * @param Request $request
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        return $this->pipeline->store($data);
    }

    /**
     * View edit pipeline
     * @param $pipelineId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function edit($pipelineId)
    {
        $data = $this->pipeline->getDetail($pipelineId);
        // danh sach journey cua pipeline mac dinh
        $list = $this->pipeline->getListJourneyDefault($data['pipeline_category_code']);
        $total = count($list);
        $listCategory = $this->pipeline->getListCategory();
        $listJourney = $this->pipeline->getListJourney($pipelineId);
        $listStaff = $this->pipeline->getOptionStaff();
        return view('customer-lead::pipeline.edit',[
            'data' => $data,
            'listCategory' => $listCategory,
            'listJourney' => $listJourney,
            'listStaff' => $listStaff,
            'total' => $total
        ]);
    }

    /**
     * update pipeline
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->all();
        return $this->pipeline->update($data);
    }

    /**
     * Chi tiet pipeline
     *
     * @param $pipelineId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function detail($pipelineId)
    {
        $data = $this->pipeline->getDetail($pipelineId);
        $listJourney = $this->pipeline->getListJourney($pipelineId);
        $listStaff = $this->pipeline->getOptionStaff();
        return view('customer-lead::pipeline.detail',[
            'data' => $data,
            'listJourney' => $listJourney,
            'listStaff' => $listStaff,
        ]);
    }

    /**
     * Xoa pipeline
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $pipelineId = $request->pipeline_id;
        return $this->pipeline->destroy($pipelineId);
    }

    /**
     * Thiet lap pipeline mac dinh
     *
     * @param Request $request
     * @return mixed
     */
    public function setDefaultPipeline(Request $request)
    {
        $pipelineId = $request->pipeline_id;
        $pipelineCategoryCode = $request->pipeline_category_code;
        return $this->pipeline->setDefaultPipeline($pipelineId, $pipelineCategoryCode);
    }

    /**
     * Kiểm tra hành trình đã được sử dụng trong customer lead hay chưa
     *
     * @param Request $request
     * @return mixed
     */
    public function checkJourneyBeUsed(Request $request)
    {
        $pipelineCode = $request->pipeline_code;
        return $this->pipeline->checkJourneyBeUsed($pipelineCode);
    }

    /**
     * lấy danh sách hành trình mặc định theo pipeline category code
     *
     * @param Request $request
     * @return mixed
     */
    public function getListJourneyDefault(Request $request)
    {
        $pipelineCategoryCode = $request->pipeline_category_code;
        $list = $this->pipeline->getListJourneyDefault($pipelineCategoryCode);

        $view = \View::make('customer-lead::pipeline.list-journey-default', [
            'listJourneyDefault' => $list
        ])->render();
        return response()->json([
            'url' => $view
        ]);
    }
}
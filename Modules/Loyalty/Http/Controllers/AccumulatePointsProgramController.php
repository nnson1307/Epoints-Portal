<?php

namespace Modules\Loyalty\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

use Modules\Loyalty\Requests\Membership\StoreRequest;
use Modules\Loyalty\Requests\Membership\ConfigNotificationRequest;
use Modules\Loyalty\Repositories\AccumulatePointsProgram\AccumulatePointsProgramRepositoryInterface;


class AccumulatePointsProgramController extends  Controller
{
    protected $accumulate;

    public function __construct(
        AccumulatePointsProgramRepositoryInterface $accumulate
    ) {
        $this->accumulate = $accumulate;
    }

    // view danh sách tích điểm
    public function index()
    {
        return view('loyalty::accumulate-points-program.index');
    }


    /**
     * load all danh sách tích điểm
     * @param $request
     * @return mixed
     */
    public function loadAll(Request $request)
    {
        $filter = $request->all();
        $data = $this->accumulate->getList($filter);
        $view = view('loyalty::accumulate-points-program.list.index', ['data' => $data['data']])->render();
        return response()->json(['view' => $view]);
    }

    /**
     * view tạo chương trình tích điểm khảo sát
     * @param $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $listSurvey = $this->accumulate->getListSurvey();
        $listRank = $this->accumulate->getAllRank();
        return view('loyalty::accumulate-points-program.create', ['listSurvey' => $listSurvey, 'listRank' => $listRank]);
    }

    /**
     * tạo chương trình tích điểm khảo sát
     * @param $request
     * @return mixed
     */

    public function store(StoreRequest $request)
    {
        $params = $request->all();
        $result = $this->accumulate->store($params);
        return response()->json($result);
    }

    /**
     * hiển thị item chương trình tích điểm
     * @param $idProgram
     * @return mixed
     */

    public function show($idProgram)
    {
        $itemProgram = $this->accumulate->findItem($idProgram);
        $listSurvey = $this->accumulate->getListSurvey();
        $listRank = $this->accumulate->getAllRank();
        return view('loyalty::accumulate-points-program.show', [
            'listSurvey' => $listSurvey,
            'listRank' => $listRank,
            'itemProgram' => $itemProgram
        ]);
    }

    /**
     * view edit chương trình tích điểm
     * @param $idProgram
     * @return mixed
     */

    public function edit($idProgram)
    {
        $itemProgram = $this->accumulate->findItem($idProgram);
        $listSurvey = $this->accumulate->getListSurvey();
        $listRank = $this->accumulate->getAllRank();
        return view('loyalty::accumulate-points-program.edit', [
            'listSurvey' => $listSurvey,
            'listRank' => $listRank,
            'itemProgram' => $itemProgram
        ]);
    }

    /**
     * update chương trình tích điểm 
     * @param $request 
     * @return mixed
     */

    public function update(StoreRequest $request)
    {
        $params = $request->all();
        $result = $this->accumulate->update($params);
        return response()->json($result);
    }

    /**
     * showModal notfication
     * @param $request
     * @return mixed
     */
    public function showModalNotification(Request $request)
    {
        $data = $this->accumulate->showConfigNotification();
        $view = view('loyalty::accumulate-points-program.modal.modal_config_notification', ['data' => $data])->render();
        return response()->json(['view' => $view]);
    }

    public function settingNotification(ConfigNotificationRequest $request)
    {
        $params = $request->all();
        $result = $this->accumulate->updateSettingNotification($params);
        return response()->json($result);
    }

    /**
     * show modal destroy loyalty
     * @param $request
     * @return mixed
     */

    public function showModalDestroy(Request $request)
    {
        $idLoyalty  = $request->id;
        $result = $this->accumulate->showItemDestroy($idLoyalty);
        return response()->json($result);
    }

    /**
     * destroy loyalty 
     * @param $request
     * @return mixed
     */

    public function destroy(Request $request)
    {
        $idLoyalty = $request->id;
        $result = $this->accumulate->destroy($idLoyalty);
        return response()->json($result);
    }
}

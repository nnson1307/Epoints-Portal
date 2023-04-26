<?php
/**
 * Created by PhpStorm
 * User: Huniel
 * Date: 4/26/2022
 * Time: 5:37 PM
 */

namespace Modules\People\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\People\Repositories\People\PeopleRepoIf;
use Modules\People\Repositories\PeopleReport\PeopleReportInterface;

class PeopleReportController extends Controller
{
    protected $peopleReportRepo;
    protected $people;

    public function __construct(PeopleReportInterface $peopleReport,PeopleRepoIf $people){
        $this->peopleReportRepo = $peopleReport;
        $this->people = $people;
    }

    /**
     * Giao diện chính report
     */
    public function index(Request $request){

        $params = $request->all();
        $params['year'] = isset($params['year']) ? $params['year'] : Carbon::now()->year;
        $params['people_object_group_id'] = isset($params['people_object_group_id']) ? $params['people_object_group_id'] : null;
        $params['page'] = $request->input('page') ? $request->input('page') : 1;

        $data = $this->peopleReportRepo->list($params);

        $data['year'] = $params['year'];
        $data['page'] = $params['page'];
        $data['people_object_group_id'] = $params['people_object_group_id'];

        $default = [''=>'Chọn nhóm đối tượng'];
        $data['people_object_group_option'] = $default+$this->people->objectGroups();



        return view('People::report.index', $data);
    }

    public function export(Request $request){
        $params = $request->all();
        $params['year'] = isset($params['year']) ? $params['year'] : Carbon::now()->year;
        $params['people_object_group_id'] = isset($params['people_object_group_id']) ? $params['people_object_group_id'] : null;
        $params['type'] = isset($params['type']) ? $params['type'] : '';

        return $this->peopleReportRepo->export($params);
    }



}

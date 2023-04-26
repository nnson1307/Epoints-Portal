<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Validator;
use Modules\Kpi\Repositories\Report\ReportRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffDays\TimeOffDaysRepositoryInterface;
use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepositoryInterface;

class Reportcontroller extends Controller
{

    protected $repo;
    protected $repoTimeOffDays;
    protected $repoTimeOffType;

    public function __construct(ReportRepoInterface $repo,
        TimeOffTypeRepositoryInterface $repoTimeOffType,
        TimeOffDaysRepositoryInterface $repoTimeOffDays)
    {
        $this->repo = $repo;
        $this->repoTimeOffDays = $repoTimeOffDays;
        $this->repoTimeOffType = $repoTimeOffType;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        
        $listBranch = $this->repo->getlistBranch();
        
        return view('timeoffdays::report.index', [
                'listBranch' => $listBranch ?? [],
            ]
        );
    }

    /**
     * reportByTypeAjax
     * @return Response
     */
    public function reportByTypeAjax(Request $request)
    {
        $params = $request->all();

        $result = $this->repoTimeOffDays->reportByType($params);
        $tmp = [];
        if($result){
            foreach($result as $key=> $item){
                $tmp[$key] = [$item['time_off_type_name'], $item['total'], true, true];
            }
        }

        if($result){
            return response()->json(['status' => 1, 'data' => $tmp  ]);
        }
        
        return response()->json(['status' => 0]);

    }

     /**
     * reportByPreciousAjax
     * @return Response
     */
    public function reportByPreciousAjax(Request $request)
    {
        $params = $request->all();

        $precious = $params['precious'] ?? 0;
        if($precious == 1){
            $start = 1;
            $end = 3;
        }
        if($precious == 2){
            $start = 4;
            $end = 6;
        }
        if($precious == 3){
            $start = 7;
            $end = 9;
        }
        if($precious == 4){
            $start = 10;
            $end = 12;
        }
    
        $result = $this->repoTimeOffDays->reportByPrecious($params);
        
        $categories = $this->repoTimeOffType->getAll();

        $collection = collect($result);
        $tmp = [];
        if($categories){
           
            for ($i= $start; $i <= $end ; $i++) { 
                $tmpTotal = [];
                foreach($categories as $key=> $item){
                    $categoriesTmp[$key] = $item['time_off_type_name'];

                    $value = $collection->where("time_off_type_name", "=", $item["time_off_type_name"])->first();
                
                    if($value){
                        $tmpTotal[] = $value['total'];

                    }else{
                        $tmpTotal[] = 0;
                    }  
                }
                $tmp[] = [
                    'name' => 'Tháng '. $i,
                    'data' => $tmpTotal
                ];
            }
            

        }

     
        if($result){
            return response()->json([
                'status' => 1, 
                'data' => $tmp, 
                'categories' => array_values($categoriesTmp)  
            ]);
        }
        
        return response()->json(['status' => 0]);

    }


     /**
     * reportByTopTenAjax
     * @return Response
     */
    public function reportByTopTenAjax(Request $request)
    {
        $params = $request->all();

        $result = $this->repoTimeOffDays->reportByTopTen($params);

        $tmp = [];
        $categoriesTmp = [];
        if($result){
            foreach($result as $key=> $item){
                $categoriesTmp[$key] = $item['full_name'];
                $tmp[$key] = $item['total'];
            }
        }
      
        if($result){
            return response()->json([
                'status' => 1, 
                'data' =>  array_values($tmp), 
                'categories' => array_values($categoriesTmp)
            ]);
        }
        
        return response()->json([
            'status' => 0, 
            'data' =>  [0,0, 0,0, 0,0, 0,0 ,0,0, 0,0], 
            'categories' => ['','', '','', '','', '','' ,'','', '','']
        ]);

    }
}

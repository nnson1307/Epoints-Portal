<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/25/2018
 * Time: 10:14 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\WardTable;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\District\DistrictRepositoryInterface;
use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;

class WarehouseController extends Controller
{
    protected $warehouse;
    protected $branch;
    protected $province;
    protected $district;

    public function __construct(
        WarehouseRepositoryInterface $warehouses,
        BranchRepositoryInterface $branches,
        ProvinceRepositoryInterface $province,
        DistrictRepositoryInterface $district
    )
    {
        $this->warehouse = $warehouses;
        $this->branch = $branches;
        $this->province = $province;
        $this->district = $district;
    }

    //View index
    public function indexAction()
    {
        $ware = $this->warehouse->list();
        $getBranch = $this->branch->getBranch();
        $province = $this->province->getOptionProvince();

        return view('admin::warehouse.index', [
            'LIST' => $ware,
            'FILTER' => $this->filters(),
            'branch' => $getBranch,
            'province' => $province,
            'district' => []
        ]);
    }

    //Filter
    protected function filters()
    {
        return [

        ];
    }

    //function view list
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived', 'search']);
        $wareList = $this->warehouse->list($filter);
        return view('admin::warehouse.list', ['LIST' => $wareList, 'page' => $filter['page']]);
    }

    //function submit add
    public function submitAddAction(Request $request)
    {
        $name = $request->name;
        $test = $this->warehouse->testName(str_slug($name), 0);
        $checkIsRetail = $this->warehouse->checkIsFirstWarehouse($request->branch_id);
        $isRetail = $request->isRetail;



        if ($checkIsRetail == null) {
            $isRetail = 1;
        }
        if ($test == null) {
            $data = [
                'name'=>$request->name,
                'branch_id'=>$request->branch_id,
                'address'=>$request->address,
                'description'=>$request->description,
                'province_id'=>$request->province,
                'district_id'=>$request->district,
                'ward_id'=>$request->ward,
                'phone' => $request->phone,
                'is_retail'=>$isRetail,
                'slug'=>str_slug($request->name),
                'created_by'=>Auth::id()
            ];
            $this->warehouse->add($data);
            return response()->json(['status' => '', 'close' => $request->close]);
        } else {
            return response()->json(['status' => 'Tên kho đã tồn tại']);
        }

    }

    //function remove warehouse
    public function removeAction($id)
    {
        $this->warehouse->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //function get item edit
    public function editAction(Request $request)
    {
        $id = $request->id;
        $item = $this->warehouse->getItem($id);

        $mWard = app()->get(WardTable::class);

        $provinceOption = $this->province->getOptionProvince();
        $districtOption = $this->district->getOptionDistrict([
            'id_province' => $item['province_id']
        ]);

        $wardOption = null;
        if (isset($item->ward_id)){
            $wardOption = $mWard->getOptionWardDetail($item->ward_id);
        }

        //get info district
        $getDistrict = $this->district->getItem($item->district_id);

        $data = [
            'warehouse_id' => $item->warehouse_id,
            'name' => $item->name,
            'branch_id' => $item->branch_id,
            'address' => $item->address,
            'description' => $item->description,
            'provinceOption' => $provinceOption,
            'districtOption' => $districtOption,
            'province_id' => $item->province_id,
            'district_id' => $item->district_id,
            'ward_id' => $item->ward_id,
            'phone' => $item->phone,
            'is_retail' => $item->is_retail,
            'district_name' => $getDistrict['name'],
            'ward_name' => $wardOption != null ? $wardOption['name'] : '',
        ];
        return response()->json($data);
    }

    //function submit edit
    public function submitEditAction(Request $request)
    {
        $param = $request->param;
        $id = $request->id;
        $name = $request->name;
        $test = $this->warehouse->testName(str_slug($name), $id);
        if ($param == 0) {
            if ($test == null) {
                $data = [
                    'name' => $name,
                    'branch_id' => $request->branch_id,
                    'address' => $request->address,
                    'description' => $request->description,
                    'province_id' => $request->province,
                    'district_id' => $request->district,
                    'ward_id' => $request->ward,
                    'phone' => $request->phone,
                    'is_retail' => $request->isRetail
                ];

                $data['updated_by'] = Auth::id();
                $this->warehouse->edit($data, $id);
                return response()->json(['status' => '']);
            } else {
                return response()->json(['status' => 'Tên kho đã tồn tại']);
            }
        } else {
            $this->warehouse->changeIsRetailAction($request->branch_id);
            $this->warehouse->edit(['is_retail' => $request->isRetail], $id);
            return response()->json(['status' => '']);
        }
    }

    public function getDistrictAction(Request $request)
    {
        $id_province = $request->id_province;
        $district = $this->district->getOptionDistrict(['id_province' => $id_province]);
        $data = [];
        foreach ($district as $key => $value) {
            $data[] = [
                'id' => $value['id'],
                'name' => $value['name'],
                'type' => $value['type']
            ];
        }
        return response()->json([
            'district' => $data
        ]);
    }

    public function checkIsRetailAction(Request $request)
    {
        $branch = $request->branch;
        $isRetail = $request->isRetail;
        $id = $request->id;
        if ($isRetail == 0) {
            return response()->json(['error' => 0]);
        } else {
            $warehouseByBranch = $this->warehouse->checkIsRetail($branch, $id);
            if (count($warehouseByBranch) > 0) {
                return response()->json(['error' => 1]);
            } else {
                return response()->json(['error' => 0]);
            }
        }
    }

    public function changeIsRetailAction(Request $request)
    {
        $this->warehouse->changeIsRetailAction($request->branch_id);
        $data = [
            'name' => $request->name,
            'branch_id' => $request->branch_id,
            'address' => $request->address,
            'description' => $request->description,
            'province_id' => $request->province,
            'district_id' => $request->province,
            'created_by' => Auth::user()->staff_id,
            'is_retail' => 1,

        ];
        $this->warehouse->add($data);
        return response()->json(['error' => 0, 'close' => $request->close]);
    }

    /**
     * Tạo cửa hàng ở GHN
     */
    public function createStoreGHN(){
        $data = $this->warehouse->createStoreGHN();
        return response()->json($data);
    }
}
//
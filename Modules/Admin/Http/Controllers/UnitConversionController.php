<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/26/2018
 * Time: 10:49 AM
 */

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;
use Modules\Admin\Repositories\UnitConversion\UnitConversionRepositoryInterface;

class UnitConversionController extends Controller
{
    protected $unit_conversion;
    protected $unit;
    public function __construct(UnitConversionRepositoryInterface $unit_conversions, UnitRepositoryInterface $units)
    {
        $this->unit_conversion=$unit_conversions;
        $this->unit=$units;
    }
    //View index
    public function indexAction()
    {
        $list = $this->unit_conversion->list();
        $getUnit = $this->unit->getUnitOption();
        return view('admin::unit-conversion.index', [
            'LIST' => $list,
            'FILTER' => $this->filters(),
            'unit' => $getUnit
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
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived','units.name']);
        $oList = $this->unit_conversion->list($filter);
        return view('admin::unit-conversion.list', [
            'LIST' => $oList,
            'page'=>$filter['page']
        ]);
    }
    //function add
    public function submitAddAction(Request $request)
    {
        $data=$this->validate($request,[
           'conversion_rate'=>'required',
            'unit_id'=>'required',
            'unit_standard'=>'required'
        ],[
            'conversion_rate.required'=>__("Hãy nhập tỉ lệ chuyển đổi"),
            'unit_id.required'=>__('Đơn vị tính không được trống'),
            'unit_standard.required'=>__('Tiêu chuẩn không được trống')
        ]);
//        $data['created_by']=Auth::id();
        $this->unit_conversion->add($data);
        return response()->json(['close'=>$request->close]);
    }
    //function xoa
    public function removeAction($id)
    {
        $this->unit_conversion->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }
    //function edit
    public function editAction(Request $request)
    {
        $id = $request->id;
        $item = $this->unit_conversion->getItem($id);

        $data = [
            'unit_conversion_id'=>$item->unit_conversion_id,
            'conversion_rate' => $item->conversion_rate,
            'unit_id'=>$item->unit_id,
            'unit_standard'=>$item->unit_standard,

        ];
//        $data['updated_by']=Auth::id();
        return response()->json($data);
    }
    //function submit edit
    public function submitEditAction(Request $request)
    {
        $id = $request->id;

        $data = [
            'conversion_rate' => $request->conversion_rate,
            'unit_id'=>$request->unit_id,
            'unit_standard'=>$request->unit_standard,
        ];
        $this->unit_conversion->edit($data, $id);

        return response()->json();
    }
}
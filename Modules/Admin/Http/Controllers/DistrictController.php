<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 5:35 PM
 */

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Modules\Admin\Models\DistrictTable;
use Modules\Admin\Repositories\District\DistrictRepositoryInterface;
use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;

class DistrictController extends Controller
{
    protected $district;

    public function __construct(DistrictRepositoryInterface $districtRepository)
    {
        $this->district=$districtRepository;

    }

    //function change province
    public function changeProvinceAction(Request $request)
    {
        $id=(int)$request->id_province;
        $oOptionDistrict=$this->district->getOptionDistrict(['id_province' => $id]);

        return response()->json($oOptionDistrict);

    }

}
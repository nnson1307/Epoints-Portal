<?php


namespace Modules\BookingWeb\Http\Controllers;


use Illuminate\Http\Request;
use Modules\BookingWeb\Repositories\District\DistrictRepositoryInterface;

class AddressController extends Controller
{
    protected $district;

    public function __construct(DistrictRepositoryInterface $district)
    {
        $this->district=$district;
    }

    public function getDistrictAction(Request $request)
    {
        $province_id = $request->province_id;
        $list_district = $this->district->getDistrictOption($province_id);

        return response()->json($list_district);
    }
}
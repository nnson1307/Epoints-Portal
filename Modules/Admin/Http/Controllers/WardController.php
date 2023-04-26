<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 5:36 PM
 */

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Modules\Admin\Models\WardTable;
use Modules\Admin\Repositories\Ward\WardRepositoryInterface;

class WardController extends Controller
{
    protected $ward;

    public function __construct(WardRepositoryInterface $wardRepository)
    {
        $this->ward=$wardRepository;
    }

    //function change district
    public function changeDistrictAction(Request $request)
    {
        $id=(int)$request->id_district;
        $oOptionWard=$this->ward->getOptionWard($id);
        return response()->json($oOptionWard);

    }
}
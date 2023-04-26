<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 12:02 PM
 */

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;

use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;

class ProvinceController extends Controller
{

    protected $province;
    protected $district;

    public function __construct(ProvinceRepositoryInterface $provinceRepository )
    {

        $this->province=$provinceRepository;

    }




}
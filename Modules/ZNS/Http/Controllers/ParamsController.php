<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ZNS\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Modules\ZNS\Repositories\Params\ParamsRepositoryInterface;


class ParamsController extends Controller
{
    protected $params;
    public function __construct(ParamsRepositoryInterface $params)
    {
        $this->params = $params;
    }

    public function list(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search','status','created_at']);
        return view('zns::params.index', $this->params->list($filters));
    }

    public function edit(Request $request)
    {
        $data = $request->all();
        return [
            'status' => 1,
            'html' => view('zns::params.edit', [
                'item' => $this->params->getItem($data['params_id'])
            ])->render()
        ];
    }
    public function editSubmit(Request $request)
    {
        $data = $request->only(['params_id','description']);
        return [
            'status' => $this->params->edit($data,$data['params_id'])
        ];
    }
    public function view()
    {
        return view('zns::params.view', []);
    }
}
<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 12/1/2021
 * Time: 11:10 AM
 * @author nhandt
 */


namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\CustomerLog\CustomerLogRepoInterface;

class CustomerLogController extends Controller
{
    protected $repo;
    public function __construct(CustomerLogRepoInterface $repo)
    {
        $this->repo = $repo;
    }

    public function indexAction(Request $request)
    {
        if(!isset($request->id)){
            return redirect()->route('admin.customer');
        }
        $id = $request->id;
        return view('admin::customer.customer-log.index', [
            'id' => $id
        ]);
    }
    public function listAction(Request $request)
    {
        $filter = $request->all();
        $data = $this->repo->list($filter);
        return view('admin::customer.customer-log.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    public function listLogUpdate(Request $request)
    {
        $data = $this->repo->listLogUpdate($request->all());

        return response()->json($data);
    }
}
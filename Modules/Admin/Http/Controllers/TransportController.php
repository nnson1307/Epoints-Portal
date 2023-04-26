<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/27/2018
 * Time: 9:57 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\Transport\TransportRepository;

class TransportController extends Controller
{
    protected $transport;

    public function __construct(TransportRepository $transports)
    {
        $this->transport = $transports;
    }

    //view index
    public function indexAction()
    {
        $list = $this->transport->list();
        return view('admin::transport.index', [
            'LIST' => $list,
            'FILTER' => $this->filters()
        ]);
    }

    //Filter
    protected function filters()
    {
        return [
//            'is_actived'=>[
//                'text'=>'Trạng thái',
//                'data'=>[
//                    ''=>'Tất cả',
//                    1=>'Đang hoạt động',
//                    0=>'Tạm đóng'
//                ]
//            ]
        ];
    }

    //function view list
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $tranList = $this->transport->list($filter);
        return view('admin::transport.list', ['LIST' => $tranList, 'page' => $filter['page']]);
    }

    //function add
    public function submitAddAction(Request $request)
    {
        $name = $request->transport_name;
        $test = $this->transport->testName(str_slug($name), 0);
        if ($test == null) {
            $data = [
                'transport_name'=>$request->transport_name,
                'slug'=>str_slug($request->transport_name),
//                'charge'=>$request->charge,
                'address'=>$request->address,
                'contact_name'=>$request->contact_name,
                'contact_phone'=>$request->contact_phone,
                'contact_title'=>$request->contact_title,
                'description'=>$request->description,
                'created_by'=>Auth::id()
            ];

            $this->transport->add($data);
            return response()->json(['status' => '', 'close' => $request->close]);
        } else {
            return response()->json(['status' => __('Tên đơn vị giao hàng đã tồn tại')]);
        }

    }

    //function remove
    public function removeAction($id)
    {
        $this->transport->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //function get item edit
    public function editAction(Request $request)
    {
        $id = $request->id;
        $item = $this->transport->getItem($id);
        $data = [
            'transport_id' => $item->transport_id,
            'transport_name' => $item->transport_name,
//            'charge' => number_format($item->charge),
            'description' => $item->description,
            'address' => $item->address,
            'contact_name' => $item->contact_name,
            'contact_phone' => $item->contact_phone,
            'contact_title' => $item->contact_title,
            'is_system' => $item->is_system,
            'transport_code' => $item->transport_code,
            'token' => $item->token,
        ];
        return response()->json($data);
    }

    //function submit edit
    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $name = $request->transport_name;
        $test = $this->transport->testName(str_slug($name), $id);
        if ($test == null) {
            $data = [
                'transport_name' => $name,
                'slug'=>str_slug($name),
//                'charge' => $request->charge,
                'description' => $request->description,
                'address' => $request->address,
                'contact_name' => $request->contact_name,
                'contact_phone' => $request->contact_phone,
                'contact_title' => $request->contact_title,
                'updated_by'=>Auth::id()
            ];

            if (isset($request['transport_code']) && $request['transport_code'] == 'ghn'){
                $data['token'] = $request->token;
            }

            $this->transport->edit($data, $id);
            return response()->json(['status' => '']);
        } else {
            return response()->json(['status' => __('Đơn vị giao hàng đã tồn tại')]);
        }
    }
}
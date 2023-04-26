<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\Ticket\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Ticket\Repositories\Alert\AlertRepositoryInterface;
use Modules\Ticket\Repositories\RoleQueue\RoleQueueRepositoryInterface;


class AlertController extends Controller
{
    protected $alert;
    protected $roleQueue;


    public function __construct(
        AlertRepositoryInterface $alert,
        RoleQueueRepositoryInterface $roleQueue
    )
    {
        $this->alert = $alert;
        $this->roleQueue = $roleQueue;
    }

    public function indexAction()
    {
        return view('ticket::alert.index', [
            'list' => $this->alert->getAll()->toArray(),
            'timeWarning' => $this->timeWarning(),
            'roleQueue' => $this->roleQueue->getName(),
        ]);
    }

    public function edit(Request $request)
    {
        if($request->post()){
            $post = $request->post();
            unset($post['_token']);
            foreach($post as $id => $column){
                $data = [
                    'time' => (int)$post[$id]['time'],
                    'time_2' => isset($post[$id]['time_2']) ? (int)$post[$id]['time_2']:'',
                    'time_3' => isset($post[$id]['time_2']) ? (int)$post[$id]['time_3']:'',
                    'ticket_role_queue_id' => $post[$id]['ticket_role_queue_id'],
                    'template' => $post[$id]['template'],
                    'is_noti' => isset($post[$id]['is_noti']) ? 1 : 0,
                    'is_email' => isset($post[$id]['is_email']) ? 1 : 0,
                ];                
                if($this->alert->edit($data,$id)){
                    // insert done
                    \Session::flash('message', __('Chỉnh sửa thông báo thành công')); 
                    \Session::flash('alert-class', 'success'); 
                }else{
                    // insert faild
                    \Session::flash('message', __('Chỉnh sửa thông báo thất bại')); 
                    \Session::flash('alert-class', 'warning'); 
                }

            }
        }
        return redirect()->route('ticket.alert');

    }

    protected function timeWarning()
    {
        return [
            15 => __('15 phút'),
            30 => __('30 phút'),
            45 => __('45 phút'),
            60 => __('60 phút'),
        ];
    }

}
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
use Modules\ZNS\Repositories\Config\ConfigRepositoryInterface;


class ConfigController extends Controller
{
    protected $config;
    public function __construct(ConfigRepositoryInterface $config)
    {
        $this->config = $config;
    }

    public function list(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search','is_active']);
        return view('zns::config.index', $this->config->list($filters));
    }

    public function editView(Request $request)
    {
        $params = $request->all();
        return $this->config->editView($params);
    }

    public function editSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:191',
            'zns_template_id' => 'required',
            'hint' => 'max:191',
        ]);
        $params = $request->all();
//        dd($params);
//        $check_send = $params['check_send'];
//        $value = $params['value'];
//        $data = [
//            $check_send,
//            $value
//        ];
        return $this->config->editSubmit($params,$params['id']);
    }

    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        return $this->config->changeStatusAction($change);
    }

    public function sendNotification(Request $request)
    {
        // $params = $request->only(['key','user_id','template_id']);
        $params = [
            'key' => "new_customer",
            'user_id' => 1,
            'obj_id' => 1,
        ];
        return $this->config->sendNotification($params['key'],$params['user_id'],$params['obj_id']);
    }
}
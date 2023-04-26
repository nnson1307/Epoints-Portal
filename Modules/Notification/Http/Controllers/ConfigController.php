<?php


namespace Modules\Notification\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Notification\Http\Requests\Config\UpdateRequest;
use Modules\Notification\Repositories\Config\ConfigRepoInterface;

class ConfigController extends Controller
{
    protected $config;

    public function __construct(
        ConfigRepoInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * View cấu hình thông báo
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request)
    {
        $param = $request->all();
        $data = $this->config->dataIndex();
        $data['tab'] = isset($param['tab']) ? $param['tab'] : '';
        return view('notification::config.index', $data);
    }

    /**
     * View chỉnh sửa cấu hình thông báo
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit(Request $request)
    {
        $data = $this->config->getInfo($request->key);

        return view("notification::config.edit", $data);
    }

    /**
     * Chỉnh sửa cấu hình thông báo
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->config->update($request->all());

        return $data;
    }

    /**
     * Thay đổi trạng thái
     *
     * @param Request $request
     * @return mixed
     */
    public function changeStatusAction(Request $request)
    {
        $data = $this->config->changeStatus($request->all());

        return $data;
    }

    /**
     * Upload hình ảnh
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAction(Request $request)
    {
        if ($request->file('file') != null) {
            $data = $this->config->uploadImage($request->all());

            return response()->json($data);
        }
    }

    /**
     * submit contract notify config
     *
     * @param Request $request
     * @return mixed
     */
    public function submitNotifyContract(Request $request)
    {
        $data = $this->config->submitNotifyContract($request->all());

        return $data;
    }
}
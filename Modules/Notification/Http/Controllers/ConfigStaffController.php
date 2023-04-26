<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Notification\Http\Requests\ConfigStaff\UpdateRequest;
use Modules\Notification\Repositories\ConfigStaff\ConfigStaffRepoInterface;

class ConfigStaffController extends Controller
{
    protected $configStaff;
    public function __construct(ConfigStaffRepoInterface $configStaff)
    {
        $this->configStaff = $configStaff;
    }

    /**
     * View cấu hình thông báo nhân viên
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->configStaff->dataIndex();
        return view('notification::config-staff.index', $data);
    }

    /**
     * View chỉnh sửa cấu hình thông báo nhân viên
     *
     * @param Request $request
     * @return array
     */
    public function edit(Request $request)
    {
        $data = $this->configStaff->dataEdit($request->key);
        return view("notification::config-staff.edit", $data);
    }

    /**
     * Chỉnh sửa cấu hình thông báo nhân viên
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->configStaff->update($request->all());
    }

    /**
     * Thay đổi trạng thái
     *
     * @param Request $request
     * @return mixed
     */
    public function changeStatusAction(Request $request)
    {
        return $this->configStaff->changeStatus($request->all());
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
            $data = $this->configStaff->uploadImage($request->all());
            return response()->json($data);
        }
    }
}
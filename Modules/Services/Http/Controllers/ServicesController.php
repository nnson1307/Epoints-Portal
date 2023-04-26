<?php
/**
 * ServicesController
 * LeDangSinh
 * Date: 3/28/2018
 */

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;

use Modules\Services\Repositories\Services\ServicesRepositoryInterface;
use Modules\Services\Repositories\ServiceTime\ServiceTimeRepositoryInterface;

class ServicesController extends Controller
{
    protected $service;
    protected $serviceTime;


    public function __construct(ServicesRepositoryInterface $services )
    {
        $this->service = $services;

    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'sv$is_active' => [
                'text' => 'Trạng thái:',
                'data' => [
                    '' => 'Tất cả',
                    1 => 'Đang hoạt động',
                    0 => 'Tạm ngưng'
                ]
            ]
        ];
    }

    /**
     * return trang chủ services
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction(Request $request)
    {
        $serviceList = $this->service->list();
        return view('service::services.index', [
            'LIST' => $serviceList,
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Lấy danh sách services
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'sv$is_active']);
        $serviceList = $this->service->list($filters);

        return view('service::services.list', ['LIST' => $serviceList]);
    }


    public function addAction()
    {
        $optionServiceTime = $this->serviceTime->getOptionServiceTime();
        return view('service::services.add', array(
            'optionServiceTime' => $optionServiceTime
        ));
    }

    public function submitAddAction(Request $request)
    {
        $data = $this->validate($request, [
            'service_code' => 'required|unique:services,service_code',
            'service_name' => 'required',
            'service_time_id' => 'required',
            'detail' => 'string',
            'is_active' => 'integer'
        ],
            [
                'service_code.required' => 'Vui lòng nhập mã dịch vụ',
                'service_code.unique' => 'Mã dịch vụ đã tồn tại',
                'service_name.required' => 'Vui lòng nhập tên dịch vụ',
                'service_time_id.required' => 'Vui lòng chọn thời gian sử dụng'

            ]);
        $data["services_image"] = $this->transferTempFileToServicesFile($request->input("services_image"));
        $oServices = $this->service->add($data);

        if ($oServices) {
            $request->session()->flash('status', 'Tạo dịch vụ thành công');
        }
        return redirect()->route('services');
    }


    public function editAction($id)
    {
        $item = $this->service->getItem($id);
        $optionServiceTime = $this->serviceTime->getOptionServiceTime();
        return view('service::services.edit', array('optionServiceTime' => $optionServiceTime), compact('item'));
    }

    public function submitEditAction(Request $request, $id)
    {
        $data = $this->validate($request, [
            'service_code' => 'required|unique:services,service_code,' . $id . ",service_id",
            'service_name' => 'required',
            'service_time_id' => 'required',
            'is_active' => 'integer'
        ],
            [
                'service_code.required' => 'Vui lòng nhập mã dịch vụ',
                'service_code.unique' => 'Mã dịch vụ đã tồn tại',
                'service_name.required' => 'Vui lòng nhập tên dịch vụ',
                'service_time_id.required' => 'Vui lòng chọn thời gian sử dụng'

            ]);
        $oServices = $this->service->edit($data, $id);
        if ($oServices) {
            $request->session()->flash('status', 'Sửa dịch vụ thành công');
        }
        return redirect()->route('services');
    }
    public function exportExcelAction(Request $request)
    {
        $params = $request->except('_token');
        foreach($params as $key=>$value){
            $oExplode=explode(",",$value);
            $column[]=$oExplode[0];
            $title[]=$oExplode[1];
        }
   $this->service->exportExcel($column,$title);
    }
    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_active'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->service->edit($data, $params['id']);
        return response()->json();
    }

    /**
     * Xóa product label
     * @param number $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAction($id)
    {
        $this->service->remove($id);
    }


    public function uploadsAction(Request $request)
    {
        $this->validate($request,
            [
                "services_image" => "mimes:jpeg,jpg,png,gif|max:10000",
            ], [
                "services_image.mimes" => ":attribute này không phải là file hình",
                "services_image.max" => ":attribute quá lớn"
            ]);
        $file = $this->uploadsFileToTemp($request->file("services_image"));
        return response()->json(["file" => $file, "success" => "1"]);
    }

    /**
     * Function upload file vào bộ nhớ tạm
     * @param $file
     * @return string
     */
    private function uploadsFileToTemp($file)
    {
        $file_name = time() . "_services." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH. "/" .$file_name, file_get_contents($file));
        return $file_name;
    }

    public function transferTempFileToServicesFile($filename)
    {
        $old_path = TEMP_PATH . $filename;
        $new_path = SERVICES_UPLOADS_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(SERVICES_UPLOADS_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    public function deleteTempFileAction(Request $request)
    {
        Storage::disk("public")->delete(TEMP_PATH . $request->input("filename"));
        return response()->json(["success" => "1"]);
    }



    public function importExcelAction(Request $request)
    {
        return $this->service->importExcelService($request);
    }

    public function importExcel()
    {
        return view('service::services.import-excel');
    }

}
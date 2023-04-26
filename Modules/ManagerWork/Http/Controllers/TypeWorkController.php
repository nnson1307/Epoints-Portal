<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerWork\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ManagerWork\Repositories\TypeWork\TypeWorkRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class TypeWorkController extends Controller
{
    protected $typeWork;


    public function __construct(
        TypeWorkRepositoryInterface $typeWork
    )
    {
        $this->typeWork = $typeWork;
    }

    public function indexAction()
    {
        return view('manager-work::typeWork.index', [
            'list' => $this->typeWork->list(),
            'filter' => $this->filters(),
        ]);
    }

    protected function filters()
    {
        return [
            'is_active' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'is_active','created_at','updated_by','created_by']);
        return view('manager-work::typeWork.list', [
                'list' => $this->typeWork->list($filters),
                'filter' => $this->filters(),
                'page' => $filters['page']
            ]
        );
    }

    public function addAction(Request $request)
    {
        $manage_type_work_name = $request->manage_type_work_name;
        $checkExist = $this->typeWork->checkExist($manage_type_work_name);
        if ($checkExist != null) {
            return response()->json(['status' => 0]);
        } else {

            $data = [
                'manage_type_work_name' => $manage_type_work_name,
                'manage_type_work_icon' => isset($request->image) ? $request->image : asset('static/backend/images/icon-default.png'),
                'is_active' => 1,
                'created_by' => Auth::id(),
            ];
            $id = $this->typeWork->add($data);
            return response()->json(['status' => 1]);
        }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_type_work_id;
            $item = $this->typeWork->getItem($id);
            $jsonString = [
                'manage_type_work_id' => $id,
                'manage_type_work_icon' => $item->manage_type_work_icon,
                'manage_type_work_icon_full' => url($item->manage_type_work_icon),
                'manage_type_work_name' => $item->manage_type_work_name,
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_type_work_id;
            $manage_type_work_name = $request->manage_type_work_name;
            $checkExist = $this->typeWork->checkExist($manage_type_work_name,$id);
            $item = $this->typeWork->getItem($id); 
            if ($checkExist == null) {
                $data = [
                    'manage_type_work_name' => $request->manage_type_work_name,
                    'updated_by' => Auth::id(),
                ];
                if($item->manage_type_work_icon != $request->image && $request->image != ''){
                    $image = $request->image;
                    $data['manage_type_work_icon'] = $image;
                }
                if($this->typeWork->edit($data, $id)){
                    return response()->json(['status' => 1]);
                }
                return response()->json(['status' => 2]);
            } else {
                return response()->json(['status' => 0]);
            }
        }
    }

    public function removeAction(Request $request)
    {
        $data = $this->typeWork->remove($request->all());
        return response()->json($data);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_active'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $data['updated_by'] =  Auth::id();
        $this->typeWork->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
    public function uploadAction(Request $request)
    {
        $this->validate($request, [
            "manager_work" => "mimes:jpg,jpeg,png,gif,svg|max:10000"
        ], [
            "manager_work.mimes" => __('File không đúng định dạng'),
            "manager_work.max" => __('File quá lớn')
        ]);
        if ($request->file('file') != null) {
            $file = $this->uploadImageTemp($request->file('file'));
            return response()->json(["file" => $file, "success" => "1"]);
        }
    }

    //Lưu file image vào folder temp
    private function uploadImageTemp($file)
    {
        $time = Carbon::now();
        $file_name = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_manager_work." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH . "/" . $file_name, file_get_contents($file));
        return $file_name;
    }

    //Chuyển file từ folder temp sang folder chính
    private function transferTempfileToAdminfile($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = WORK_UPLOADS_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(WORK_UPLOADS_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    //function delete image
    public function deleteTempFileAction(Request $request)
    {
        Storage::disk("public")->delete(TEMP_PATH . '/' . $request->input('filename'));
        return response()->json(['success' => '1']);
    }

}
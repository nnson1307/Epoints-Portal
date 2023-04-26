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
use Modules\ManagerWork\Repositories\ManageRedmind\ManageRedmindRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ManageRedmindController extends Controller
{
    protected $manageRedmind;


    public function __construct(
        ManageRedmindRepositoryInterface $manageRedmind
    )
    {
        $this->manageRedmind = $manageRedmind;
    }

    public function indexAction()
    {
        return view('manager-work::manageRedmind.index', [
            'list' => $this->manageRedmind->list(),
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
        return view('manager-work::manageRedmind.list', [
                'list' => $this->manageRedmind->list($filters),
                'filter' => $this->filters()
            ]
        );
    }

    public function addAction(Request $request)
    {
        $manage_type_work_name = $request->manage_type_work_name;
        $checkExist = $this->manageRedmind->checkExist($manage_type_work_name);
        if ($checkExist != null) {
            return response()->json(['status' => 1]);
        } else {
            if($request->image){
                $image = $this->transferTempfileToAdminfile($request->image, str_replace('', '', $request->image));
            }
            $data = [
                'manage_type_work_name' => $manage_type_work_name,
                'manage_type_work_icon' => $image,
                'is_active' => 1,
                'created_by' => Auth::id(),
            ];
            $id = $this->manageRedmind->add($data);
            return response()->json(['status' => 1]);
        }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_type_work_id;
            $item = $this->manageRedmind->getItem($id);
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
            $checkExist = $this->manageRedmind->checkExist($manage_type_work_name,$id);
            if ($checkExist == null) {
                if($request->image){
                    $image = $this->transferTempfileToAdminfile($request->image, str_replace('', '', $request->image));
                }
                $data = [
                    'manage_type_work_name' => $request->manage_type_work_name,
                    'manage_type_work_icon' => $image,
                    'updated_by' => Auth::id(),
                ];
                if($this->manageRedmind->edit($data, $id)){
                    return response()->json(['status' => 1]);
                }
                return response()->json(['status' => 2]);
            } else {
                return response()->json(['status' => 0]);
            }
        }
    }

    public function removeAction($id)
    {
        $this->manageRedmind->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_active'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $data['updated_by'] =  Auth::id();
        $this->manageRedmind->edit($data, $change['id']);
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
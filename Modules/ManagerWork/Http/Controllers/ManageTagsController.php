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
use Modules\ManagerWork\Repositories\ManageTags\ManageTagsRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ManageTagsController extends Controller
{
    protected $manageTags;


    public function __construct(
        ManageTagsRepositoryInterface $manageTags
    )
    {
        $this->manageTags = $manageTags;
    }

    public function indexAction()
    {
        return view('manager-work::manageTags.index', [
            'list' => $this->manageTags->list(),
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
        return view('manager-work::manageTags.list', [
                'list' => $this->manageTags->list($filters),
                'filter' => $this->filters(),
                'page' => $filters['page']
            ]
        );
    }

    public function addAction(Request $request)
    {
        $manage_tag_name = $request->manage_tag_name;
        $checkExist = $this->manageTags->checkExist($manage_tag_name);
        if ($checkExist != null) {
            return response()->json(['status' => 0]);
        } else {
            $data = [
                'manage_tag_name' => $manage_tag_name,
                'is_active' => 1,
                'created_by' => Auth::id(),
            ];
            $id = $this->manageTags->add($data);
            return response()->json(['status' => 1]);
        }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_tag_id;
            $item = $this->manageTags->getItem($id);
            $jsonString = [
                'manage_tag_id' => $id,
                'manage_tag_name' => $item->manage_tag_name,
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->manage_tag_id;
            $manage_tag_name = $request->manage_tag_name;
            $checkExist = $this->manageTags->checkExist($manage_tag_name,$id);
            if ($checkExist == null) {
                $data = [
                    'manage_tag_name' => $request->manage_tag_name,
                    'updated_by' => Auth::id(),
                ];
                if($this->manageTags->edit($data, $id)){
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
        $result = $this->manageTags->remove($id);
        return response()->json($result);
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_active'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $data['updated_by'] =  Auth::id();
        $this->manageTags->edit($data, $change['id']);
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
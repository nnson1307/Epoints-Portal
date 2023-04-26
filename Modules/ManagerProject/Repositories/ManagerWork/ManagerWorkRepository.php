<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ManagerProject\Repositories\ManagerWork;

use App\Exports\ExportFile;
use App\Http\Middleware\S3UploadsRedirect;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\ManagerProject\Models\ManageProjectPhareTable;
use Modules\ManagerProject\Models\ProjectIssueTable;
use Modules\ManagerProject\Models\ProjectPhaseTable;
use Modules\ManagerWork\Http\Api\ManageFileApi;
use Modules\ManagerWork\Http\Api\SendNotificationApi;
use Modules\ManagerProject\Models\BranchTable;
use Modules\ManagerProject\Models\Customers;
use Modules\ManagerProject\Models\DepartmentTable;
use Modules\ManagerProject\Models\FileMinioConfigTable;
use Modules\ManagerProject\Models\ManageProjectStaffTable;
use Modules\ManagerProject\Models\ManagerCommentTable;
use Modules\ManagerProject\Models\ManagerDocumentFileTable;
use Modules\ManagerProject\Models\ManageRedmindTable;
use Modules\ManagerProject\Models\ManageRepeatTimeTable;
use Modules\ManagerProject\Models\ManagerHistoryTable;
use Modules\ManagerProject\Models\ManagerWorkTable;
use Modules\ManagerProject\Models\ManagerWorkTagTable;
use Modules\ManagerProject\Models\ManageStatusConfigMapTable;
use Modules\ManagerProject\Models\ManageStatusTable;
use Modules\ManagerProject\Models\ManageTagsTable;
use Modules\ManagerProject\Models\ManageWorkSupportTable;
use Modules\ManagerProject\Models\ProjectTable;
use Modules\ManagerProject\Models\StaffsTable;
use Modules\ManagerProject\Models\StaffTableNew;
use Modules\ManagerProject\Models\TicketTable;
use Modules\ManagerProject\Models\TypeWorkTable;
use Modules\ManagerProject\Repositories\Project\ProjectRepositoryInterface;
use Monolog\Handler\IFTTTHandler;
use Modules\ManagerProject\Models\ManageProjectTable;
use Modules\ManagerProject\Models\ManageProjectDocumentTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ManagerWorkRepository implements ManagerWorkRepositoryInterface
{
    /**
     * @var managerWork ableTable
     */
    protected $managerWork;
    protected $mManageHistory;
    protected $timestamps = true;
    protected $s3Disk;

    public function __construct(ManagerWorkTable $managerWork, S3UploadsRedirect $_s3, ManagerHistoryTable $mManageHistory)
    {
        $this->managerWork = $managerWork;
        $this->mManageHistory = $mManageHistory;
        $this->s3Disk = $_s3;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->managerWork->getListWorkAll($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->managerWork->getAll($filters);
    }
    public function getName()
    {
        return $this->managerWork->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->managerWork->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {
        return $this->managerWork->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->managerWork->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->managerWork->getItem($id);
    }

    /*
    * check exist
    */
    public function checkExist($name = '', $id = '')
    {
        return $this->managerWork->checkExist($name, $id);
    }

    /**
     * Lấy chi tiết công việc
     * @param $id
     * @return mixed|void
     */
    public function getDetail($id)
    {
        $mManageComment = new ManagerCommentTable();
        $mManageDocumentFile = new ManagerDocumentFileTable();
        $mManageSupport = new ManageWorkSupportTable();
        $mManageWorkTag = new ManagerWorkTagTable();

        $detail = $this->getDetailWork($id);
        if($detail['repeat_type'] != NULL && $detail['repeat_type'] != 'daily'){
            $mManageRepeatTime = new ManageRepeatTimeTable();
            $detail['repeat_time_list'] = $mManageRepeatTime->listTimeWorkProject($id);
        }
        $detail['total_message'] = $mManageComment->getTotalCommentByWork($id);
        $detail['total_attach'] = $mManageDocumentFile->getTotalFileAttach($id);
        $detail['list_support'] = $mManageSupport->getListSupport($id);
        $detail['list_tag'] = $mManageWorkTag->getListTagByWork($id);
        if ($detail['parent_id'] == null){
            $detail['is_parent'] = ($this->managerWork->getListChildTask($id) != 0 ? 1 : 0);
        } else {
            $detail['is_parent'] = 0;
        }
        return $detail;
    }

    public function getDetailWork($id){
        $detail = $this->managerWork->getDetail($id);
        return $detail;
    }

    /**
     * Lấy danh sách công việc con
     * @param $id
     * @return mixed|void
     */
    public function getListWorkChildInsert($id)
    {
        $mManageComment = new ManagerCommentTable();
        $mManageDocumentFile = new ManagerDocumentFileTable();
        $mManageSupport = new ManageWorkSupportTable();
        $mManageWorkTag = new ManagerWorkTagTable();

        $listWork = $this->managerWork->getListWorkChildInsert($id);
        foreach ($listWork as $key => $item) {
            $listWork[$key]['total_message'] = $mManageComment->getTotalCommentByWork($item['manage_work_id']);
            $listWork[$key]['total_attach'] = $mManageDocumentFile->getTotalFileAttach($item['manage_work_id']);
            $listWork[$key]['list_support'] = $mManageSupport->getListSupport($item['manage_work_id']);
            $listWork[$key]['list_tag'] = $mManageWorkTag->getListTagByWork($item['manage_work_id']);
        }

        return $listWork;
    }

    /**
     * Lấy danh sách lịch sử
     * @param $id
     * @return mixed|void
     */
    public function getListHistory($data)
    {
        $mManageHistory = new ManagerHistoryTable();
        $listHistory = $mManageHistory->getListhistory($data);
        if (count($listHistory) != 0) {
            $listHistory = collect($listHistory)->groupBy('created_at_format')->sortKeysDesc();
        }

        return $listHistory;
    }

    /**
     * Lấy danh sách comment
     * @param $id
     * @return mixed|void
     */
    public function getListComment($id)
    {
        $mManageComment = new ManagerCommentTable();
        $listComment = $mManageComment->getListCommentWork($id);

        foreach ($listComment as $key => $item) {
            $listComment[$key]['child_comment'] = $mManageComment->getListCommentWork($id, $item['manage_comment_id']);
        }
        return $listComment;
    }

    /**
     * Lấy giao diện theo từng tab
     * @return mixed|void
     */
    public function changeTabDetailWork($data)
    {
        try {
            $detail = $this->getDetail($data['id']);
            $filter['manage_work_id'] = $data['id'];
            if ($data['view'] == 'comment') {
                $listComment = $this->getListComment($data['id']);
                $view = view('manager-project::work.detail_comment', [
                    'detail' => $detail,
                    'listComment' => $listComment,

                ])->render();
            } else if ($data['view'] == 'sub_task' && $detail['parent_id'] == null){
                $listWorkChild = $this->getListWorkChild($filter);
                $listStatus = $this->getListStatus();

                $view = view('manager-project::work.detail-child-work', [
                    'detail' => $detail,
                    'listWorkChild' => $listWorkChild,
                    'listStatus' => $listStatus
                ])->render();
            } else if ($data['view'] == 'document') {
                $listDocument = $this->getListDocument($filter);
                $view = view('manager-project::work.detail-document', [
                    'detail' => $detail,
                    'listDocument' => $listDocument
                ])->render();
            } else if ($data['view'] == 'remind') {
                $filter['sort_date_remind'] = 'DESC';
                $filter['date_remind'] = Carbon::now()->startOfMonth()->format('d/m/Y') . ' - ' . Carbon::now()->endOfMonth()->format('d/m/Y');
                $listRemind = $this->getListRemindDetail($filter);
                //        lấy danh sách nhân viên

                $listStaff = $this->getListStaffId($detail);

                $view = view('manager-project::work.detail-remind', [
                    'detail' => $detail,
                    'listRemind' => $listRemind,
                    'listStaff' => $listStaff
                ])->render();
            } else if ($data['view'] == 'history') {
                $listStaff = $this->getListStaff();
                $view = view('manager-project::work.detail-history', [
                    'detail' => $detail,
                    'listStaff' => $listStaff
                ])->render();
            }

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chuyển tab thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * upload file
     * @param $data
     * @return mixed|void
     */
    public function uploadFile($input)
    {
        try {
            if ($input['file'] != null) {

                $fileName = $this->uploadImageS3($input['file'], '.');

                return [
                    'error' => false,
                    'file' => $fileName
                ];
            }

            if ($input['file'] != null) {
//                if (session()->has('brand_code') && in_array(session()->get('brand_code'), ['matthewsliquor', 'matthews','qc'])) {
                    $file = $input['file'];
                    $ext = $file->getClientOriginalExtension();
                    $mineType = $file->getMimeType();
                    $config = config('filesystems.disks.minio');

                    $mConfig = app()->get(FileMinioConfigTable::class);

                    $detailConfig = $mConfig->getLastConfig();

//                    $s3 = new S3Client([
//                        'credentials' => [
//                            'key'    => $config["key"],
//                            'secret' => $config["secret"]
//                        ],
//                        'region'      => $config["region"],
//                        'version'     => "latest",
//                        'use_path_style_endpoint' => true,
//                        'endpoint'    => $config["endpoint"],
//                        'bucket_endpoint' => false
//                    ]);

                    $s3 = new S3Client([
                        'credentials' => [
                            'key'    => $detailConfig["minio_root_user"],
                            'secret' => $detailConfig["minio_root_password"]
                        ],
                        'region'      => $detailConfig["minio_region"],
                        'version'     => "latest",
                        'use_path_style_endpoint' => true,
                        'endpoint'    => $detailConfig["minio_endpoint"],
                        'bucket_endpoint' => false
                    ]);

                    $fileName = $file->getClientOriginalName();

                    $folder = env('FOLDER');

                    $s3->putObject([
                        'Bucket' => $folder,
                        'SourceFile' => $file->getRealPath(),
                        'Key' => $fileName,
                        'ContentType' => $mineType
                        // 'Tagging' => http_build_query($tags),
                        // 'Metadata' => $metadata
                    ]);

                    $fullPath = $folder . "/" . $fileName;
                    $fileName = $detailConfig["minio_endpoint"]. '/' . $fullPath;
//                } else {
//                    $fileName = $this->uploadImageS3($input['file'], '.');
//                }

                return [
                    'error' => false,
                    'file' => $fileName
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Tải file thất bại'),
                '__message' => $e->getMessage(),
                '__Line' => $e->getLine(),
            ];
        }
    }


    /**
     * Lưu ảnh vào folder temp
     *
     * @param $file
     * @param $link
     * @return string
     */
    private function uploadImageS3($file, $link)
    {

        $time = Carbon::now();
        $idTenant = session()->get('idTenant');

        //        $to = $idTenant . '/' . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';
        $to = $idTenant . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';

        $file_name =
            str_random(5) .
            rand(0, 9) .
            time() .
            date_format($time, 'd') .
            date_format($time, 'm') .
            date_format($time, 'Y') .
            $link .
            $file->getClientOriginalExtension();

        Storage::disk('public')->put($to . $file_name, file_get_contents($file), 'public');

        //Lấy real path trên s3
        return $this->s3Disk->getRealPath($to . $file_name);
    }

    /**
     * Thêm bình luận
     * @param $data
     * @return mixed|void
     */
    public function addComment($data)
    {
        try {
            //            if (strlen(strip_tags($data['description'])) != 0){
            $mManageComment = new ManagerCommentTable();
            $mManageWork = app()->get(ManagerWorkTable::class);

            $comment = [
                'message' => $data['description'],
                'manage_work_id' => $data['manage_work_id'],
                'manage_parent_comment_id' => isset($data['manage_parent_comment_id']) ? $data['manage_parent_comment_id'] : null,
                'staff_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $idComment = $mManageComment->createdComment($comment);

            $detailComment = $mManageComment->getDetail($idComment);

            $mManageWork->updateWork([
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ], $data['manage_work_id']);

            $sendNoti = new SendNotificationApi();

            $dataNoti = [
                'key' => 'comment_new',
                'object_id' => $data['manage_work_id'],
            ];
            $sendNoti->sendStaffNotification($dataNoti);

            $view = view('manager-project::work.append.append-message', ['detail' => $detailComment, 'data' => $data])->render();

            $dataHistory = [
                'manage_work_id' => $data['manage_work_id'],
                'staff_id' => Auth::id(),
                'message' => __(' đã thêm bình luận mới cho công việc'),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $this->mManageHistory->createdHistory($dataHistory);

            return [
                'error' => false,
                'message' => __('Thêm bình luận thành công'),
                'view' => $view
            ];
            //            } else {
            //                return [
            //                    'error'=> true,
            //                    'message' => 'Vui lòng nhập bình luận'
            //                ];
            //            }

        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm bình luận thất bại')
            ];
        }
    }

    /**
     * hiển thị form comment
     * @param $data
     * @return mixed|void
     */
    public function showFormComment($data)
    {
        try {

            $view =  $view = view('manager-project::work.append.append-form-chat', ['manage_comment_id' => $data['manage_comment_id']])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị form trả lời thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách nhân viên
     * @return mixed|void
     */
    public function getListStaff()
    {
        $mStaff = new StaffsTable();
        return $mStaff->getAll();
    }

    /**
     * Lấy danh sách id của nhân viên
     * @param $detail
     * @return mixed|void
     */
    public function getListStaffId($detail)
    {
        $arrStaff[] = $detail['processor_id'];
        $arrStaff[] = $detail['assignor_id'];
        if ($detail['approve_id'] != null) {
            $arrStaff[] = $detail['approve_id'];
        }

        if (count($detail['list_support']) != 0) {
            $arrSupport = collect($detail['list_support'])->pluck('staff_id')->toArray();
            $arrStaff = array_merge($arrStaff, $arrSupport);
        }
        $arrStaff = array_unique($arrStaff);
        $listStaff = $this->getListStaffByWork($arrStaff);
        return $listStaff;
    }

    /**
     * Search tab lịch sử
     * @param $data
     * @return mixed|void
     */
    public function searchListHistory($data)
    {
        try {

            $listHistory = $this->getListHistory($data);

            $view =  $view = view('manager-project::work.append.list-history', ['listHistory' => $listHistory])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Tìm kiếm thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách file
     * @param $id
     * @return mixed|void
     */
    public function getListDocument($data)
    {
        $mManageDocumentFile = new ManagerDocumentFileTable();

        return $mManageDocumentFile->getListFile($data);
    }

    /**
     * Phân trang tài liệu
     * @param $data
     * @return mixed|void
     */
    public function searchDocument($data)
    {
        try {

            $mManageDocumentFile = new ManageProjectDocumentTable();
            $mManageWorkDocumentFile = new \Modules\ManagerWork\Models\ManagerDocumentFileTable();

            $listDocument = $mManageDocumentFile->getListFile($data);

            if (isset($data['manage_work_id'])) {
                $listDocument = $mManageWorkDocumentFile->getListFile($data);
            } else {
                $listDocument = $mManageDocumentFile->getListFile($data);
            }



            $listStaffManage = [];
            $listStaffProject = [];
            if (isset($data['manage_project_id'])) {
                $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);

                $listStaffManage = $mManageProjectStaff->getListAdmin($data['manage_project_id'],'administration');
                $listStaffProject = $mManageProjectStaff->getListAdmin($data['manage_project_id']);
                if (count($listStaffManage) != 0){
                    $listStaffManage = collect($listStaffManage)->pluck('staff_id')->toArray();
                }
                if (count($listStaffProject) != 0){
                    $listStaffProject = collect($listStaffProject)->pluck('staff_id')->toArray();
                }
            }
            
            $view =  $view = view('manager-project::work.append.append-list-document', ['listDocument' => $listDocument,'listStaffManage'=> $listStaffManage,'listStaffProject' => $listStaffProject])->render();
            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị danh sách file thất bại'),
                '__message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Hiển thị popup upload file
     * @param $data
     * @return mixed|void
     */
    public function showPopupUploadFile($data)
    {
        try {
            $mManageDocumentFile = new ManageProjectDocumentTable();

            if (isset($data['manage_project_document_id'])) {
                $detailFile = $mManageDocumentFile->getDetail($data['manage_project_document_id']);
            } else {
                $detailFile = null;
            }

            $viewPopup = 'manager-project::work.popup.popup-upload-file';
            if (isset($data['view_popup'])) {
                $viewPopup = $data['view_popup'];
            }

            $view =  $view = view($viewPopup, ['detailFile' => $detailFile])->render();


            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    /**
     * Thêm file hồ sơ
     * @param $data
     * @return mixed|void
     */
    public function addFileDocument($data)
    {
        try {

            if (!isset($data['type_upload']) || $data['type_upload'] != 'link'){
                if (!isset($data['document']) || count($data['document']) == 0) {
                    return [
                        'error' => true,
                        'message' => __('Lưu hồ sơ thất bại')
                    ];
                }
            } else {
                $message = '';
                if (!isset($data['name_upload'])){
                    $message = $message.__('Vui lòng nhập tên upload').'<br>';
                }
                if (!isset($data['link_upload'])){
                    $message = $message.__('Vui lòng nhập link upload');
                }
                if ($message != '') {
                    return [
                        'error' => true,
                        'message' => $message
                    ];
                }

                $data['document'][] = [
                    'file_name' => $data['name_upload'],
                    'path' => $data['link_upload'],
                    'file_type' => 'file',
                    'type_upload' => isset($data['type_upload']) && $data['type_upload'] == 'link' ? 'link' : 'file'
                ];
            }

            $mManageProject = app()->get(ManageProjectTable::class);

            // $mManageProject->editWork([
            //     'updated_at' => Carbon::now(),
            //     'updated_by' => Auth::id()
            // ], $data['manage_project_id']);

            $mManageDocumentFile = new ManageProjectDocumentTable();
            $mManageWorkDocumentFile = new ManagerDocumentFileTable();


            if (isset($data['manage_work_id'])){
                if (isset($data['manage_document_id'])) {
                    foreach ($data['document'] as $itemDocument) {
                        $dataImage = [
                            'file_name' => strip_tags($itemDocument['file_name']),
                            'path' => $itemDocument['path'],
                            'file_type' => $itemDocument['file_type'],
                            'type_upload' => isset($itemDocument['type_upload']) && $itemDocument['type_upload'] == 'link' ? 'link' : 'file',
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id()
                        ];
                        $mManageWorkDocumentFile->updatedFileDocument($dataImage, $data['manage_document_id']);
                    }
                    $detailDocument = $mManageWorkDocumentFile->getDetail($data['manage_document_id']);
                    $id = $detailDocument['manage_work_id'];
                } else {
                    foreach ($data['document'] as $itemDocument) {
                        $dataImage = [
                            'file_name' => strip_tags($itemDocument['file_name']),
                            'manage_work_id' => $data['manage_work_id'],
                            'path' => $itemDocument['path'],
                            'file_type' => $itemDocument['file_type'],
//                            'type_upload' => isset($itemDocument['type_upload']) && $itemDocument['type_upload'] == 'link' ? 'link' : 'file',
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id()
                        ];
                        $idFile = $mManageWorkDocumentFile->createdFileDocument($dataImage);

//                    $sendNoti = new SendNotificationApi();
//
//                    $dataNoti = [
//                        'key' => 'file_new',
//                        'object_id' => $idFile,
//                    ];
//                    $sendNoti->sendStaffNotification($dataNoti);
                    }
                    $id = $data['manage_work_id'];
                }
            } else {
                if (isset($data['manage_project_document_id'])) {
                    foreach ($data['document'] as $itemDocument) {
                        $dataImage = [
                            'file_name' => strip_tags($itemDocument['file_name']),
                            'path' => $itemDocument['path'],
                            'type' => $itemDocument['file_type'],
                            'type_upload' => isset($itemDocument['type_upload']) && $itemDocument['type_upload'] == 'link' ? 'link' : 'file',
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id()
                        ];
                        $mManageDocumentFile->updatedFileDocument($dataImage, $data['manage_project_document_id']);
                    }
                    $detailDocument = $mManageDocumentFile->getDetail($data['manage_project_document_id']);
                    $id = $detailDocument['manage_project_id'];
                } else {
                    foreach ($data['document'] as $itemDocument) {
                        $dataImage = [
                            'file_name' => strip_tags($itemDocument['file_name']),
                            'manage_project_id' => $data['manage_project_id'],
                            'path' => $itemDocument['path'],
                            'type' => $itemDocument['file_type'],
                            'type_upload' => isset($itemDocument['type_upload']) && $itemDocument['type_upload'] == 'link' ? 'link' : 'file',
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id()
                        ];
                        $idFile = $mManageDocumentFile->createdFileDocument($dataImage);

//                    $sendNoti = new SendNotificationApi();
//
//                    $dataNoti = [
//                        'key' => 'file_new',
//                        'object_id' => $idFile,
//                    ];
//                    $sendNoti->sendStaffNotification($dataNoti);
                    }
                    $id = $data['manage_project_id'];
                }
            }

            if (isset($data['manage_work_id'])){
                $dataHistory = [
                    'manage_work_id' => $data['manage_work_id'],
                    'staff_id' => Auth::id(),
                    'message' => __(' đã cập nhật file hồ sơ thành công'),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $this->mManageHistory->createdHistory($dataHistory);
            } else {
                $rProjectRepo = app()->get(ProjectRepositoryInterface::class);
                $rProjectRepo->createHistoryProject([
                    'key' => 'document',
                    'manage_project_id' => $id
                ]);
            }


            $detailFile = null;
            $view =  $view = view('manager-project::work.popup.popup-upload-file', ['detailFile' => $detailFile])->render();


            return [
                'error' => false,
                'view' => $view,
                'message' => __('Lưu hồ sơ thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lưu hồ sơ thất bại'),
                '__message' => $e->getMessage(),
            ];
        }
    }

    public function removeFileDocument($data)
    {
        try {
            $mManageDocumentFile = new ManageProjectDocumentTable();

            $detail = $mManageDocumentFile->getDetail($data['manage_document_file_id']);

            // $dataHistory = [
            //     'manage_work_id' => $detail['manage_work_id'],
            //     'staff_id' => Auth::id(),
            //     'message' => __(' đã xoá thành công hồ sơ ') . $detail['file_name'],
            //     'created_at' => Carbon::now(),
            //     'created_by' => Auth::id(),
            //     'updated_at' => Carbon::now(),
            //     'updated_by' => Auth::id()
            // ];

            // $this->mManageHistory->createdHistory($dataHistory);

            $mManageDocumentFile->removeFileDocument($data['manage_document_file_id']);
            $rProjectRepo = app()->get(ProjectRepositoryInterface::class);
            $rProjectRepo->createHistoryProject([
                'key' => 'document_delete',
                'old' => $detail['file_name'],
                'manage_project_id' => $detail['manage_project_id']
            ]);
            return [
                'error' => false,
                'message' => __('Xoá hồ sơ thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá hồ sơ thất bại')
            ];
        }
    }

    /**
     * lấy danh sách nhắc nhở
     * @param $id
     * @return mixed|void
     */
    public function getListRemind($id)
    {
        $mManageRemind = new ManageRedmindTable();
        return $mManageRemind->getByWork($id);
    }

    /**
     * Lấy danh sách nhân viên theo nhóm id nhân viên
     * @param $arrStaff
     * @return mixed|void
     */
    public function getListStaffByWork($arrStaff)
    {
        $mStaff = new StaffsTable();
        return $mStaff->getListStaffByStaff($arrStaff);
    }

    /**
     * hiển thị popup nhắc nhở
     * @param $data
     * @return mixed|void
     */
    public function showPopupRemindPopup($data)
    {
        try {

            $mManageRemind = new ManageRedmindTable();
            $mManageWork = app()->get(ManagerWorkTable::class);
            $detail = null;
            if (isset($data['manage_remind_id'])) {
                $detail = $mManageRemind->getDetailRemind($data['manage_remind_id']);
            }

            $workDetail = null ;
            if (isset($data['manage_work_id'])){
                $workDetail = $mManageWork->getDetail($data['manage_work_id']);
            }

            $view = view('manager-project::work.popup.remind-work', ['data' => $data, 'detail' => $detail,'workDetail' => $workDetail])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    /**
     * Tạo / chỉnh sửa nhắc nhở
     * @param $data
     * @return array|mixed
     */
    public function addRemindWork($data)
    {
        try {
            $mManageRemind = new ManageRedmindTable();
            if (isset($data['time_remind']) && $data['time_remind'] == 'selected') {
                unset($data['time_remind']);
            }
            if (isset($data['time_remind'])) {
                $data['time_remind'] = str_replace(',', '', $data['time_remind']);
                //                $messageError = $this->checkRemind($data);
                //                if ($messageError != null){
                //                    return [
                //                        'error' => true,
                //                        'message'=> $messageError
                //                    ];
                //                }
            }

            $mStaff = app()->get(StaffsTable::class);
            $mManageWork = app()->get(ManagerWorkTable::class);

            $dataRemind = [];
            foreach ($data['staff'] as $item) {
                if (isset($data['manage_remind_id'])) {
                    $dataRemind = [
                        'date_remind' => Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->format('Y-m-d H:i:00'),
                        'time' => isset($data['time_remind']) ? $data['time_remind'] : null,
                        'time_type' => isset($data['time_remind']) ? $data['time_type_remind'] : null,
                        'description' => strip_tags($data['description_remind']),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                } else {

                    $created_by = $mStaff->getStaffId(Auth::id());
                    $staff_id = $mStaff->getStaffId($item);
                    if (isset($data['popup_manage_work_id'])) {
                        $detailWork = $mManageWork->getDetail($data['popup_manage_work_id']);
                        $title = $created_by['staff_name'] . ' ' . __('managerwork::managerwork.created_remind_work_for', ['manage_work_title' => $detailWork['manage_work_title']]) . ' ' . $staff_id['staff_name'];
                    } else {
                        $title = $created_by['staff_name'] . ' ' . __('managerwork::managerwork.created_remind_for') . ' ' . $staff_id['staff_name'];
                    }

                    $dataRemind[] = [
                        'title' => $title,
                        'staff_id' => $item,
                        'manage_work_id' => $data['popup_manage_work_id'],
                        'date_remind' => Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->format('Y-m-d H:i:00'),
                        'time' => isset($data['time_remind']) ? $data['time_remind'] : null,
                        'time_type' => isset($data['time_remind']) ? $data['time_type_remind'] : null,
                        'description' => strip_tags($data['description_remind']),
                        'is_sent' => 0,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }
            }

            if (isset($data['manage_remind_id'])) {
                $mManageRemind->updateRemind($dataRemind, $data['manage_remind_id']);

                $dataHistory = [
                    'manage_work_id' => $data['popup_manage_work_id'],
                    'staff_id' => Auth::id(),
                    'message' => __(' đã cập nhật thành công nhắc nhở'),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $this->mManageHistory->createdHistory($dataHistory);
            } else {
                if (count($dataRemind) != 0) {
                    $idRemind = $mManageRemind->insertRemind($dataRemind[0]);
                    unset($dataRemind[0]);
                    if (count($dataRemind) != 0) {
                        $mManageRemind->insertArrayRemind($dataRemind);
                    }
                }

                $sendNoti = new SendNotificationApi();

                $mManageWork->editWork([
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ],$data['popup_manage_work_id']);

                $dataNoti = [
                    'key' => 'work_remind',
                    'object_id' => $idRemind,
                ];
                $sendNoti->sendStaffNotification($dataNoti);

                $dataHistory = [
                    'manage_work_id' => $data['popup_manage_work_id'],
                    'staff_id' => Auth::id(),
                    'message' => __(' đã tạo nhắc nhở thành công'),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $this->mManageHistory->createdHistory($dataHistory);
            }

            $data['manage_work_id'] = $data['popup_manage_work_id'];
            $detail = null;

            $view = view('manager-project::work.popup.remind-work', ['data' => $data, 'detail' => $detail])->render();

            return [
                'error' => false,
                'message' => __('Lưu nhắc nhở thành công'),
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lưu nhắc nhở thất bại ' . $e->getMessage())
            ];
        }
    }

    /**
     * Kiểm tra nhắc trước
     * @param $data
     * @return string|null
     */
    public function checkRemind($data)
    {
        $messageError = __('Thời gian trước nhắc nhở cho thời gian nhắc đã qua vui lòng chọn thời gian khác');
        if ($data['time_type_remind'] == 'm') {
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->subMinutes($data['time_remind'] == '' ? 0 : $data['time_remind'])) {
                return $messageError;
            }
        } else if ($data['time_type_remind'] == 'h') {
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->subHours($data['time_remind'] == '' ? 0 : $data['time_remind'])) {
                return $messageError;
            }
        } else if ($data['time_type_remind'] == 'd') {
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->subDays($data['time_remind'] == '' ? 0 : $data['time_remind'])) {
                return $messageError;
            }
        } else if ($data['time_type_remind'] == 'w') {
            if (Carbon::now() > Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->subWeeks($data['time_remind'] == '' ? 0 : $data['time_remind'])) {
                return $messageError;
            }
        }

        return null;
    }

    /**
     * Xoá nhắc nhở
     * @param $data
     * @return mixed|void
     */
    public function removeRemind($data)
    {
        try {

            $mManageRemind = new ManageRedmindTable();

            $detail = $mManageRemind->getItem($data['manage_remind_id']);

            $idWork = null;
            if (isset($data['popup_manage_work_id'])) {
                $idWork = $data['popup_manage_work_id'];
            } else if (isset($detail['manage_work_id'])) {
                $idWork = $detail['manage_work_id'];
            }

            if ($idWork != null) {
                $dataHistory = [
                    'manage_work_id' => $idWork,
                    'staff_id' => Auth::id(),
                    'message' => __(' đã xoá nhắc nhở ') . $detail['description'],
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $this->mManageHistory->createdHistory($dataHistory);
            }

            $mManageRemind->remove($data['manage_remind_id']);

            return [
                'error' => false,
                'message' => __('Xoá nhắc nhở thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá nhắc nhở thất bại')
            ];
        }
    }


    public function searchRemind($data)
    {
        try {

            $mManageRemind = new ManageRedmindTable();

            $listRemind = $mManageRemind->getListRemind($data);

            $view = view('manager-project::work.append.append-list-remind', ['listRemind' => $listRemind])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lấy danh sách nhắc nhở thất bại')
            ];
        }
    }

    public function changeStatusRemind($data)
    {
        try {

            $mManageRemind = new ManageRedmindTable();
            $detail = $mManageRemind->getItem($data['manage_remind_id']);

            $dataHistory = [
                'manage_work_id' => isset($data['popup_manage_work_id']) ? $data['popup_manage_work_id'] : '',
                'staff_id' => Auth::id(),
                'message' => __(' đã cập nhật trạng thái nhắc nhở ') . $detail['description'],
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $this->mManageHistory->createdHistory($dataHistory);

            $mManageRemind->updateRemind(['is_active' => $data['is_active']], $data['manage_remind_id']);


            return [
                'error' => false,
                'message' => __('Cập nhật trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Status update failed')
            ];
        }
    }

    /**
     * Lấy danh sách nhắc nhở
     * @param $data
     * @return mixed
     */
    public function getListRemindDetail($data)
    {
        $mManageRemind = new ManageRedmindTable();
        return $mManageRemind->getListRemind($data);
    }

    /**
     * lấy danh sách công việc con
     * @param $data
     * @return mixed|void
     */
    public function getListWorkChild($data)
    {
        $mManageWork = new ManagerWorkTable();

        return $mManageWork->getListWorkChild($data);
    }

    public function showPopupWorkChild($data)
    {
        try {
            $mManageTypeWork = new TypeWorkTable();
            $mManageWork = new ManagerWorkTable();
            $mManageProject = new ProjectTable();
            $mStaff = new StaffsTable();
            $mManageStatus = new ManageStatusTable();
            $mManageTag = new ManageTagsTable();
            $mCustomer = new Customers();
            $mManageRepeatTime = new ManageRepeatTimeTable();
            $mManageStatusConfigMap = new ManageStatusConfigMapTable();
            $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
            $mManageProjectIssue = app()->get(ProjectIssueTable::class);

            $listTypeWork = $mManageTypeWork->getListTypeWork();

            $projectDetail = $mManageProject->getDetailProject($data['manage_project_id']);

            $listStaffProject = $mManageProjectStaff->getListStaffByProject($data['manage_project_id']);

            $arrStaff = [];
            if (count($listStaffProject) != 0){
                $arrStaff = collect($listStaffProject)->pluck('staff_id')->toArray();
            }

            $arrStaff[count($arrStaff)] = $projectDetail['manager_id'];

            $listStaff = $mStaff->getAll(['arr_staff' => $arrStaff]);
            $listWork = $mManageWork->getAllParent();
            $listProject = $mManageProject->getAll();
            $listStatus = $mManageStatus->getAll();
            $listTag = $mManageTag->getAll();
            $listCustomer = $mCustomer->getAll();

            $detail = null;
            $listTime = [];

            $dataInfo = [
                'title' => null,
                'description' => null,
                'create_object_type' => null,
                'create_object_id' => null
            ];

            $issueDetail = null;

            if (isset($data['project_issue_id'])){
                $issueDetail = $mManageProjectIssue->getDetail($data['project_issue_id']);
                $dataInfo['description'] = $issueDetail['content'];
            }

            if (isset($data['manage_work_id'])) {
                $detail = $this->getDetail($data['manage_work_id']);
                $listTime = $mManageRepeatTime->listTimeWork($data['manage_work_id']);
                $listTime = collect($listTime)->toArray();
                $listStatusConfig = $mManageStatusConfigMap->getListStatusByConfig($detail['manage_status_id']);

                if (count($listStatusConfig) != 0) {
                    $listStatusConfig = collect($listStatusConfig)->pluck('manage_status_id')->toArray();
                } else {
                    $listStatusConfig = [];
                }

                $listStatusConfig = array_merge($listStatusConfig, [$detail['manage_status_id']]);
                $listStatus = $mManageStatus->getAll($listStatusConfig);

                if ($detail != null) {
                    $dataInfo['title'] = $detail['manage_work_title'];
                    $dataInfo['description'] = $detail['description'];
                }
            }

            $mBranch = app()->get(BranchTable::class);

            $listBranch = $mBranch->getListBranch();

            $dataShift = [];

            if (isset($data['view']) && $data['view'] == 'shift') {
                $dataShift = [
                    "processor_id" => $data['processor_id'],
                    "manage_type_work_id" => $data['manage_type_work_id'],
                    "start_date" => $data['start_date'],
                    "start_time" => $data['start_time'],
                    "end_date" => $data['end_date'],
                    "end_time" => $data['end_time'],
                    "view" => $data['view']
                ];

                $dataInfo['create_object_type'] = "shift";
                $dataInfo['create_object_id'] = $data['manage_type_work_id'];
            }

            $infoTicket = null;

            if (isset($data['create_object_type']) && $data['create_object_type'] != null) {
                switch ($data['create_object_type']) {
                    case 'ticket':
                        $mTicket = app()->get(TicketTable::class);

                        //Lấy thông tin ticket
                        $infoTicket = $mTicket->getInfo($data['create_object_id']);

                        if ($infoTicket != null) {
                            $routeDetail = route('ticket.detail', $infoTicket['ticket_id']);

                            $dataInfo['title'] =  '['. $infoTicket['ticket_code']. ']' . ' ' . $infoTicket['title'];
                            $dataInfo['description'] = '<a href="'.$routeDetail.'" target="_blank">'.$dataInfo['title'].'</a>';
                            $dataInfo['create_object_type'] = $data['create_object_type'];
                            $dataInfo['create_object_id'] = $data['create_object_id'];
                        }
                        break;
                }
            }

            $dataBranch = [];
            $dataOptionSupport = [];

            //Lấy chi nhánh của nhân viên
            $getBranchByStaff = $mStaff->getBranchByStaff()->toArray();

            if (count($getBranchByStaff) > 0) {
                $mDepartment = app()->get(DepartmentTable::class);

                //Lấy tất cả phòng ban
                $getDepartment = $mDepartment->getAll()->toArray();

                foreach ($getBranchByStaff as $v) {
                    $v['department'] = $getDepartment;

                    $dataBranch [] = $v;
                }
            }

            $parentWork = null;

            if (isset($detail['parent_id'])){
                $parentWork = $this->getDetail($detail['parent_id']);
            }



            $arrStaff = [];
            if ($detail == null) {
                if(($parentWork != null && $parentWork['manage_project_id'] != null )|| isset($data['manage_project_id'])){
                    $manage_project_id = $parentWork != null && $parentWork['manage_project_id'] ? $parentWork['manage_project_id'] : (isset($data['manage_project_id']) ? $data['manage_project_id'] : '');

                    $dataStaff = $this->getListStaffByProject($manage_project_id);
                    $listStaff = $dataStaff['listStaff'];
                    $arrStaff = $dataStaff['arrStaff'];
                } else {
                    $listProject = $mManageProject->getAllActive();
                    $listStatus = $mManageStatus->getListStatusActive();
                }
            } else {
                if (isset($detail['manage_project_id'])){
                    $dataStaff = $this->getListStaffByProject($detail['']);
                    $listStaff = $dataStaff['listStaff'];
                    $arrStaff = $dataStaff['arrStaff'];
                }
            }

            //Lấy chi nhánh của phòng ban
            if (count($dataBranch) > 0) {
                foreach ($dataBranch as $v) {
                    $dataChild = [];

                    if (count($v['department']) > 0) {
                        foreach ($v['department'] as $v1) {
                            //Lấy thông tin nv theo phòng ban và chi nhánh
                            if(count($arrStaff) != 0){
                                $getStaff = $mStaff->getListStaffByBranchDepartmentStaff($v['branch_id'], $v1['department_id'],$arrStaff)->toArray();
                            }else {
                                $getStaff = $mStaff->getListStaffByBranchDepartment($v['branch_id'], $v1['department_id'])->toArray();
                            }

                            if (count($getStaff) != 0){
                                $v1['list_staff'] = $getStaff;
                                $dataChild[] = $v1;
                            }
                        }
                    }

                    if (count($dataChild) != 0){
                        $dataOptionSupport [] = [
                            "branch_id" => $v['branch_id'],
                            "branch_name" => $v['branch_name'],
                            "dataChild" => $dataChild
                        ];
                    }
                }
            }
            $mManageProjectPhase = app()->get(ManageProjectPhareTable::class);
            $listPhase = [];
            if ($detail != null) {
                $dataStaffSupport = [];

                if (count($detail['list_support']) > 0) {
                    foreach ($detail['list_support'] as $v) {
                        $dataStaffSupport [$v['staff_id']] = [
                            'staff_id' => $v['staff_id']
                        ];
                    }
                }

                session()->put('staff_support', $dataStaffSupport);

                if (isset($detail['manage_project_id'])){
                    $listPhase = $mManageProjectPhase->getAllPhareByProject($detail['manage_project_id']);
                }
            } else {
                $listPhase = $mManageProjectPhase->getAllPhareByProject($data['manage_project_id']);
            }

            $view = view('manager-project::work.popup.popup-work', [
                'listTypeWork' => $listTypeWork,
                'listStaff' => $listStaff,
                'listWork' => $listWork,
                'listProject' => $listProject,
                'listStatus' => $listStatus,
                'listTag' => $listTag,
                'listCustomer' => $listCustomer,
                'data' => $data,
                'detail' => $detail,
                'listTime' => $listTime,
                'dataShift' => $dataShift,
                'listBranch' => $listBranch,
                'dataInfo' => $dataInfo,
                'dataOptionSupport' => $dataOptionSupport,
                'manage_project_id' => $data['manage_project_id'],
                'parentWork' => $parentWork,
                'listPhase' => $listPhase
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại'),
                '__message' => $e->getMessage(),
                '__line' => $e->getLine(),
                '__file' => $e->getFile(),
            ];
        }
    }

    public function addAndEditWork($data)
    {
        try {
            $mManageTypeWork = new TypeWorkTable();
            $mManageWork = new ManagerWorkTable();
            $mManageProject = new ProjectTable();
            $mStaff = new StaffsTable();
            $mManageStatus = new ManageStatusTable();
            $mManageTag = new ManageTagsTable();
            $mCustomer = new Customers();
            $mManageRepeatTime = new ManageRepeatTimeTable();
            $mManageStatusConfigMap = new ManageStatusConfigMapTable();
            $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);

            $listTypeWork = $mManageTypeWork->getListTypeWork();

            $projectDetail = $mManageProject->getDetailProject($data['manage_project_id']);

            $listStaffProject = $mManageProjectStaff->getListStaffByProject($data['manage_project_id']);

            $arrStaff = [];
            if (count($listStaffProject) != 0){
                $arrStaff = collect($listStaffProject)->pluck('staff_id')->toArray();
            }

            $arrStaff[count($arrStaff)] = $projectDetail['manager_id'];

            $listStaff = $mStaff->getAll(['arr_staff' => $arrStaff]);
            $listWork = $mManageWork->getAllParent();
            $listProject = $mManageProject->getAll();
            $listStatus = $mManageStatus->getAll();
            $listTag = $mManageTag->getAll();
            $listCustomer = $mCustomer->getAll();

            $detail = null;
            $listTime = [];

            $dataInfo = [
                'title' => null,
                'description' => null,
                'create_object_type' => null,
                'create_object_id' => null
            ];

            if (isset($data['manage_work_id'])) {
                $detail = $this->getDetail($data['manage_work_id']);
                $listTime = $mManageRepeatTime->listTimeWork($data['manage_work_id']);
                $listTime = collect($listTime)->toArray();
                $listStatusConfig = $mManageStatusConfigMap->getListStatusByConfig($detail['manage_status_id']);

                if (count($listStatusConfig) != 0) {
                    $listStatusConfig = collect($listStatusConfig)->pluck('manage_status_id')->toArray();
                } else {
                    $listStatusConfig = [];
                }

                $listStatusConfig = array_merge($listStatusConfig, [$detail['manage_status_id']]);
                $listStatus = $mManageStatus->getAll($listStatusConfig);

                if ($detail != null) {
                    $dataInfo['title'] = $detail['manage_work_title'];
                    $dataInfo['description'] = $detail['description'];
                }
            }

            $mBranch = app()->get(BranchTable::class);

            $listBranch = $mBranch->getListBranch();

            $dataShift = [];

            if (isset($data['view']) && $data['view'] == 'shift') {
                $dataShift = [
                    "processor_id" => $data['processor_id'],
                    "manage_type_work_id" => $data['manage_type_work_id'],
                    "start_date" => $data['start_date'],
                    "start_time" => $data['start_time'],
                    "end_date" => $data['end_date'],
                    "end_time" => $data['end_time'],
                    "view" => $data['view']
                ];

                $dataInfo['create_object_type'] = "shift";
                $dataInfo['create_object_id'] = $data['manage_type_work_id'];
            }

            $infoTicket = null;

            if (isset($data['create_object_type']) && $data['create_object_type'] != null) {
                switch ($data['create_object_type']) {
                    case 'ticket':
                        $mTicket = app()->get(TicketTable::class);

                        //Lấy thông tin ticket
                        $infoTicket = $mTicket->getInfo($data['create_object_id']);

                        if ($infoTicket != null) {
                            $routeDetail = route('ticket.detail', $infoTicket['ticket_id']);

                            $dataInfo['title'] =  '['. $infoTicket['ticket_code']. ']' . ' ' . $infoTicket['title'];
                            $dataInfo['description'] = '<a href="'.$routeDetail.'" target="_blank">'.$dataInfo['title'].'</a>';
                            $dataInfo['create_object_type'] = $data['create_object_type'];
                            $dataInfo['create_object_id'] = $data['create_object_id'];
                        }
                        break;
                }
            }

            $dataBranch = [];
            $dataOptionSupport = [];

            //Lấy chi nhánh của nhân viên
            $getBranchByStaff = $mStaff->getBranchByStaff()->toArray();

            if (count($getBranchByStaff) > 0) {
                $mDepartment = app()->get(DepartmentTable::class);

                //Lấy tất cả phòng ban
                $getDepartment = $mDepartment->getAll()->toArray();

                foreach ($getBranchByStaff as $v) {
                    $v['department'] = $getDepartment;

                    $dataBranch [] = $v;
                }
            }

            $parentWork = null;
            if (isset($data['parent_id'])){
                $parentWork = $this->getDetail($data['parent_id']);
            }

            $arrStaff = [];
            if ($detail == null) {
                if(($parentWork != null && $parentWork['manage_project_id'] != null )|| isset($data['manage_project_id'])){
                    $manage_project_id = $parentWork != null && $parentWork['manage_project_id'] ? $parentWork['manage_project_id'] : (isset($data['manage_project_id']) ? $data['manage_project_id'] : '');
                    $dataStaff = $this->getListStaffByProject($manage_project_id);
                    $listStaff = $dataStaff['listStaff'];
                    $arrStaff = $dataStaff['arrStaff'];
                } else {
                    $listProject = $mManageProject->getAllActive();
                    $listStatus = $mManageStatus->getListStatusActive();
                }
            } else {
                if (isset($detail['manage_project_id'])){
                    $dataStaff = $this->getListStaffByProject($detail['']);
                    $listStaff = $dataStaff['listStaff'];
                    $arrStaff = $dataStaff['arrStaff'];
                }
            }

            //Lấy chi nhánh của phòng ban
            if (count($dataBranch) > 0) {
                foreach ($dataBranch as $v) {
                    $dataChild = [];

                    if (count($v['department']) > 0) {
                        foreach ($v['department'] as $v1) {
                            //Lấy thông tin nv theo phòng ban và chi nhánh
                            if(count($arrStaff) != 0){
                                $getStaff = $mStaff->getListStaffByBranchDepartmentStaff($v['branch_id'], $v1['department_id'],$arrStaff)->toArray();
                            }else {
                                $getStaff = $mStaff->getListStaffByBranchDepartment($v['branch_id'], $v1['department_id'])->toArray();
                            }

                            if (count($getStaff) != 0){
                                $v1['list_staff'] = $getStaff;
                                $dataChild[] = $v1;
                            }
                        }
                    }

                    if (count($dataChild) != 0){
                        $dataOptionSupport [] = [
                            "branch_id" => $v['branch_id'],
                            "branch_name" => $v['branch_name'],
                            "dataChild" => $dataChild
                        ];
                    }
                }
            }

            if ($detail != null) {
                $dataStaffSupport = [];

                if (count($detail['list_support']) > 0) {
                    foreach ($detail['list_support'] as $v) {
                        $dataStaffSupport [$v['staff_id']] = [
                            'staff_id' => $v['staff_id']
                        ];
                    }
                }

                session()->put('staff_support', $dataStaffSupport);
            }

            return [
                'listTypeWork' => $listTypeWork,
                'listStaff' => $listStaff,
                'listWork' => $listWork,
                'listProject' => $listProject,
                'listStatus' => $listStatus,
                'listTag' => $listTag,
                'listCustomer' => $listCustomer,
                'data' => $data,
                'detail' => $detail,
                'listTime' => $listTime,
                'dataShift' => $dataShift,
                'listBranch' => $listBranch,
                'dataInfo' => $dataInfo,
                'dataOptionSupport' => $dataOptionSupport,
                'manage_project_id' => $data['manage_project_id'],
                'parentWork' => $parentWork,
                'param' => $data
            ];
        } catch (\Exception $e) {
            return [
                '__message' => $e->getMessage(),
                '__line' => $e->getLine(),
            ];
        }
    }

    public function getListStaffByProject($manage_project_id){
        $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);
        $mStaff = new StaffsTable();
        $arrStaff = [];
        if(isset($manage_project_id)){
            $listStaffProject = $mManageProjectStaff->getListStaffByProject($manage_project_id);

            if (count($listStaffProject) != 0){
                $arrStaff = collect($listStaffProject)->pluck('staff_id')->toArray();
            }

            $listStaff = $mStaff->getAll(['arr_staff' => $arrStaff]);
        } else {
            $listStaff = $mStaff->getAll();
        }


        return [
            'listStaff' => $listStaff,
            'arrStaff' => $arrStaff
        ];
    }

    /**
     * Lưu công việc
     * @param $data
     * @return mixed|void
     */
    public function saveChildWork($data)
    {
        try {
            if (isset($data['time_remind']) && $data['time_remind'] == 'selected') {
                unset($data['time_remind']);
            }

            $totalParentProgress = 0;

            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageWorkTag = new ManagerWorkTagTable();
            $mManageRepeatTime = new ManageRepeatTimeTable();
            $sendNoti = new SendNotificationApi();
            $mManageWork = app()->get(ManagerWorkTable::class);

            DB::beginTransaction();

            if (isset($data['support']) && count($data['support']) != 0 && in_array($data['processor_id'], collect($data['support'])->toArray())) {
                return [
                    'error' => true,
                    'message' => __('Nhân viên hỗ trợ không được trùng với nhân viên thực hiện')
                ];
            }

            if (isset($data['date_start'])) {

                $date_start = Carbon::createFromFormat('d/m/Y H:i', $data['date_start'])->format('Y-m-d H:i:00');
                $date_end = Carbon::createFromFormat('d/m/Y H:i', $data['date_end'])->format('Y-m-d H:i:00');

                if ($date_start > $date_end) {
                    return [
                        'error' => true,
                        'message' => __('Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc')
                    ];
                }
            }

            if (isset($data['is_edit_work'])) {
                $dataWork = [
                    'manage_status_id' => isset($data['manage_status_id']) ? $data['manage_status_id'] : null,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ];

                $idPhase = isset($data['manage_project_phase_id']) ?? null;
                if (!$idPhase & isset($data['manage_project_id']) && $data['manage_project_id'] != null && $data['manage_project_id'] != '') {
                    $filter = [
                        'manage_project_id' => $data['manage_project_id'],
                    ];
                    $mProjectPhase = new ProjectPhaseTable();
                    $defaultPhase = $mProjectPhase->getDefaultPhase($filter);
                    $dataWork['manage_project_phase_id'] = $defaultPhase['manage_project_phase_id'];
                }

                $idWork = $data['manage_work_child_id'];
                $detailOld = $this->getDetail($idWork);
                if (isset($data['manage_status_id']) && $data['manage_status_id'] == 6 && $detailOld['manage_status_id'] != 6) {
                    $dataWork['date_finish'] = Carbon::now();
                }
                $mManageWork->updateWork($dataWork, $idWork);

                if ($dataWork['manage_status_id'] == 7 && $detailOld['parent_id'] == null) {
                    $listTaskChildOld = $mManageWork->getListTaskOfParent($detailOld['manage_work_id']);
                    $mManageWork->updateByParentId([
                        'manage_status_id' => $dataWork['manage_status_id']
                    ], $detailOld['manage_work_id']);

                    $listTaskChildNew = $mManageWork->getListTaskOfParent($detailOld['manage_work_id']);

                    if (count($listTaskChildOld) != 0) {
                        $listTaskChildOld = collect($listTaskChildOld)->keyBy('manage_work_id');
                        $listTaskChildNew = collect($listTaskChildNew)->keyBy('manage_work_id');

                        foreach ($listTaskChildOld as $keyTask => $itemTask) {
                            $message = __(' đã cập nhật trạng thái công việc con ') . $detailOld['manage_work_title'] . __(' từ ') . $itemTask['manage_status_name'] . __(' sang ') . $listTaskChildNew[$keyTask]['manage_status_name'];
                            $this->createHistory($itemTask['manage_work_id'], $message);
                        }
                    }
                }

                $totalParentProgress = $this->updateProgressParentTask($detailOld);

                if (!isset($data['type_copy_child'])) {
                    if (isset($data['manage_work_id'])) {
                        $checkUpdate = 0;
                        if (isset($data['manage_status_id']) && $data['manage_status_id'] != $detailOld['manage_status_id']) {
                            $mManageStatus = app()->get(ManageStatusTable::class);
                            $oldStatus = $mManageStatus->getItem($detailOld['manage_status_id']);
                            $newStatus = $mManageStatus->getItem($data['manage_status_id']);
                            $dataHistory = [
                                'manage_work_id' => $data['manage_work_id'],
                                'staff_id' => Auth::id(),
                                'message' => __(' đã cập nhật trạng thái công việc con ') . $detailOld['manage_work_title'] . __(' từ ') . $oldStatus['manage_status_name'] . __(' sang ') . $newStatus['manage_status_name'],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                            $this->mManageHistory->createdHistory($dataHistory);
                            $checkUpdate = 1;
                        }

                        if (isset($data['progress']) && $data['progress'] != $detailOld['progress']) {
                            $dataHistory = [
                                'manage_work_id' => $data['manage_work_id'],
                                'staff_id' => Auth::id(),
                                'message' => __(' đã cập nhật tiến độ công việc con ') . $detailOld['manage_work_title'] . __(' từ ') . $detailOld['progress'] . '%' . __(' sang ') . $data['progress'] . '%',
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                            $this->mManageHistory->createdHistory($dataHistory);
                            $checkUpdate = 1;
                        }

                        if (isset($data['processor_id']) && $data['processor_id'] != $detailOld['processor_id']) {
                            $mStaff = app()->get(StaffsTable::class);
                            $oldProcessor = $mStaff->getStaffId($detailOld['processor_id']);
                            $newProcessor = $mStaff->getStaffId($data['processor_id']);
                            $dataHistory = [
                                'manage_work_id' => $data['manage_work_id'],
                                'staff_id' => Auth::id(),
                                'message' => __(' đã cập nhật người thực hiện công việc con ') . $detailOld['manage_work_title'] . __(' từ ') . $oldProcessor['staff_name'] . __(' sang ') . $newProcessor['staff_name'],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                            $this->mManageHistory->createdHistory($dataHistory);
                            $checkUpdate = 1;
                        }
                        if (isset($data['date_end'])) {
                            $date_end = isset($data['time_end']) ? Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d ' . $data['time_end'] . ':00') : Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d 23:00:00');
                            if ($date_end != $detailOld['date_end']) {
                                $dataHistory = [
                                    'manage_work_id' => $data['manage_work_id'],
                                    'staff_id' => Auth::id(),
                                    'message' => __(' đã cập nhật ngày hết hạn công việc con ') . $detailOld['manage_work_title'] . __(' từ ') . Carbon::parse($detailOld['date_end'])->format('H:i:s d/m/Y') . __(' sang ') . Carbon::parse($date_end)->format('H:i:s d/m/Y'),
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id()
                                ];
                                $this->mManageHistory->createdHistory($dataHistory);
                                $checkUpdate = 1;
                            }
                        }

                        if ($checkUpdate == 0) {
                            $dataHistory = [
                                'manage_work_id' => $data['manage_work_id'],
                                'staff_id' => Auth::id(),
                                'message' => __(' đã cập nhật thành công công việc con ') . $detailOld['manage_work_title'],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                            $this->mManageHistory->createdHistory($dataHistory);
                        }
                    } else {
                        $checkUpdate = 0;
                        if (isset($data['manage_status_id']) && $data['manage_status_id'] != $detailOld['manage_status_id']) {
                            $mManageStatus = app()->get(ManageStatusTable::class);
                            $oldStatus = $mManageStatus->getItem($detailOld['manage_status_id']);
                            $newStatus = $mManageStatus->getItem($data['manage_status_id']);
                            $dataHistory = [
                                'manage_work_id' => $data['manage_work_child_id'],
                                'staff_id' => Auth::id(),
                                'message' => __(' đã cập nhật trạng thái công việc ') . $detailOld['manage_work_title'] . __(' từ ') . $oldStatus['manage_status_name'] . __(' sang ') . $newStatus['manage_status_name'],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                            $this->mManageHistory->createdHistory($dataHistory);
                            $checkUpdate = 1;

                            if ($data['manage_status_id'] == 6) {
                                $dataHistory = [
                                    'manage_work_id' => $data['manage_work_child_id'],
                                    'staff_id' => Auth::id(),
                                    'message' => __(' đã cập nhật tiến độ công việc ') . $detailOld['manage_work_title'] . __(' từ ') . $detailOld['progress'] . '%' . __(' sang ') . '100%',
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id()
                                ];
                                $this->mManageHistory->createdHistory($dataHistory);
                            }
                        }

                        if (isset($data['progress']) && $data['progress'] != $detailOld['progress']) {
                            $dataHistory = [
                                'manage_work_id' => $data['manage_work_child_id'],
                                'staff_id' => Auth::id(),
                                'message' => __(' đã cập nhật tiến độ công việc ') . $detailOld['manage_work_title'] . __(' từ ') . $detailOld['progress'] . '%' . __(' sang ') . $data['progress'] . '%',
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                            $this->mManageHistory->createdHistory($dataHistory);
                            $checkUpdate = 1;
                        }

                        if (isset($data['processor_id']) && $data['processor_id'] != $detailOld['processor_id']) {
                            $mStaff = app()->get(StaffsTable::class);
                            $oldProcessor = $mStaff->getStaffId($detailOld['processor_id']);
                            $newProcessor = $mStaff->getStaffId($data['processor_id']);
                            $dataHistory = [
                                'manage_work_id' => $data['manage_work_child_id'],
                                'staff_id' => Auth::id(),
                                'message' => __(' đã cập nhật người thực hiện công việc ') . $detailOld['manage_work_title'] . __(' từ ') . $oldProcessor['staff_name'] . __(' sang ') . $newProcessor['staff_name'],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                            $this->mManageHistory->createdHistory($dataHistory);
                            $checkUpdate = 1;
                        }

                        if (isset($data['date_end'])) {
                            $date_end = isset($data['time_end']) ? Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d ' . $data['time_end'] . ':00') : Carbon::createFromFormat('d/m/Y', $data['date_end'])->format('Y-m-d 23:00:00');
                            if ($date_end != $detailOld['date_end']) {
                                $dataHistory = [
                                    'manage_work_id' => $data['manage_work_child_id'],
                                    'staff_id' => Auth::id(),
                                    'message' => __(' đã cập nhật ngày hết hạn công việc ') . $detailOld['manage_work_title'] . __(' từ ') . Carbon::parse($detailOld['date_end'])->format('H:i:s d/m/Y') . __(' sang ') . Carbon::parse($date_end)->format('H:i:s d/m/Y'),
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id()
                                ];
                                $this->mManageHistory->createdHistory($dataHistory);
                                $checkUpdate = 1;
                            }
                        }

                        if ($checkUpdate == 0) {
                            $dataHistory = [
                                'manage_work_id' => $data['manage_work_child_id'],
                                'staff_id' => Auth::id(),
                                'message' => __(' đã cập nhật thành công công việc ') . $detailOld['manage_work_title'],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                            $this->mManageHistory->createdHistory($dataHistory);
                        }
                    }
                }

                DB::commit();

                if (!isset($data['type_copy_child'])) {
                    if (isset($dataWork['manage_status_id']) && isset($detailOld) && $detailOld['manage_status_id'] != $dataWork['manage_status_id']) {
                        $dataNoti = [
                            'key' => 'work_update_status',
                            'object_id' => $idWork,
                        ];
                        $sendNoti->sendStaffNotification($dataNoti);

                        if ($data['manage_status_id'] == 3) {
                            $dataNoti = [
                                'key' => 'work_finish',
                                'object_id' => $idWork,
                            ];

                            $sendNoti->sendStaffNotification($dataNoti);
                        }
                    }
                }
            } else {
                if (isset($data['date_start'])) {
                    $date_start = Carbon::createFromFormat('d/m/Y H:i', $data['date_start'])->format('Y-m-d H:i:00');
                    $date_end = Carbon::createFromFormat('d/m/Y H:i', $data['date_end'])->format('Y-m-d H:i:00');

                    if (!isset($data['date_start'])) {
                        if ($date_start > $date_end) {
                            return [
                                'error' => true,
                                'message' => __('Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc')
                            ];
                        }
                    }
                }

                if (!isset($data['type_copy_child']) && !isset($data['type_copy'])) {
                    $date_end = Carbon::createFromFormat('d/m/Y H:i', $data['date_end'])->format('Y-m-d H:i:00');
                }

                $mManageRemind = new ManageRedmindTable();
                $messageErrorRemind = null;
                if (isset($data['date_remind']) || isset($data['staff']) || (isset($data['description_remind']) && strlen(strip_tags($data['description_remind'])) != 0)) {

                    if (!isset($data['staff'])) {
                        $messageErrorRemind = $messageErrorRemind . __('Vui lòng chọn nhân viên được nhắc <br>');
                    }
                    if (!isset($data['date_remind'])) {
                        $messageErrorRemind = $messageErrorRemind . __('Vui lòng chọn thời gian nhắc <br>');
                    }
                    if (isset($data['description_remind']) && strlen(strip_tags($data['description_remind'])) == 0) {
                        $messageErrorRemind = $messageErrorRemind . __('Vui lòng nhập nội dung nhắc <br>');
                    }
                }


                if ($messageErrorRemind != null) {
                    return [
                        'error' => true,
                        'message' => $messageErrorRemind
                    ];
                }

                if (isset($data['date_remind']) && count($data['staff']) != 0 && strlen(strip_tags($data['description_remind'])) != 0) {
                    $data['time_remind'] = str_replace(',', '', $data['time_remind']);
                    $messageError = $this->checkRemind($data);
                    if ($messageError != null) {
                        return [
                            'error' => true,
                            'message' => $messageError
                        ];
                    }
                }

                if (isset($data['type_card_work']) && $data['type_card_work'] == 'kpi') {
                    $data['is_approve_id'] = 1;
                }

                $mStaff = app()->get(StaffsTable::class);

                $detailStaff = $mStaff->getDetail($data['processor_id']);

                $dataWork = [
                    'manage_type_work_id' => $data['manage_type_work_id'],
                    'manage_work_title' => strip_tags($data['manage_work_title']),
                    'date_end' => Carbon::createFromFormat('d/m/Y H:i', $data['date_end'])->format('Y-m-d H:i:00'),
                    'processor_id' => $data['processor_id'],
                    'time' => isset($data['time']) ? strip_tags($data['time']) : null,
                    'branch_id' => isset($data['branch_id']) ? $data['branch_id'] : $detailStaff['branch_id'],
                    'time_type' => isset($data['time_type']) ? $data['time_type'] : null,
                    'progress' => isset($data['progress']) ? $data['progress'] : 0,
                    'customer_id' => isset($data['customer_id']) ? strip_tags($data['customer_id']) : null,
                    'description' => isset($data['description']) ? $data['description'] : null,
                    'approve_id' => isset($data['approve_id']) ? $data['approve_id'] : null,
                    'is_approve_id' => isset($data['is_approve_id']) && !isset($data['type_copy']) ? 1 : (isset($data['type_copy']) ? $data['is_approve_id'] : 0),
                    'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
                    'type_card_work' => isset($data['type_card_work']) ? $data['type_card_work'] : null,
                    'priority' => isset($data['priority']) ? $data['priority'] : null,
                    'manage_status_id' => isset($data['manage_status_id']) ? $data['manage_status_id'] : null,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                    'create_object_type' => isset($data['create_object_type']) ? $data['create_object_type'] : null,
                    'create_object_id' => isset($data['create_object_id']) ? $data['create_object_id'] : null
                ];

                if (isset($data['manage_project_id'])) {
                    $dataWork['manage_project_id'] = $data['manage_project_id'];
                }

                if (isset($data['manage_project_phase_id'])) {
                    $dataWork['manage_project_phase_id'] = $data['manage_project_phase_id'];
                } else {
                    if (isset($data['manage_project_id'])) {
                        $mManageProjectPhase = app()->get(ManageProjectPhareTable::class);
                        $phaseDefault = $mManageProjectPhase->getDefault($data['manage_project_id']);
                        if ($phaseDefault != null){
                            $dataWork['manage_project_phase_id'] = $phaseDefault['manage_project_phase_id'];
                        }
                    }
                }

                if (isset($data['manage_project_issue_id'])) {
                    $dataWork['manage_project_issue_id'] = $data['manage_project_issue_id'];
                }

                if (isset($data['manage_work_customer_type'])) {
                    $dataWork['manage_work_customer_type'] = $data['manage_work_customer_type'];
                }

                if (isset($data['repeat_type']) && $data['repeat_type'] != 'none') {
                    $dataWork['repeat_type'] = isset($data['repeat_type']) && $data['repeat_type'] != 'none' ? $data['repeat_type'] : null;
                    $dataWork['repeat_end'] = isset($data['repeat_end']) && isset($data['repeat_type']) && $data['repeat_type'] != 'none' ? $data['repeat_end'] : null;
                    $dataWork['repeat_time'] = isset($data['repeat_time']) && isset($data['repeat_type']) && $data['repeat_type'] != 'none' ? $data['repeat_time'] : null;
                    $dataWork['repeat_end_time'] = isset($data['repeat_end']) && $data['repeat_end'] == 'after' && isset($data['repeat_type']) && $data['repeat_type'] != 'none' ? str_replace(',', '', $data['repeat_end_time']) : null;
                    $dataWork['repeat_end_type'] = isset($data['repeat_end']) && $data['repeat_end'] == 'after' && isset($data['repeat_type']) && $data['repeat_type'] != 'none' ? $data['repeat_end_type'] : null;
                    $dataWork['repeat_end_full_time'] = isset($data['repeat_end']) && $data['repeat_end'] == 'date' && isset($data['repeat_type']) && $data['repeat_type'] != 'none' ? Carbon::createFromFormat('d/m/Y', $data['repeat_end_full_time'])->format('Y-m-d 00:00:00') : null;
                } else {
                    $dataWork['repeat_type'] = null;
                    $dataWork['repeat_end'] = null;
                    $dataWork['repeat_time'] = null;
                    $dataWork['repeat_end_time'] = null;
                    $dataWork['repeat_end_type'] = null;
                    $dataWork['repeat_end_full_time'] = null;
                }

                if (isset($data['date_start'])) {
                    $dataWork['date_start'] = Carbon::createFromFormat('d/m/Y H:i', $data['date_start'])->format('Y-m-d H:i:00');
                }

                if (!isset($data['manage_work_child_id'])) {
                    $dataWork['created_by'] = Auth::id();
                    $dataWork['created_at'] = Carbon::now();
//                    $dataWork['manage_work_code'] = $this->codeWork();
                    $dataWork['manage_work_code'] = isset($dataWork['manage_project_id']) ? $this->codeWorkProject($dataWork['manage_project_id']) : $this->codeWork();
                    $dataWork['assignor_id'] = Auth::id();
                    $idWork = $mManageWork->createdWork($dataWork);
                    $detailOld = null;

                    $message = __(' đã tạo thành công công việc ') . $data['manage_work_title'];
                    $this->createHistory($idWork, $message);

                    if ($dataWork['parent_id'] != null) {
                        $message = __(' đã tạo thành công công việc con ') . $data['manage_work_title'];
                        $this->createHistory($dataWork['parent_id'], $message);
                    }
                } else {

                    $idWork = $data['manage_work_child_id'];
                    $detailOld = $this->getDetail($idWork);
                    if ($detailOld['is_parent'] == 0 && isset($data['manage_status_id']) && $data['manage_status_id'] == 6 && $detailOld['manage_status_id'] != 6) {
                        $dataWork['date_finish'] = Carbon::now();
                        $dataWork['progress'] = 100;
                    }
                    $mManageWork->updateWork($dataWork, $idWork);

                    if ($dataWork['manage_status_id'] == 7 && $detailOld['parent_id'] == null) {
                        $listTaskChildOld = $mManageWork->getListTaskOfParent($detailOld['manage_work_id']);
                        $mManageWork->updateByParentId([
                            'manage_status_id' => $dataWork['manage_status_id']
                        ], $detailOld['manage_work_id']);

                        $listTaskChildNew = $mManageWork->getListTaskOfParent($detailOld['manage_work_id']);

                        if (count($listTaskChildOld) != 0) {
                            $listTaskChildOld = collect($listTaskChildOld)->keyBy('manage_work_id');
                            $listTaskChildNew = collect($listTaskChildNew)->keyBy('manage_work_id');

                            foreach ($listTaskChildOld as $keyTask => $itemTask) {
                                $messageTask = __(' đã cập nhật trạng thái công việc con ') . $detailOld['manage_work_title'] . __(' từ ') . $itemTask['manage_status_name'] . __(' sang ') . $listTaskChildNew[$keyTask]['manage_status_name'];
                                $this->createHistory($itemTask['manage_work_id'], $messageTask);
                            }
                        }
                    }

                    $totalParentProgress = $this->updateProgressParentTask($detailOld);

                    if (isset($data['manage_work_id'])) {

                        $checkUpdate = 0;
                        if (isset($data['manage_status_id']) && $data['manage_status_id'] != $detailOld['manage_status_id']) {
                            $mManageStatus = app()->get(ManageStatusTable::class);
                            $oldStatus = $mManageStatus->getItem($detailOld['manage_status_id']);
                            $newStatus = $mManageStatus->getItem($data['manage_status_id']);

                            $message = __(' đã cập nhật trạng thái công việc con ') . $data['manage_work_title'] . __(' từ ') . $oldStatus['manage_status_name'] . __(' sang ') . $newStatus['manage_status_name'];
                            $this->createHistory($data['manage_work_id'], $message);

                            $message = __(' đã cập nhật trạng thái công việc ') . $data['manage_work_title'] . __(' từ ') . $oldStatus['manage_status_name'] . __(' sang ') . $newStatus['manage_status_name'];
                            $this->createHistory($idWork, $message);

                            $checkUpdate = 1;

                            if ($data['manage_status_id'] == 6) {
                                $mManageStatus = app()->get(ManageStatusTable::class);

                                $message = __(' đã cập nhật tiến độ công việc con ') . $data['manage_work_title'] . __(' từ ') . $detailOld['progress'] . '%' . __(' sang ') . '100%';
                                $this->createHistory($data['manage_work_id'], $message);

                                $message = __(' đã cập nhật tiến độ công việc ') . $data['manage_work_title'] . __(' từ ') . $detailOld['progress'] . '%' . __(' sang ') . '100%';
                                $this->createHistory($idWork, $message);
                            }
                        }

                        if (isset($data['progress']) && $data['progress'] != $detailOld['progress']) {
                            $message = __(' đã cập nhật tiến độ công việc con ') . $data['manage_work_title'] . __(' từ ') . $detailOld['progress'] . '%' . __(' sang ') . $data['progress'] . '%';
                            $this->createHistory($data['manage_work_id'], $message);

                            $message = __(' đã cập nhật tiến độ công việc ') . $data['manage_work_title'] . __(' từ ') . $detailOld['progress'] . '%' . __(' sang ') . $data['progress'] . '%';
                            $this->createHistory($idWork, $message);

                            $checkUpdate = 1;
                        }

                        if (isset($data['processor_id']) && $data['processor_id'] != $detailOld['processor_id']) {
                            $mStaff = app()->get(StaffsTable::class);
                            $oldProcessor = $mStaff->getStaffId($detailOld['processor_id']);
                            $newProcessor = $mStaff->getStaffId($data['processor_id']);
                            $message = __(' đã cập nhật người thực hiện công việc con ') . $data['manage_work_title'] . __(' từ ') . $oldProcessor['staff_name'] . __(' sang ') . $newProcessor['staff_name'];
                            $this->createHistory($data['manage_work_id'], $message);

                            $message = __(' đã cập nhật người thực hiện công việc ') . $data['manage_work_title'] . __(' từ ') . $oldProcessor['staff_name'] . __(' sang ') . $newProcessor['staff_name'];
                            $this->createHistory($idWork, $message);

                            $checkUpdate = 1;
                        }

                        if (isset($data['date_end']) && $date_end != $detailOld['date_end']) {
                            $message = __(' đã cập nhật ngày hết hạn công việc con ') . $data['manage_work_title'] . __(' từ ') . Carbon::parse($detailOld['date_end'])->format('H:i:s d/m/Y') . __(' sang ') . Carbon::parse($date_end)->format('H:i:s d/m/Y');
                            $this->createHistory($data['manage_work_id'], $message);

                            $message = __(' đã cập nhật ngày hết hạn công việc ') . $data['manage_work_title'] . __(' từ ') . Carbon::parse($detailOld['date_end'])->format('H:i:s d/m/Y') . __(' sang ') . Carbon::parse($date_end)->format('H:i:s d/m/Y');
                            $this->createHistory($idWork, $message);

                            $checkUpdate = 1;
                        }

                        if ($checkUpdate == 0) {
                            $message = __(' đã cập nhật thành công công việc con ') . $data['manage_work_title'];
                            $this->createHistory($data['manage_work_id'], $message);

                            $message = __(' đã cập nhật thành công công việc ') . $data['manage_work_title'];
                            $this->createHistory($idWork, $message);
                        }
                    } else {
                        $checkUpdate = 0;
                        if (isset($data['manage_status_id']) && $data['manage_status_id'] != $detailOld['manage_status_id']) {
                            $mManageStatus = app()->get(ManageStatusTable::class);
                            $oldStatus = $mManageStatus->getItem($detailOld['manage_status_id']);
                            $newStatus = $mManageStatus->getItem($data['manage_status_id']);

                            $message = __(' đã cập nhật trạng thái công việc ') . $data['manage_work_title'] . __(' từ ') . $oldStatus['manage_status_name'] . __(' sang ') . $newStatus['manage_status_name'];
                            $this->createHistory($data['manage_work_child_id'], $message);
                            $checkUpdate = 1;
                        }

                        if (isset($data['progress']) && $data['progress'] != $detailOld['progress']) {
                            $message = __(' đã cập nhật tiến độ công việc ') . $data['manage_work_title'] . $data['manage_work_title'] . __(' từ ') . $detailOld['progress'] . '%' . __(' sang ') . $data['progress'] . '%';
                            $this->createHistory($data['manage_work_child_id'], $message);
                            $checkUpdate = 1;
                        }

                        if (isset($data['processor_id']) && $data['processor_id'] != $detailOld['processor_id']) {
                            $mStaff = app()->get(StaffsTable::class);
                            $oldProcessor = $mStaff->getStaffId($detailOld['processor_id']);
                            $newProcessor = $mStaff->getStaffId($data['processor_id']);

                            $message = __(' đã cập nhật người thực hiện công việc ') . $data['manage_work_title'] . __(' từ ') . $oldProcessor['staff_name'] . __(' sang ') . $newProcessor['staff_name'];
                            $this->createHistory($data['manage_work_child_id'], $message);
                            $checkUpdate = 1;
                        }

                        if (isset($data['date_end']) && $date_end != $detailOld['date_end']) {
                            $message = __(' đã cập nhật ngày hết hạn công việc ') . $data['manage_work_title'] . __(' từ ') . Carbon::parse($detailOld['date_end'])->format('H:i:s d/m/Y') . __(' sang ') . Carbon::parse($date_end)->format('H:i:s d/m/Y');
                            $this->createHistory($data['manage_work_child_id'], $message);
                            $checkUpdate = 1;
                        }

                        if ($checkUpdate == 0) {
                            $message = __(' đã cập nhật thành công công việc ') . $data['manage_work_title'];
                            $this->createHistory($data['manage_work_child_id'], $message);
                        }
                    }
                }

                $dataTime = [];
                if (isset($data['repeat_type'])) {
                    if ($data['repeat_type'] == 'weekly') {
                        $checkNull = 0;
                        foreach ($data['manage_repeat_time_weekly'] as $item) {
                            if ($item == null) {
                                $checkNull++;
                            }
                            if ($item != null) {
                                $dataTime[] = [
                                    'manage_work_id' => $idWork,
                                    'time' => $item,
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id(),
                                ];
                            }
                        }

                        if (count($data['manage_repeat_time_weekly']) == $checkNull) {
                            DB::rollBack();
                            return [
                                'error' => true,
                                'message' => __('Vui lòng chọn ngày trong tuần')
                            ];
                        }
                    } else if ($data['repeat_type'] == 'monthly') {
                        $checkNull = 0;
                        foreach ($data['manage_repeat_time_monthly'] as $item) {
                            if ($item == null) {
                                $checkNull++;
                            }
                            if ($item != null) {
                                $dataTime[] = [
                                    'manage_work_id' => $idWork,
                                    'time' => $item,
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id(),
                                ];
                            }
                        }

                        if (count($data['manage_repeat_time_monthly']) == $checkNull) {
                            return [
                                'error' => true,
                                'message' => __('Vui lòng chọn ngày trong tháng')
                            ];
                        }
                    }
                }

                $mManageRepeatTime->removeRepeatTime($idWork);
                if (count($dataTime) != 0) {
                    $mManageRepeatTime->insertTime($dataTime);
                }

                $mManageWorkSupport->remove($idWork);
                if (isset($data['support'])) {
                    $dataSupport = [];
                    foreach ($data['support'] as $item) {
                        $dataSupport[] = [
                            'manage_work_id' => $idWork,
                            'staff_id' => $item,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id(),
                        ];
                    }

                    if (count($dataSupport) != 0) {
                        $mManageWorkSupport->insertArrSupport($dataSupport);
                    }
                }

                $mManageTag = app()->get(ManageTagsTable::class);
                $mManageWorkTag->removeWorkTag($idWork);
                if (isset($data['manage_tag'])) {
                    $dataTag = [];
                    foreach ($data['manage_tag'] as $item) {

                        $checkTag = $mManageTag->checkExist($item);

                        if ($checkTag != null) {
                            $idTag = $checkTag['manage_tag_id'];
                        } else {
                            $idTag = $mManageTag->add([
                                'manage_tag_name' => $item,
                                'is_active' => 1,
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id(),
                            ]);
                        }

                        $dataTag[] = [
                            'manage_work_id' => $idWork,
                            'manage_tag_id' => $idTag,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id(),
                        ];
                    }

                    if (count($dataTag) != 0) {
                        $mManageWorkTag->insertArrTag($dataTag);
                    }
                }

                $mStaff = app()->get(StaffsTable::class);

                if (isset($data['date_remind']) && count($data['staff']) != 0 && strlen(strip_tags($data['description_remind'])) != 0) {
                    $dataRemind = [];
                    foreach ($data['staff'] as $item) {
                        $created_by = $mStaff->getStaffId(Auth::id());
                        $staff_id = $mStaff->getStaffId($item);
                        if (isset($idWork)) {
                            $detailWork = $mManageWork->getDetail($idWork);
                            $title = $created_by['staff_name'] . ' ' . __('managerwork::managerwork.created_remind_work_for', ['manage_work_title' => $detailWork['manage_work_title']]) . ' ' . $staff_id['staff_name'];
                        } else {
                            $title = $created_by['staff_name'] . ' ' . __('managerwork::managerwork.created_remind_for') . ' ' . $staff_id['staff_name'];
                        }

                        $dataRemind[] = [
                            'title' => $title,
                            'staff_id' => $item,
                            'manage_work_id' => $idWork,
                            'date_remind' => Carbon::createFromFormat('d/m/Y H:i', $data['date_remind'])->format('Y-m-d H:i:00'),
                            'time' => isset($data['time_remind']) ? $data['time_remind'] : null,
                            'time_type' => isset($data['time_remind']) ? $data['time_type_remind'] : null,
                            'description' => strip_tags($data['description_remind']),
                            'is_sent' => 0,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id()
                        ];
                    }

                    $mManageRemind->insertArrayRemind($dataRemind);
                }

                if (isset($data['file']) && count($data['file']) != 0) {
                    $mManageDocumentFile = app()->get(ManagerDocumentFileTable::class);
                    foreach ($data['file'] as $itemFile) {
                        $dataImage = [
                            'file_name' => strip_tags($itemFile['file_name_work']),
                            'manage_work_id' => $idWork,
                            'path' => $itemFile['path_work'],
                            'file_type' => $itemFile['file_type_work'],
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id()
                        ];
                        $idFile = $mManageDocumentFile->createdFileDocument($dataImage);
                    }

                    $dataHistory = [
                        'manage_work_id' => $idWork,
                        'staff_id' => Auth::id(),
                        'message' => __(' đã cập nhật file hồ sơ thành công'),
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];

                    $this->mManageHistory->createdHistory($dataHistory);
                }

                DB::commit();
                if (!isset($data['type_copy_child'])) {
                    if ($detailOld != null) {
                        if (isset($dataWork['manage_status_id']) && isset($detailOld) && $detailOld['manage_status_id'] != $dataWork['manage_status_id']) {

                            $dataNoti = [
                                'key' => 'work_update_status',
                                'object_id' => $idWork,
                            ];

                            $sendNoti->sendStaffNotification($dataNoti);

                            if ($data['manage_status_id'] == 3) {
                                $dataNoti = [
                                    'key' => 'work_finish',
                                    'object_id' => $idWork,
                                ];

                                $sendNoti->sendStaffNotification($dataNoti);
                            }
                        }

                        if (isset($dataWork['processor_id']) && (isset($detailOld) && $detailOld['processor_id'] != $dataWork['processor_id']) && !isset($detailOld)) {
                            $dataNoti = [
                                'key' => 'work_assign',
                                'object_id' => $idWork,
                            ];
                            $sendNoti->sendStaffNotification($dataNoti);
                        }

                        if (isset($dataWork['description']) && isset($detailOld) && $detailOld['description'] != $dataWork['description']) {
                            $dataNoti = [
                                'key' => 'work_update_description',
                                'object_id' => $idWork,
                            ];
                            $sendNoti->sendStaffNotification($dataNoti);
                        }
                    } else {
                        $dataNoti = [
                            'key' => 'work_assign',
                            'object_id' => $idWork,
                        ];
                        $test = $sendNoti->sendStaffNotification($dataNoti);
                    }
                }
            }

            $detailWork = $mManageWork->getDetail($idWork);

            return [
                'error' => false,
                'message' => __('Lưu công việc thành công'),
                'manage_work_id' => $idWork,
                'parent_progress' => $totalParentProgress,
                'data' => [
                    'manage_work_id' => $idWork,
                    'manage_work_code' => isset($detailWork['manage_project_id']) && $detailWork['manage_project_id'] != null ?: $this->codeWork(),
                ],// data for chathub
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Lưu công việc thất bại'),
                '__message' => $e->getMessage(),
                '__line' => $e->getLine(),
                '__file' => $e->getFile(),
            ];
        }
    }

    public function updateProgressParentTask($detailOld){
        $mManageWork = app()->get(ManagerWorkTable::class);
        $processTotal = 0;
        if ($detailOld['parent_id'] != null){
            $totalChildProgress = $mManageWork->getTotalChildProgress($detailOld['parent_id']);
            if ($totalChildProgress != null && $totalChildProgress['sum_work'] != 0 && $totalChildProgress['total_progress'] != 0){
                $processTotal = round($totalChildProgress['total_progress']/$totalChildProgress['sum_work'] , 0);
            }

            $mManageWork->updateWork(['progress' => $processTotal],$detailOld['parent_id']);
        }

        return $processTotal;
    }

    /**
     * Tạo lịch sử
     */
    public function createHistory($manage_work_id, $message)
    {
        $dataHistory = [
            'manage_work_id' => $manage_work_id,
            'staff_id' => Auth::id(),
            'message' => $message,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id()
        ];
        $this->mManageHistory->createdHistory($dataHistory);
    }

    public function codeWork()
    {
        $codeWork = 'CV_' . Carbon::now()->format('Ymd') . '_';
        $workCodeDetail = $this->managerWork->getCodeWork($codeWork);

        if ($workCodeDetail == null) {
            return $codeWork . '001';
        } else {
            $arr = explode($codeWork, $workCodeDetail);
            $value = strval(intval($arr[1]) + 1);
            $zero_str = "";
            if (strlen($value) < 7) {
                for ($i = 0; $i < (3 - strlen($value)); $i++) {
                    $zero_str .= "0";
                }
            }
            return $codeWork . $zero_str . $value;
        }
    }


    /**
     * Xoá công việc
     * @param $data
     * @return mixed|void
     */
    public function removeWork($data)
    {
        try {
            $idWork = $data['manage_work_id'];
            $mManageWork = new ManagerWorkTable();
            $mManageRepeatTime = new ManageRepeatTimeTable();
            $mManageWorkTag = new ManagerWorkTagTable();
            $mManageRemind = new ManageRedmindTable();
            $mManageWorkSupport = new ManageWorkSupportTable();

            $mManageWork->remove($idWork);
            $mManageRepeatTime->removeRepeatTime($idWork);
            $mManageWorkTag->removeWorkTag($idWork);
            $mManageRemind->removeByWorkId($idWork);
            $mManageWorkSupport->remove($idWork);

            return [
                'error' => false,
                'message' => __('Xoá công việc thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá công việc thất bại')
            ];
        }
    }

    /**
     * Danh sách trạng thái
     * @return mixed
     */
    public function getListStatus()
    {
        $mManageStatus = new ManageStatusTable();
        return $mManageStatus->getAll();
    }

    /**
     * Lấy danh sách công việc con
     * @param $data
     * @return mixed|void
     */
    public function searchWork($data)
    {
        try {

            $mManageWork = new ManagerWorkTable();

            $listWorkChild = $mManageWork->getListWorkChild($data);

            $view = view('manager-project::work.append.append-list-work-child', ['listWorkChild' => $listWorkChild])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                'message' => __('Lấy danh sách thất bại')
            ];
        }
    }

    public function copyWork($id)
    {
        return $this->managerWork->getDetail($id);
    }

    public function getListByProject($id)
    {
        return $this->managerWork->getListByProject($id);
    }

    public function export($data)
    {

        $heading = [];

        foreach ($data['listColumn'] as $ic) {
            if ($ic['active'] != 1 || in_array($ic['name'],['Chức năng','Action'])) continue;
            $heading[] = $ic['nameConfig'];
        }
        //        $heading = [
        //            __('TÊN NHÂN VIÊN'),
        //            __('SỐ ĐIỆN THOẠI'),
        //            __('ĐỊA CHỈ'),
        //            __('CHI NHÁNH'),
        //            __('CHỨC VỤ'),
        //            __('LƯƠNG'),
        //            __('TRỢ CẤP'),
        //            __('TỈ LỆ HOA HỒNG')
        //        ];

        //        dd($data['listColumn']);

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $myData = [];
        //Lấy thông tin tất cả nv

        if (count($data['list']) > 0) {
            $i = 1;
            foreach ($data['list'] as $item) {
                $parse_column = [
                    'id' => $item->manage_work_id,
                    'count' => isset($page) ? ($page - 1) * 10 + $i + 1 : $i + 1,
//                    'manage_type_work_icon' => $item['manage_type_work_icon'] != '' ? ($item['manage_type_work_icon']) : asset('static/backend/images/service-card/default/hinhanh-default3.png'),
                    'manage_type_work_icon' => $item['manage_type_work_name'] != '' ? ($item['manage_type_work_name']) : '',
                    'manage_work_title' => $item->manage_work_title,
                    'manage_status_id' => $item->manage_status_id,
                    'manage_status_name' => $item->manage_status_name,
                    'manage_color_code' => $item->manage_color_code,
                    'manage_work_code' => $item->manage_work_code,
                    'manage_project_name' => $item->manage_project_name,
                    'priority' => $item->priority == 1 ? __('Cao') : ($item->priority == 2 ? __('Bình thường') : ($item->priority == 3 ? __('Thấp') : '')),
                    'progress' => $item->progress == 0 ? '0' : $item->progress,
                    'processor_id' => (isset($item->processor->full_name))? $item->processor->full_name:'',
                    'date_estimated' => isset($item->date_estimated) && $item->date_estimated != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($item->date_estimated)->format('d/m/Y H:i') : '',
                    'date_start' => $item->date_start != null ? \Carbon\Carbon::parse($item->date_start)->format('d/m/Y H:i') : \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i'),
                    'date_end' => \Carbon\Carbon::parse($item->date_end)->format('d/m/Y H:i'),
                    'created_by' => $item->created_by,
                    'approve_name' => $item->approve_name,
                    'updated_name' => $item->updated_name,
                    'created_name' => $item->created_name,
                    'is_edit' => $item->is_edit,
                    'is_deleted' => $item->is_deleted,
                    'type_card_work' => $item->type_card_work == 'bonus' ? __('Thường') : 'KPI',
                    'created_at' => isset($item->created_at) && $item->created_at != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : '',
                    'updated_at' => isset($item->updated_at) && $item->updated_at != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') : '',
                    'date_finish' =>  isset($item->date_finish) && $item->date_finish != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($item->date_finish)->format('d/m/Y H:i') : '',
                    'customer_name' => $item->customer_name,
                    'time' => $item->time.' '.($item->time_type == 'd' ? __('ngày') : __('giờ')),
                    'manage_work_support_id' => isset($listSupport[$item['manage_work_id']]) ? implode(', ',$listSupport[$item['manage_work_id']]) : '',
                    'tag' => isset($listTag[$item['manage_work_id']]) ? implode(', ',$listTag[$item['manage_work_id']]) : ''
                ];
                $j = 1;
                foreach ($data['listColumn'] as $column) {
                    if ($column['active'] == 1 && !in_array($column['name'],['Chức năng','Action'])) {
                        $name = $column['column_name'];
                        if ($name == 'count') {

                            $value = $i;
                        } else {
                            $value = $parse_column[$name];
                        }
                        $myData[$i][$j] = $value;
                        $j++;
                    }
                }
                $i++;
            }
        }

        return Excel::download(new ExportFile($heading, $myData), 'export-work.xlsx');
    }

    /**
     * Lấy danh sách khách hàng
     * @param $data
     * @return mixed|void
     */
    public function changeCustomer($data)
    {
        try {

            $customerLead = new CustomerLeadTable();
            $customer = new Customers();
            $mDeal = new CustomerDealTable();
            $mManageWork = new ManagerWorkTable();
            $arrCustomer = [];

            $detail = null;

            if (isset($data['manage_work_id'])) {
                $detail = $mManageWork->getDetail($data['manage_work_id']);
            }
            $data['typeCustomer'] = $data['typeCustomer'] ?? 'customer';
            if ($data['typeCustomer'] == 'lead') {
                $arrCustomer = $customerLead->getAllCustomerLead([]);
            } else if ($data['typeCustomer'] == 'deal') {
                $arrCustomer = $mDeal->getAll();
            } else if ($data['typeCustomer'] == 'customer') {
                $arrCustomer = $customer->getAll();
            }

            $view = view('manager-project::work.append.option-select-customer', ['arrCustomer' => $arrCustomer, 'typeCustomer' => $data['typeCustomer'], 'detail' => $detail])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {

            return [
                'error' => false,
                'message' => __('Lấy danh sách khách hàng bị lỗi')
            ];
        }
    }

    /**
     * Popup chuyển folder
     * @param $data
     * @return mixed|void
     */
    public function popupChangeFolder($data)
    {
        try {

            $mManageDocumentFile = app()->get(ManageProjectDocumentTable::class);

            $detail = $mManageDocumentFile->getDetail($data['manage_project_document_id']);

            if (session()->has('access_token')) {
                $access_token = session()->get('access_token');
            } else {
                $access_token = '';
            }

            $brandCode = session()->get('brand_code');

            $mConfig = app()->get(FileMinioConfigTable::class);

            $detailConfig = $mConfig->getLastConfig();

            $view = view('manager-project::work.popup.popup-change-folder', ['detail' => $detail, 'access_token' => $access_token,'brandCode' => $brandCode,'detailConfig' => $detailConfig])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            
            return [
                'error' => false,
                'message' => __('Hiển thị popup thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lưu di chuyển tài liệu
     * @param $data
     * @return mixed|void
     */
    public function submitChangeFolder($data)
    {
        try {

            $apiManageFile = app()->get(ManageFileApi::class);

            $login = $apiManageFile->loginManageFIle();

            if (isset($login['access_token'])) {
                $file = [
                    'token' => $login['access_token'],
//                    'file_path' => $data['file_path'],
                    'file_path' => $data['file_path'],
                    'new_name' => $data['new_folder_display_name'],
//                    'file_path' => env('FOLDER').'/'.$data['old_folder_display_name'],
                    'folder_name' => $data['name_folder_change'],
                    'old_folder_display_name' => 'quản lý công việc',
                    'new_folder_display_name' => $data['name_folder_display_change_text'],
                    'password' => $data['password'],
                    'is_delete' => 0,
                    'brand-code' => session()->get('brand_code')
                ];
                $moveFile = $apiManageFile->moveFile($file);
                if ($moveFile == null) {
                    return [
                        'error' => false,
                        'message' => __('Di chuyển file thành công'),
                    ];
                } else {
                    return [
                        'error' => true,
                        'message' => $moveFile['message'],
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'message' => $login['message'],
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lưu thông tin thất bại'),
                '__message' => $e->getMessage(),
                '__line' => $e->getLine(),
            ];
        }
    }

    public function showPopupStaff($data)
    {
        try {
            $view = 'manager-project::work.popup.popup-list-staff';
            $getData = $this->getDataSearchListStaff($data,$view);

            return [
                'error' => false,
                'view' => $getData
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại'),
                '__message' => $e->getMessage(),
                '__line' => $e->getLine(),
                '__file' => $e->getFile(),
            ];
        }
    }

    public function searchPagePopupStaff($data)
    {
        try {

            $view = 'manager-project::work.append.get-list-staff';

            $getData = $this->getDataSearchListStaff($data,$view);

            return [
                'error' => false,
                'view' => $getData
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại'),
                '__message' => $e->getMessage(),
                '__line' => $e->getLine(),
                '__file' => $e->getFile(),
            ];
        }
    }

    public function getDataSearchListStaff($data,$viewTmp){
        $mManageWork = app()->get(ManagerWorkTable::class);
        $manageWorkSupport = app()->get(ManageWorkSupportTable::class);
        $mStaff = app()->get(StaffsTable::class);

        $manageWorkId = $data['manage_work_id'];

        $staffId = [];

        $detailWork = $mManageWork->getItem($manageWorkId);

        $staffId[] = $detailWork['processor_id'];

        $listSupport = $manageWorkSupport->getListSupport($manageWorkId);

        if (count($listSupport) != 0) {
            $listSupport = collect($listSupport)->pluck('staff_id')->toArray();
            $staffId = array_merge($staffId,$listSupport);
        }

        $data['list_staff'] = $staffId;

        $listStaff = $mStaff->getList($data);

        $view = view($viewTmp, ['listStaff' => $listStaff,'processor_id' => $detailWork['processor_id'],'manage_work_id' => $manageWorkId])->render();
        return $view;
    }

    /**
     * Lấy danh sách nhân viên khi thay đổi chi nhánh
     * @param $data
     * @return mixed|void
     */
    public function changeBranchStaff($data)
    {
        $mStaff = new StaffsTable();
        $listStaff = $mStaff->getAll($data);
        $view = view('manager-project::work.append.option-staff',[
            'listStaff' => $listStaff
        ])->render();

        return [
            'error' => false,
            'view' => $view
        ];
    }

    /**
     * Kiểm tra số lượng công việc con
     * @param $data
     * @return mixed|void
     */
    public function checkWorkChild($data)
    {
        try {
            $detail = $this->getDetailWork($data['id']);
            return [
                'error' => false,
                'total_child' => $detail != null ? $detail['total_child_job'] : 0
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Công việc không thể xóa')
            ];
        }
    }

    public function codeWorkProject($manageProjectId){
        $manageProject = app()->get(ProjectTable::class);

        $detailProject = $manageProject->getDetailProject($manageProjectId);

//        $codeWork = $detailProject['prefix_code'].'_' . Carbon::now()->format('Ymd') . '_';
        $codeWork = $detailProject['prefix_code'].'_';
        $workCodeDetail = $this->managerWork->getCodeWork($codeWork);

        if ($workCodeDetail == null) {
            return $codeWork . '001';
        } else {
            $arr = explode($codeWork, $workCodeDetail);
            $value = strval(intval($arr[1]) + 1);
            $zero_str = "";
            if (strlen($value) < 7) {
                for ($i = 0; $i < (3 - strlen($value)); $i++) {
                    $zero_str .= "0";
                }
            }
            return $codeWork . $zero_str . $value;
        }
    }

    public function showPopStaffSupportAction($data)
    {
        try {
            //Get session product
            $arrCheckTemp = [];
            if (session()->get('staff_support')) {
                $arrCheckTemp = session()->get('staff_support');
            }

            session()->forget('staff_support_temp');
            session()->put('staff_support_temp', $arrCheckTemp);
            session()->forget('remove_staff_support');

            //Lấy ds nhân viên
            $list = $this->listStaffSupport($data);

            $list['FILTER'] = $this->staffSupportFilters();

            $html = \View::make('manager-project::work.popup.pop-staff-support', $list)->render();

            return [
                'html' => $html,
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Danh sách nhân viên hỗ trợ
     *
     * @param array $filter
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function listStaffSupport($filter = [])
    {
        $mStaff = app()->get(StaffTableNew::class);
        $mProjectStaff = app()->get(ManageProjectStaffTable::class);

        $arrCheckTemp = [];
        $arrCheck = [];

        //Lấy session tạm
        if (session()->get('staff_support_temp')) {
            $arrCheckTemp = session()->get('staff_support_temp');
        }

        //Lấy session chính
        if (session()->get('staff_support')) {
            $arrCheck = session()->get('staff_support');
        }

        $filter['arr_staff_project'] = [-1];
//        Lấy danh sách nhân viên theo dự án
        if (isset($filter['manage_project_id'])) {
            $tmp = $mProjectStaff->getListStaffByProject($filter['manage_project_id']);
            if(count($tmp) != 0){
                $filter['arr_staff_project'] = collect($tmp)->pluck('staff_id')->toArray();
            }
        }

        //Lấy ds ca làm việc
        $list = $mStaff->getList($filter);

        return [
            'list' => $list,
            'arrCheckTemp' => array_replace_recursive($arrCheckTemp, $arrCheck)
        ];
    }

    /**
     * Filter popup chọn nhân viên hỗ trợ
     *
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function staffSupportFilters()
    {
        $mBranch = app()->get(BranchTable::class);
        $mDepartment = app()->get(DepartmentTable::class);
        $mStaff = app()->get(StaffTableNew::class);

        //Lấy option chi nhánh
        $getOptionBranch = $mBranch->getOption()->toArray();

        $groupBranch = (["" => __("Chọn chi nhánh")]) + array_combine(array_column($getOptionBranch, 'branch_id'), array_column($getOptionBranch, 'branch_name'));

        //Lấy option phòng ban
        $getOptionDepartment = $mDepartment->getOption()->toArray();

        $groupDepartment = (["" => __("Chọn phòng ban")]) + array_combine(array_column($getOptionDepartment, 'department_id'), array_column($getOptionDepartment, 'department_name'));

        return [
            'staffs$branch_id' => [
                'data' => $groupBranch
            ],
            'staffs$department_id' => [
                'data' => $groupDepartment
            ],
        ];
    }

    /**
     * Submit chọn nhân viên hỗ trợ
     *
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function submitChooseStaffSupport()
    {
        $mStaff = app()->get(\Modules\ManagerWork\Models\StaffTableNew::class);

        $arrCheckTemp = [];
        $arrCheck = [];
        $dataStaff = [];

        //Get session temp
        if (session()->get('staff_support_temp')) {
            $arrCheckTemp = session()->get('staff_support_temp');
        }

        //Get session chính
        if (session()->get('staff_support')) {
            $arrCheck = session()->get('staff_support');
        }

        //Merge 2 mãng lại
        $arrTotal = array_replace_recursive($arrCheckTemp, $arrCheck);

        //Get session remove product
        $arrRemove = [];
        if (session()->get('remove_staff_support')) {
            $arrRemove = session()->get('remove_staff_support');
        }
        if (count($arrRemove) > 0) {
            foreach ($arrRemove as $v) {
                unset($arrTotal[$v]);
            }
        }
        //Forget session temp
        session()->forget('staff_support_temp');
        //Forget session chính
        session()->forget('staff_support');
        //Put session chính
        session()->put('staff_support', $arrTotal);

        if (count($arrTotal) == 0) {
            return [
                'error' => true,
                'message' => __('Vui lòng chọn nhân viên hỗ trợ')
            ];
        }

        //Lấy thông tin nhân viên
        foreach ($arrTotal as $v) {
            //Lấy thông tin nhân viên
            $info = $mStaff->getInfo($v['staff_id']);

            if ($info != null) {
                $dataStaff [] = [
                    'staff_id' => $info['staff_id'],
                    'full_name' => $info['full_name']
                ];
            }
        }

        //Return kết quả
        return [
            'error' => false,
            'message' => __('Chọn nhân viên hỗ trợ thành công'),
            'data' => $dataStaff
        ];
    }

}

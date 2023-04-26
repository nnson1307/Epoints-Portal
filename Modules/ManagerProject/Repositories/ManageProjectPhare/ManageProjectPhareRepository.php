<?php


namespace Modules\ManagerProject\Repositories\ManageProjectPhare;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\ManagerProject\Models\ManagePhaseTable;
use Modules\ManagerProject\Models\ManageProjectPhareTable;
use Modules\ManagerProject\Models\ManageProjectTable;
use Modules\ManagerProject\Repositories\ManageProjectStaff\ManageProjectStaffRepositoryInterface;

class ManageProjectPhareRepository implements ManageProjectPhareRepositoryInterface
{
    protected $mManageProjectPhare;

    public function __construct(ManageProjectPhareTable $manageProjectPhare)
    {
        $this->mManageProjectPhare = $manageProjectPhare;
    }

    /**
     * Lấy danh sách giai đoạn theo dự án
     * @param $projectId
     * @return mixed
     */
    public function getAllPhareByProject($projectId){
        return $this->mManageProjectPhare->getAllPhareByProject($projectId);
    }

    /**
     * Tạo giai đoạn
     * @param $data
     * @return mixed|void
     */
    public function storePhase($data)
    {
        try {
            $mManageProjectPhase = app()->get(ManageProjectPhareTable::class);
            $mManagePhase = app()->get(ManagePhaseTable::class);
            $dataSample = [];
            $code = $this->createRandomCode();
            if (isset($data['phase']) && count($data['phase']) != 0){
                foreach ($data['phase'] as $key => $item){
                    if (isset($data['phase'][$key]['date_start'])){
                        $data['phase'][$key]['date_start'] = Carbon::createFromFormat('d/m/Y',$data['phase'][$key]['date_start'])->format('Y-m-d');
                    }
                    if (isset($data['phase'][$key]['date_end'])){
                        $data['phase'][$key]['date_end'] = Carbon::createFromFormat('d/m/Y',$data['phase'][$key]['date_end'])->format('Y-m-d');
                    }

                    if ($data['phase'][$key]['date_start'] > $data['phase'][$key]['date_end']) {
                        return [
                            'error' => true,
                            'message' => __('Ngày kết thúc phải lớn hơn ngày bắt đầu')
                        ];
                    }

                    if (isset($data['saveTemplate']) && $data['saveTemplate'] == 'save'){
                        $dataSample[$key] = $item;
                        $dataSample[$key]['manage_phase_group_code'] = $code;
                        $dataSample[$key]['date_start'] = $data['phase'][$key]['date_start'];
                        $dataSample[$key]['date_end'] = $data['phase'][$key]['date_end'];
                        $dataSample[$key]['is_deleted'] = 0;
                        $dataSample[$key]['status'] = 'new';
                        $dataSample[$key]['created_at'] = Carbon::now();
                        $dataSample[$key]['created_by'] = Auth::id();
                        $dataSample[$key]['updated_at'] = Carbon::now();
                        $dataSample[$key]['updated_by'] = Auth::id();
                        unset($dataSample[$key]['manage_project_id']);
                        unset($dataSample[$key]['manage_project_id']);
                    }
                }
                $mManageProjectPhase->addPhase($data['phase']);

                if (count($dataSample) != 0){
                    $mManagePhase->insertSample($dataSample);
                }

            } else {
                return [
                    'error' => true,
                    'message' => __('Vui lòng thêm ít nhất 1 giai đoạn')
                ];
            }

            return [
                'error' => false,
                'message' => __('Tạo giai đoạn thành công'),
                'link' => route('manager-project.project.project-info-phase',['id' => $data['main_manage_project_id']])
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Tạo giai đoạn thất bại')
            ];
        }
    }

    function createRandomCode() {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;

    }

    /**
     * Xóa trong phase
     * @param $data
     * @return mixed|void
     */
    public function removeAction($data)
    {
        try {

            $mManageProjectPhase = app()->get(ManageProjectPhareTable::class);

            $mManageProjectPhase->deletePhase($data['manage_project_phase_id']);

            return [
                'error' => false,
                'message' => __('Xóa giai đoạn thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Xóa giai đoạn thất bại')
            ];
        }
    }

    /**
     * Hiển thị popup cập nhật Phase
     * @param $data
     * @return mixed|void
     */
    public function showPopup($data)
    {
        try {

            $mManageProjectPhase = app()->get(ManageProjectPhareTable::class);

            $detail = $mManageProjectPhase->getDetail($data['manage_project_phase_id']);

            $rManageProjectStaff = app()->get(ManageProjectStaffRepositoryInterface::class);

            $listStaff = $rManageProjectStaff->getListStaff($detail['manage_project_id']);

            $view = view('manager-project::phase.popup.popup-phase',[
                'listStaff' => $listStaff,
                'detail' => $detail
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    /**
     * Cập nhật phase
     * @param $data
     * @return mixed|void
     */
    public function updateAction($data)
    {
        try {

            $mManageProjectPhase = app()->get(ManageProjectPhareTable::class);
            $tmpData = [
                'name' => strip_tags($data['name']),
                'pic' => $data['pic'],
                'status' => $data['status'],
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            if (isset($data['date_start'])){
                $tmpData['date_start'] = Carbon::createFromFormat('d/m/Y',$data['date_start'])->format('Y-m-d');
            }

            if (isset($data['date_end'])){
                $tmpData['date_end'] = Carbon::createFromFormat('d/m/Y',$data['date_end'])->format('Y-m-d');
            }

            $mManageProjectPhase->updatePhase($tmpData,$data['manage_project_phase_id']);

            return [
                'error' => false,
                'message' => __('Cập nhật giai đoạn thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Cập nhật giai đoạn thất bại')
            ];
        }
    }

    /**
     * Tự động tạo phase cho tất cả dự án
     */
    public function autoCreatePhase()
    {
        DB::beginTransaction();
        try {
            $mManageProjectPhase = app()->get(ManageProjectPhareTable::class);
            $mManageProject = app()->get(ManageProjectTable::class);

//            Lấy danh sách ProjectId có tạo giai đoạn mặc định
            $listProjectIdDefault = $mManageProjectPhase->getListDefault();

            $arrProjectId = [];
            if (count($listProjectIdDefault) != 0){
                $arrProjectId = collect($listProjectIdDefault)->pluck('manage_project_id')->toArray();
            }

            $listProject = $mManageProject->getListNotDefault($arrProjectId);

            $dataPhase = [];
            foreach ($listProject as $item){
                $dataPhase[] = [
                    'manage_project_id' => $item['manage_project_id'],
                    'name' =>  'Default Job',
                    'date_start' => isset($item['date_start']) ? $item['date_start'] : Carbon::now()->format('Y-m-d'),
                    'date_end' => isset($item['date_end']) ? $item['date_end'] : Carbon::now()->addMonths(1)->format('Y-m-d'),
                    'pic' => $item['manager_id'],
                    'is_deleted' => 0,
                    'status' => 'new',
                    'created_at' => \Illuminate\Support\Carbon::now(),
                    'created_by' => 1,
                    'updated_at' => Carbon::now(),
                    'updated_by' => 1,
                    'is_default' => 1
                ];
            }

            if (count($dataPhase) != 0){
                $mManageProjectPhase->addPhase($dataPhase);
            }

            DB::commit();
        }catch (Exception $e){
            dd($e->getMessage());
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}
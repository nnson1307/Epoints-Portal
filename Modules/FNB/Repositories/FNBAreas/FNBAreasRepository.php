<?php


namespace Modules\FNB\Repositories\FNBAreas;


use App\Exports\ExportFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\FNBAreasTable;
use Modules\FNB\Repositories\Branch\BranchRepositoryInterface;
use Modules\FNB\Repositories\ConfigColumn\ConfigColumnRepositoryInterface;

class FNBAreasRepository implements FNBAreasRepositoryInterface
{
    private $areas;

    public function __construct(FNBAreasTable $areas)
    {
        $this->areas = $areas;
    }

    public function getList(array $filter = [])
    {
        return $this->areas->getList($filter);
    }

    /**
     * Lấy danh sách có phân trang custom
     * @param array $filter
     * @return mixed|void
     */
    public function getListPagination(array $filter = [])
    {
        return $this->areas->getListPagination($filter);
    }

    public function getAll()
    {
        return $this->areas->getAll();
    }
    /**
     * Hiển thị popup
     * @param $data
     */
    public function showPopup($data)
    {
        try{
            $rBranch = app()->get(BranchRepositoryInterface::class);
            $getListBranch = $rBranch->getAllBranch();
            if(isset($data['item'])){
                $view = view('fnb::areas.popup.edit',[
                    'getListBranch' => $getListBranch,
                    'item' => $data['item']
                ])->render();
                $job = 'edit';
            }else {
                $view = view('fnb::areas.popup.create',[
                    'getListBranch' => $getListBranch
                ])->render();
                $job = 'create';
            }
            return [
                'error' => false,
                'view' => $view,
                'job' => $job
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lỗi')
            ];
        }
    }
    public function getAllAreas(){
        try{
            return $this->areas->getAllAreas();
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lấy danh sách thất bại!')
            ];
        }
    }
    public  function createAreas($input){
        try{
            $dataAreas = [
                'area_code' => $input['area_code'],
                'branch_id' => $input['branch_id'],
                'note' =>  isset($input['note']) && $input['note'] != null ? $input['note'] : '',
                'name' => $input['name'],
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'created_by' => Auth()->id()
            ];
            $areas = app()->get(FNBAreasTable::class);
            $mAreas = $areas->createAreas($dataAreas);
            if(isset($mAreas) && $mAreas != ''){
                return [
                    'error' => false,
                    'message' => 'Thêm khu vực thành công!'
                ];
            }else{
                return [
                    'error' => true,
                    'message' => 'Thêm khu vực thất bại!'
                ];
            }

        }catch (Exception $e){
            return [
                'error' => true,
                'message' => 'Thêm khu vực thất bại!'
            ];
        }
    }
    public  function editAreas($input){
        try{
            $areasId = $input['area_id'];
            unset($input['area_id']);
            $areas = app()->get(FNBAreasTable::class);
            if(!isset($input['is_active'])){
                $input['is_active'] = 0;
            }else{
                unset( $input['is_active']);
            }
            $input['updated_at'] = Carbon::now();
            $input['updated_by'] = Auth()->id();
            $mAreas = $areas->editAreas($areasId,$input);
               return [
                   'error' => false,
                   'message' => __('Chỉnh sửa khu vực thành công!')
               ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Chỉnh sửa khu vực thất bại!')
            ];
        }
    }
    public function deleteAreas($input){
        try{
            $areaId = $input;
            $areas = app()->get(FNBAreasTable::class);
            $mDelAreas = $areas->deleteAreas($areaId);
            return [
                'error' => false,
                'message' => __('Xóa khu vực thành công!')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Xóa khu vực thất bại!')
            ];
        }
    }
    public function export($data){
        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);
        $mListAreas = app()->get(FNBAreasTable::class);

//        Lấy danh sách cấu hình hiển thị hoặc tạo mới nếu chưa có
        $listConfigStaff = $rConfigColumn->getAllConfigStaff(Auth::id(),$data['route']);

        if (count($listConfigStaff) != 0){
            $listConfigStaff = collect($listConfigStaff)->groupBy('type');
        }

        $list = $mListAreas->getListNoPage($data);

        $heading = [];
        if (isset($listConfigStaff['show']) > 0){
            foreach ($listConfigStaff['show'] as $item){
                $heading[] = $item[getValueByLang('column_nameConfig_')];
            }
        }
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $listData = [];
        if (isset($listConfigStaff['show']) > 0) {
            foreach ($list as $key => $item) {
                foreach ($listConfigStaff['show'] as $itemValue){
                    if($itemValue['column_name'] == 'is_active'){
                        $listData[$key][$itemValue['column_name']] = $item[$itemValue['column_name']] == 1 ? __('Đang hoạt động') : __('Ngừng hoạt động');
                    }else if(in_array($itemValue['column_name'],['created_at','updated_at'])){
                        $listData[$key][$itemValue['column_name']] = isset($item[$itemValue['column_name']]) ? \Carbon\Carbon::parse($item[$itemValue['column_name']])->format('H:i:s d/m/Y') : '';
                    }else {
                        $listData[$key][$itemValue['column_name']] = $item[$itemValue['column_name']];
                    }
                }
            }
        }


        return Excel::download(new ExportFile($heading, $listData), 'areas.xlsx');

    }


}

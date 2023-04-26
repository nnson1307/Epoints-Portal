<?php


namespace Modules\FNB\Repositories\FNBTable;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\FNBTableTable;
use Modules\FNB\Repositories\ConfigColumn\ConfigColumnRepositoryInterface;
use Modules\FNB\Repositories\FNBAreas\FNBAreasRepositoryInterface;
use Modules\FNB\Repositories\FNBQrCode\FNBQrCodeRepositoryInterface;

class FNBTableRepository implements FNBTableRepositoryInterface
{
    private $table;

    public function __contruct(FNBTableTable $table){
        $this->table = $table;
    }

    public function getList(array $filter = [])
    {
        $table = app()->get(FNBTableTable::class);
        return $table->getList($filter);
    }

    /**
     * Lấy danh sách phân trang custom
     * @param array $filter
     * @return mixed|void
     */
    public function getListPagination(array $filter = [])
    {
        $mTable = app()->get(FNBTableTable::class);
        $list = $mTable->getListPagination($filter);
        return $list;
    }

    /**
     * Lấy danh sách bàn theo template
     * @param $idCodeTemplate
     * @return mixed|void
     */
    public function getListTableByTemplate($idCodeTemplate,$apply_for)
    {
        $mTable = app()->get(FNBTableTable::class);
        $rQrCode = app()->get(FNBQrCodeRepositoryInterface::class);

        if ($apply_for == 'custom'){
            $list = $rQrCode->getListTableByTemplate($idCodeTemplate);
        } else {
            $list = $mTable->getAll();
        }

        return $list;
    }
    /**
     * Hiển thị popup
     * @param $data
     */
    public function showPopup($data)
    {
        try{
            $rAreas = app()->get(FNBAreasRepositoryInterface::class);
            $getListAreas = $rAreas->getAllAreas();
            if(isset($data['item'])){
                $view = view('fnb::table.popup.edit',[
                    'listAreas' => $getListAreas,
                    'item' => $data['item']
                ])->render();
                $job = 'edit';
            }else {
                $view = view('fnb::table.popup.create',[
                    'listAreas' => $getListAreas
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
    /**
     * @inheritDoc
     */
    public function createTable($dataTable)
    {
        try{
            $dataTable['created_at'] = Carbon::now();
            $dataTable['created_by'] = Auth()->id();
            $mTable= app()->get(FNBTableTable::class);
            $table = $mTable->createTable($dataTable);
            if(isset($table) && $table != ''){
                return [
                    'error' => false,
                    'message' => 'Thêm bàn thành công!'
                ];
            }else{
                return [
                    'error' => true,
                    'message' => 'Thêm bàn thất bại!'
                ];
            }
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => 'Thêm bàn thất bại!'
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function editTable($dataEditTable)
    {
        try{

            $tableId = $dataEditTable['table_id'];
            unset($dataEditTable['table_id']);
            $dataEditTable['updated_at'] = Carbon::now();
            $dataEditTable['updated_by'] = Auth()->id();
            if(!isset($dataEditTable['is_active'])){
                $dataEditTable['is_active'] = 0;
            }else{
                unset($dataEditTable['is_active']);
            }

            $mTable = app()->get(FNBTableTable::class);
            $table = $mTable->editTable($tableId,$dataEditTable);
            if(isset($table) && $table != ''){
                return [
                    'error' => false,
                    'message' => __('Chỉnh sửa khu vực thành công!')
                ];
            }else{
                return [
                    'error' => true,
                    'message' => __('Chỉnh sửa khu vực thất bại!')
                ];
            }

        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Chỉnh sửa khu vực thất bại!')
            ];
        }
    }
    public function deleteTable($input){
        try{
            $tableId = $input;
            $mTable = app()->get(FNBTableTable::class);
            $mDelTable = $mTable->deleteTable($tableId);
            if(isset($mDelTable) && $mDelTable != ''){
                return [
                    'error' => false,
                    'message' => __('Xóa bàn thành công!')
                ];
            }else{
                return [
                    'error' => true,
                    'message' => __('Xóa bàn thất bại!')
                ];
            }
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Xóa bàn thất bại!')
            ];
        }
    }
    public function export($data){
        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);
        $mTable = app()->get(FNBTableTable::class);

        $listConfigStaff = $rConfigColumn->getAllConfigStaff(Auth::id(),$data['route']);
        if (count($listConfigStaff) != 0){
            $listConfigStaff = collect($listConfigStaff)->groupBy('type');
        }
        /// danh sách bàn không phân trang
        $list = $mTable->getListNoPage($data);

        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        $heading = [];
        if (isset($listConfigStaff['show']) > 0){
            foreach ($listConfigStaff['show'] as $item){
                $heading[] = $item[getValueByLang('column_nameConfig_')];
            }
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
        return Excel::download(new ExportFile($heading, $listData), 'table.xlsx');
    }
}
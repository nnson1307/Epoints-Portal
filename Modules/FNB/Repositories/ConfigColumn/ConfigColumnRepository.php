<?php


namespace Modules\FNB\Repositories\ConfigColumn;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\ConfigColumnStaffTable;
use Modules\FNB\Models\ConfigColumnTable;

class ConfigColumnRepository implements ConfigColumnRepositoryInterface
{
    private $configColumn;

    public function __construct(ConfigColumnTable $configColumn)
    {
        $this->configColumn = $configColumn;
    }

    /**
     * Hiển thị popup cấu hình
     * @param $data
     * @return mixed|void
     */
    public function showColumn($data)
    {
        try {

//            Lấy cấu hình theo route

            $listConfig = $this->configColumn->getAllByRoute($data['route']);

            if (count($listConfig) != 0){
                $listConfig = collect($listConfig)->groupBy('type');
            }

            $listConfigStaff = $this->getConfigByStaffRoute(Auth::id(),$data['route']);

            $listConfig = collect($listConfig)->toArray();
            foreach ($listConfig['show'] as $key => $value){
                if($value['column_name'] == 'qr_image'){
                    unset($listConfig['show'][$key]);
                }
            }

            $listConfigStaff = collect($listConfigStaff)->toArray();
            foreach ($listConfigStaff['show'] as $key => $value){
                if($value == 25){
                    unset($listConfigStaff['show'][$key]);
                }
            }


            $view = view('fnb::config-column.popup.config_popup',
                [
                    'listConfig' => $listConfig,
                    'listConfigStaff' => $listConfigStaff,
                ]
                )->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup lỗi')
            ];
        }
    }

    /**
     * Lưu cấu hình
     * @param $data
     * @return mixed|void
     */
    public function saveConfig($data)
    {
        try {

            $mConfigColumnStaff = app()->get(ConfigColumnStaffTable::class);

            if (!isset($data['filter']) || count($data['filter']) == 0){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn ít nhất 1 Cấu hình tìm kiếm')
                ];
            }

            if (!isset($data['show']) || count($data['show']) == 0){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn ít nhất 1 Cấu hình danh sách')
                ];
            }

//            Xóa cấu hình trước đó
            $mConfigColumnStaff->removeConfig(Auth::id(),$data['route']);
            $dataConfig = [];
            $data['filter'] = array_merge($data['filter'],$data['show']);
            foreach ($data['filter'] as $item){
                $dataConfig[] = [
                    'staff_id' => Auth::id(),
                    'route' => $data['route'],
                    'config_column_id' => $item,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];
            }

            if (count($dataConfig) != 0){
                $mConfigColumnStaff->addConfig($dataConfig);
            }

            return [
                'error' => false,
                'message' => __('Lưu cấu hình thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lưu cấu hình thất bại')
            ];
        }
    }

    /**
     * Lấy cấu hình hiển thị cột và filter theo route và nhân viên
     * @param $staffId
     * @param $route
     * @return mixed|void
     */
    public function getConfigByStaffRoute($staffId, $route)
    {
        $mConfigColumnStaff = app()->get(ConfigColumnStaffTable::class);

        $listConfigStaff = $mConfigColumnStaff->getAllByRoute($staffId,$route);

//        Nếu giá trị thì gom nhóm lại
        if(count($listConfigStaff) != 0){
            $listConfigStaff = collect($listConfigStaff)->groupBy('type');
            if (isset($listConfigStaff['filter'])){
                $listConfigStaff['filter'] = collect($listConfigStaff['filter'])->pluck('config_column_id')->toArray();
            }

            if (isset($listConfigStaff['show'])){
                $listConfigStaff['show'] = collect($listConfigStaff['show'])->pluck('config_column_id')->toArray();
            }
        } else {
//            Nếu k có thì tạo theo giá trị mặc định mỗi loại rồi gom nhóm lại
            $listConfig = $this->configColumn->getAllByRouteDefault($route);
            $dataDefault = [
                'staff_id' => $staffId,
                'route' => $route,
            ];

            if (count($listConfig) != 0){
                $listConfig = collect($listConfig)->groupBy('type');

                if (isset($listConfig['filter'])){
                    $dataDefault['filter'] = collect($listConfig['filter'])->pluck('config_column_id')->toArray();
                }

                if (isset($listConfig['show'])){
                    $dataDefault['show'] = collect($listConfig['show'])->pluck('config_column_id')->toArray();
                }

                $this->saveConfig($dataDefault);
            }
        }

        return $listConfigStaff;
    }

    /**
     * Lấy danh sách cấu hình theo từng nhân viên
     * @param $staffId
     * @param $route
     * @return mixed|void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getAllConfigStaff($staffId, $route)
    {

        $mConfigColumnStaff = app()->get(ConfigColumnStaffTable::class);

        $listConfigStaff = $mConfigColumnStaff->getAllByRoute($staffId,$route);

//        Nếu chưa có thông tin thì tạo dữ liệu
        if (count($listConfigStaff) == 0){
            $this->getConfigByStaffRoute($staffId, $route);
            $listConfigStaff = $mConfigColumnStaff->getAllByRoute($staffId,$route);
        }

        ///ẩn qr_image
        foreach ($listConfigStaff as $key => $item){
            if($item['column_name'] == 'qr_image'){
                unset($listConfigStaff[$key]);
            }
        }
        return $listConfigStaff;
    }
}
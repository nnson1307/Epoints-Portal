<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 9/29/2021
 * Time: 1:55 PM
 * @author nhandt
 */


namespace Modules\Dashbroad\Repositories\DashBoardConfig;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Dashbroad\Models\DashboardComponentTable;
use Modules\Dashbroad\Models\DashboardComponentWidgetTable;
use Modules\Dashbroad\Models\DashboardTable;
use Modules\Dashbroad\Models\DashboardWidgetTable;

class DashBoardConfigRepo implements DashBoardConfigRepoInterface
{
    protected $dashboard;
    protected $dashboardComponent;
    protected $dashboardComponentWidget;
    protected $dashboardWidget;
    public function __construct(DashboardTable $dashboard,
                                DashboardComponentTable $dashboardComponent,
                                DashboardComponentWidgetTable $dashboardComponentWidget,
                                DashboardWidgetTable $dashboardWidget)
    {
        $this->dashboard = $dashboard;
        $this->dashboardComponent = $dashboardComponent;
        $this->dashboardComponentWidget = $dashboardComponentWidget;
        $this->dashboardWidget = $dashboardWidget;
    }
    public function getList(array &$filters = [])
    {
        $list = $this->dashboard->getList($filters);
        return [
            "list" => $list,
        ];
    }
    /**
     * popup tạo thông tin cơ bản dashboard
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function popCreateDashboardConfig($input)
    {
        $optionDashboard = $this->dashboard->getOptionDashboard();
        $html = \View::make('dashbroad::dashboard-config.pop.create-dashboard', [
            'optionDashboard' => $optionDashboard
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Lưu thông tin cơ bản dashboard
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePopCreateDashboardConfig($data)
    {
        try{
            $idDashboardCopy = $data['dashboard_id'];
            $isActived = intval($data['is_actived']);
            if($isActived == 1){
                // update all dashboard with is_actived = 0;
                $dataActive = [
                    'is_actived' => 0
                ];
                $this->dashboard->updateActive($dataActive);
            }
            $dataNewDashboard = [
                'name_vi' => $data['name_vi'],
                'name_en' => $data['name_en'],
                'is_actived' => $isActived,
                'created_by' => auth()->id(),
                'created_at' => Carbon::now()
            ];
            $idDashboard = $this->dashboard->createData($dataNewDashboard);
            $lstComponentDefault = $this->dashboardComponent->getComponent($idDashboardCopy);
            foreach ($lstComponentDefault as $key => $value) {
//                $dataComponent = [
//                  'dashboard_id' => $idDashboard,
//                  'component_type' => $value['component_type'],
//                  'component_name_vi' => $value['component_name_vi'],
//                  'component_name_en' => $value['component_name_en'],
//                  'component_position' => $value['component_position'],
//                  'is_default' => 0,
//                  'created_by' => auth()->id(),
//                  'created_at' => Carbon::now()
//                ];
//                $idComponent = $this->dashboardComponent->createData($dataComponent);
                $lstComponentWidget = $this->dashboardComponentWidget->getWidgetOfComponent($value['dashboard_component_id']);
                $lstComponentDefault[$key]['widget'] = $lstComponentWidget;
                $dataComponentWidget = [];
//                foreach ($lstComponentWidget as $k => $v) {
//                    $dataComponentWidget[] = [
//                        'dashboard_component_id' => $idComponent,
//                        'dashboard_widget_id' => $v['dashboard_widget_id'],
//                        'widget_position' => $v['widget_position'],
//                        'created_by' => auth()->id(),
//                        'created_at' => Carbon::now()
//                    ];
//                }
//                $this->dashboardComponentWidget->insertData($dataComponentWidget);
            }
            $lstWidget = $this->dashboardWidget->getListWidget();
            $html = \View::make('dashbroad::dashboard-config.create_body', [
                'dashboard_id' => $idDashboard,
                'lstWidget' => $lstWidget,
                'lstComponentDefault' => $lstComponentDefault,
            ])->render();
            return response()->json([
                'error' => false,
                'html' => $html,
                'message' => __('')
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * ds widget mặc định
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListWidget($data)
    {
        try{
            $lstWidget = $this->dashboardWidget->getListWidget($data);
            return response()->json([
                'error' => false,
                'lstWidget' => $lstWidget,
                'message' => __('')
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lưu thông tin vị trí bố cục
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDashboardAction($data)
    {
        try{
            $lstComponentDefault = $data['arrComponent'];
            $idDashboard = $data['dashboard_id'];
            foreach ($lstComponentDefault as $key => $value) {
                $dataComponent = [
                    'dashboard_id' => $idDashboard,
                    'component_type' => $value['component_type'],
                    'component_position' => $value['component_position'],
                    'is_default' => 0,
                    'created_by' => auth()->id(),
                    'created_at' => Carbon::now()
                ];
                $idComponent = $this->dashboardComponent->createData($dataComponent);
                $dataComponentWidget = [];
                $lstComponentWidget = $value['arrWidget'];
                foreach ($lstComponentWidget as $k => $v) {
                    $dataComponentWidget[] = [
                        'dashboard_component_id' => $idComponent,
                        'dashboard_widget_id' => $v['dashboard_widget_id'],
                        'widget_display_name' => $v['widget_display_name'],
                        'widget_position' => $v['widget_position'],
                        'created_by' => auth()->id(),
                        'created_at' => Carbon::now()
                    ];
                }
                $this->dashboardComponentWidget->insertData($dataComponentWidget);
            }
            return response()->json([
                'error' => false,
                'message' => __('Tạo thành công')
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * remove dashboard
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeDashboardAction($data)
    {
        try{
            $idDashboard = $data['dashboard_id'];
            $data = [
                'is_deleted' => 1
            ];
            $this->dashboard->updateData($data, $idDashboard);
            return response()->json([
                'error' => false,
                'message' => __('Xoá thành công')
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * detail dashboard
     *
     * @param $data
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getDetail($data)
    {
        $lstComponentDefault = $this->dashboardComponent->getComponent($data['id']);
        foreach ($lstComponentDefault as $key => $value) {
            $lstComponentWidget = $this->dashboardComponentWidget->getWidgetOfComponent($value['dashboard_component_id']);
            $lstComponentDefault[$key]['widget'] = $lstComponentWidget;
        }
        return view('dashbroad::dashboard-config.detail', [
            'lstComponentDefault' => $lstComponentDefault,
        ]);
    }

    /**
     * update is_actived dashboard
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatusAction($data)
    {
        try{
            $idDashboard = $data['dashboard_id'];
            $isActived = intval($data['is_actived']);
            if($isActived == 1){
                // update all dashboard with is_actived = 0;
                $dataActive = [
                    'is_actived' => 0
                ];
                $this->dashboard->updateActive($dataActive);
            }
            $data = [
                'is_actived' => $isActived
            ];
            $this->dashboard->updateData($data, $idDashboard);
            return response()->json([
                'error' => false,
                'message' => __('Cập nhật thành công')
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * popup thay đổi thông tin cơ bản của dashboard
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function popEditDashboardConfig($input)
    {
        $id = $input['dashboard_id'];
        $dataDashboard = $this->dashboard->getItem($id);
        $optionDashboard = $this->dashboard->getOptionDashboard();
        $html = \View::make('dashbroad::dashboard-config.pop.edit-dashboard', [
            'optionDashboard' => $optionDashboard,
            'item' => $dataDashboard
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * lưu thông tin cơ bản của dashboard + return html bố cục cấu hình
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePopEditDashboardConfig($data)
    {
        try{
            $id = $data['dashboard_id'];
            $isActived = intval($data['is_actived']);
            if($isActived == 1){
                // update all dashboard with is_actived = 0;
                $dataActive = [
                    'is_actived' => 0
                ];
                $this->dashboard->updateActive($dataActive);
            }
            $dataDashboard = [
                'name_vi' => $data['name_vi'],
                'name_en' => $data['name_en'],
                'is_actived' => $isActived,
                'created_by' => auth()->id(),
                'created_at' => Carbon::now()
            ];
            $this->dashboard->updateData($dataDashboard, $id);
            $lstComponentDefault = $this->dashboardComponent->getComponent($id);
            foreach ($lstComponentDefault as $key => $value) {
                $lstComponentWidget = $this->dashboardComponentWidget->getWidgetOfComponent($value['dashboard_component_id']);
                $lstComponentDefault[$key]['widget'] = $lstComponentWidget;
            }
            $lstWidget = $this->dashboardWidget->getListWidget();
            $html = \View::make('dashbroad::dashboard-config.edit_body', [
                'dashboard_id' => $id,
                'lstWidget' => $lstWidget,
                'lstComponentDefault' => $lstComponentDefault,
            ])->render();
            return response()->json([
                'error' => false,
                'html' => $html,
                'message' => __('')
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lưu cấu hình dashboard
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editDashboardAction($data)
    {
        try{
            $lstComponentDefault = $data['arrComponent'];
            // get list component of dashboard => remove
            $lstComponentRemove = $this->dashboardComponent->getComponent($data['dashboard_id'])->toArray();
            foreach ($lstComponentRemove as $item) {
                $dashboardComponentId = $item['dashboard_component_id'];
                $this->dashboardComponentWidget->removeData($dashboardComponentId);
            }
            $this->dashboardComponent->removeData($data['dashboard_id']);
            foreach ($lstComponentDefault as $key => $value) {
                $dataComponent = [
                    'dashboard_id' => $data['dashboard_id'],
                    'component_type' => $value['component_type'],
                    'component_position' => $value['component_position'],
                    'is_default' => 0,
                    'created_by' => auth()->id(),
                    'created_at' => Carbon::now()
                ];
                $idComponent = $this->dashboardComponent->createData($dataComponent);
                $dataComponentWidget = [];
                $lstComponentWidget = $value['arrWidget'];
                foreach ($lstComponentWidget as $k => $v) {
                    $dataComponentWidget[] = [
                        'dashboard_component_id' => $idComponent,
                        'dashboard_widget_id' => $v['dashboard_widget_id'],
                        'widget_display_name' => $v['widget_display_name'],
                        'widget_position' => $v['widget_position'],
                        'created_by' => auth()->id(),
                        'created_at' => Carbon::now()
                    ];
                }
                $this->dashboardComponentWidget->insertData($dataComponentWidget);
            }
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
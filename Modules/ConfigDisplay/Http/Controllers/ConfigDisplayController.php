<?php

namespace Modules\ConfigDisplay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ConfigDisplay\Http\Requests\StoreRequest;
use Modules\ConfigDisplay\Repositories\ConfigDisplayRepoInterface;
use Modules\ConfigDisplay\Repositories\ConfigDisplayDetailRepoInterface;

class ConfigDisplayController extends Controller
{

    protected $configDisplayRepo;
    protected $configDisplayDetailRepo;
    public function __construct(
        ConfigDisplayRepoInterface $configDisplayRepo,
        ConfigDisplayDetailRepoInterface $configDisplayDetailRepo
    ) {
        $this->configDisplayRepo = $configDisplayRepo;
        $this->configDisplayDetailRepo = $configDisplayDetailRepo;
    }
    /**
     * Danh sách cấu hình hiển thị 
     * @return Views
     */
    public function index()
    {
        $typeTemplate = $this->configDisplayRepo->getTypeTemplate();
        return view('config-display::index', ['typeTemplate' => $typeTemplate]);
    }

    /**
     * Hiển thị danh sách cấu hình ajax
     * @param Request $request
     * @return Response
     */

    public function loadAll(Request $request)
    {
        $params = $request->all();
        $result = $this->configDisplayRepo->getAll($params);
        return response()->json($result);
    }

    /**
     * chi tiết cấu hình hiển thị
     * @param $id
     * @return mixed
     */

    public function show($id)
    {
        return view("config-display::show", ['id' => $id, 'type' => 'show']);
    }

    /**
     * chi tiết cấu hình hiển thị
     * @param $id
     * @return mixed
     */

    public function edit($id)
    {

        return view("config-display::edit", ['id' => $id, 'type' => 'edit']);
    }

    /**
     * Hiển thị danh sách cấu hình chi tiết ajax
     * @param Request $request
     * @return Response
     */

    public function loadAllDetail(Request $request)
    {
        $params = $request->all();
        $result = $this->configDisplayDetailRepo->getAll($params);
        return response()->json($result);
    }

    /**
     * view thêm banner
     * @param $id
     * @return view
     */

    public function createDetail($id)
    {
        $configCategoryDetail = $this->configDisplayDetailRepo->getCategoryConfigDetail();
        $positionMaxConfigDisplayDetail = $this->configDisplayDetailRepo->getPositionMax($id);
        return view("config-display::config_detail.create", [
            'configCategoryDetail' => $configCategoryDetail,
            'positionMaxConfigDisplayDetail' => $positionMaxConfigDisplayDetail,
            'id' => $id
        ]);
    }

    /**
     * lấy tất cả khảo sát 
     * @param $request 
     * @return mixed
     */

    public function loadAllSurvey(Request $request)
    {
        $listSurvey = $this->configDisplayDetailRepo->getAllSurvey();
        return response()->json($listSurvey);
    }

    /**
     * Tạo mới banner
     * @param request $request
     * @return mixed
     */

    public function storeDetail(StoreRequest $request)
    {
        $params = $request->all();
        $result = $this->configDisplayDetailRepo->storeConfigDetail($params);
        return response()->json($result);
    }

    /**
     * Hiển thị chi tiết cấu hình hiển thị
     * @param $id_config_display [cấu hình hiển thị]
     * @param $id_config_display_detail [cấu hình hiển thị chi tiết]
     * @return mixed
     */

    public function showDetail($id_config_display, $id_config_display_detail)
    {
        $configCategoryDetail = $this->configDisplayDetailRepo->getCategoryConfigDetail();
        $positionMaxConfigDisplayDetail = $this->configDisplayDetailRepo->getPositionMax($id_config_display);
        $itemConfigDisplayDetail = $this->configDisplayDetailRepo->getItem($id_config_display_detail);
        return view("config-display::config_detail.show", [
            'configCategoryDetail' => $configCategoryDetail,
            'positionMaxConfigDisplayDetail' => $positionMaxConfigDisplayDetail,
            'id' => $id_config_display,
            'itemConfigDisplayDetail' => $itemConfigDisplayDetail
        ]);
    }

    /**
     * Hiển thị cập nhật cấu hình hiển thị chi tiết
     * @param $id_config_display [cấu hình hiển thị]
     * @param $id_config_display_detail [cấu hình hiển thị chi tiết]
     * @return mixed
     */

    public function editDetail($id_config_display, $id_config_display_detail)
    {
        $configCategoryDetail = $this->configDisplayDetailRepo->getCategoryConfigDetail();
        $positionMaxConfigDisplayDetail = $this->configDisplayDetailRepo->getPositionMax($id_config_display);
        $itemConfigDisplayDetail = $this->configDisplayDetailRepo->getItem($id_config_display_detail);
        return view("config-display::config_detail.edit", [
            'configCategoryDetail' => $configCategoryDetail,
            'positionMaxConfigDisplayDetail' => $positionMaxConfigDisplayDetail,
            'id' => $id_config_display,
            'itemConfigDisplayDetail' => $itemConfigDisplayDetail
        ]);
    }

    /**
     * Cập nhật cấu hình hiển thị chi tiết 
     * @param Request $request
     * @return mixed
     */

    public function updateDetail(StoreRequest $request)
    {
        $params = $request->all();
        $result = $this->configDisplayDetailRepo->updateConfigDetail($params);
        return response()->json($result);
    }

    /**
     * hiển thị modal xoá cấu hình hiển thị chi tiêts
     * @param Request $request
     * @return Response
     */

    public function showModalDestroy(Request $request)
    {
        $idConfigDisplay = $request->idConfigDisplay;
        $idConfigDisplayDetail = $request->idConfigDetail;
        $view = view("config-display::modal.remove_config_detail", [
            'idConfigDisplay' => $idConfigDisplay,
            'idConfigDisplayDetail' => $idConfigDisplayDetail,
        ])->render();
        return response()->json(['view' => $view]);
    }

    /**
     * Xoá banner 
     * @param Request $request
     * @return mixed
     */

    public function destroyDetail(Request $request)
    {
        $params = $request->all();
        $result = $this->configDisplayDetailRepo->destroy($params);
        return response()->json($result);
    }

    /**
     * Danh sách khuyến mãi 
     * @param Request $request
     * @return mixed 
     */

    public function loadAllPromotion(Request $request)
    {
        $listPromotion = $this->configDisplayDetailRepo->getAllPromotion();
        return response()->json($listPromotion);
    }

    /**
     * Danh sách sản phẩm
     * @param Request $request
     * @return mixed 
     */

    public function loadAllProduct(Request $request)
    {
        $listProduct = $this->configDisplayDetailRepo->getAllProduct();
        return response()->json($listProduct);
    }

    /**
     * Danh sách bài viết
     * @param Request $request
     * @return mixed 
     */

    public function loadAllPost(Request $request)
    {
        $listPost = $this->configDisplayDetailRepo->getAllPost();
        return response()->json($listPost);
    }
}

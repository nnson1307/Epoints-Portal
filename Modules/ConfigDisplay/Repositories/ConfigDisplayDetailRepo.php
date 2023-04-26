<?php


namespace Modules\ConfigDisplay\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\ConfigDisplay\Models\NewTable;
use Modules\ConfigDisplay\Models\SurveyTable;
use Modules\ConfigDisplay\Models\ProductTable;
use Modules\ConfigDisplay\Models\PromotionMasterTable;
use Modules\ConfigDisplay\Models\ConfigDisplayDetailTable;
use Modules\ConfigDisplay\Models\ConfigCategoryDetailTable;

class ConfigDisplayDetailRepo implements ConfigDisplayDetailRepoInterface
{

    protected $mConfigDisplayDetail;
    public function __construct(ConfigDisplayDetailTable $mConfigDisplayDetail)
    {
        $this->mConfigDisplayDetail = $mConfigDisplayDetail;
    }


    /**
     * Lấy tất cả cấu hình hiển thị 
     * @return mixed
     */

    public function getAll(array $params)
    {
        try {
            // Tiêu đề //
            $filters['keyword_config_display_detail$main_title'] = $params['mainTitle'] ?? "";
            // Ngày tạo //
            $filters['dateStart'] = $params['dateStart'] ?? "";
            // trạng thái //
            $filters['config_display_detail$status'] = $params['status'] ?? "";

            // số trang //  
            $filters['page'] = (int) ($params['page'] ?? 1);
            // id cấu hình //
            $filters['id'] = $params['id'] ?? "";
            // item trang //    
            $filters['perpage'] = $params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP;
            // danh sách item cấu hình hiển thị
            $listConfigDisplay = $this->mConfigDisplayDetail->getListNew($filters);
            // màn hình hiển thị //
            $site = $params['site'];
            // view hiển thị list danh sách //
            $view = view('config-display::list.config_display_detail', [
                'listConfigDisplayDetail' => $listConfigDisplay,
                'site' => $site
            ])->render();
            // dữ liệu trả về view hiển thị
            $result = [
                'view' => $view,
                'error' => false,
            ];
            return $result;
        } catch (\Exception $e) {

            Log::info('Load_all_config_display_detail : ' . $e->getMessage());
            return [
                'view' => '',
                'error' => true
            ];
        }
    }

    /**
     * get danh mục cấu hình hiển thị
     * @return mixed
     */

    public function getCategoryConfigDetail()
    {
        $mConfigCategoryDetailTable = app()->get(ConfigCategoryDetailTable::class);
        $result = $mConfigCategoryDetailTable->getAll();
        return $result;
    }

    /**
     * lấy tất cả khảo sát
     * @return mixed
     */

    public function getAllSurvey()
    {
        try {
            $mSurvey = app()->get(SurveyTable::class);
            $data = $mSurvey->getAll();
            $result = [
                'erorr' => false,
                'data' => $data,
            ];
            return $result;
        } catch (\Exception $e) {
            Log::info("Get all survey config : " . $e->getMessage());
            return [
                'erorr' => true,
                'data' => ''
            ];
        }
    }

    /**
     * lấy vị trí hiển thị lớn nhất của item cấu hình hiển thị
     * @param int $id
     * @return mixed
     */

    public function getPositionMax($id)
    {
        $positionMax = $this->mConfigDisplayDetail->getPositionMax($id);
        $result =  $this->mConfigDisplayDetail::POSITION_DEFAULT;
        if ($positionMax) {
            $result = $positionMax->position;
        }
        return $result;
    }

    /**
     * Tạo mới banner
     * @param array $params
     * @return mixed
     */

    public function storeConfigDetail($params)

    {

        try {
            DB::beginTransaction();
            // check status //
            $status = $params['status'] ?? "";
            $isActive = $this->checkStatus($status);
            if ($isActive) {
                // cập nhật lại ví trị và lấy vị trí tạo chuẩn //
                $postion = $this->syncPositionConfigDisplayDetail($params['id_config_display'], (int)$params['position']);
                $params['position'] = $postion;
            } else {
                $params['position'] = 0;
            }
            $params['params_action'] = $this->handleActionParam($params['destination'], $params['destination_detail']);
            // tạo banner //
            $itemConfigDisplayDetail = $this->mConfigDisplayDetail->create($params)->id_config_display_detail;
            DB::commit();
            return [
                'error' => false,
                'id' => $itemConfigDisplayDetail,
                'message' => __("Tạo banner thành công ")
            ];
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('Error Created banner : ' . ' ' . $e->getMessage());
            return [
                'error' => true,
                'message' => __('Tạo banner thất bại')
            ];
        }
    }

    /**
     * get action_params 
     * @param $destination_detail
     */

    public function handleActionParam($destination, $destination_detail)
    {
        $paramAction = [];
        if ($destination == 'promotion') {
            $paramAction = $this->handleActionParamPromotion($destination_detail);
        } else if ($destination == 'survey') {
            $paramAction = $this->handleActionParamSurvey($destination_detail);
        } else if ($destination == 'product_detail') {
            $paramAction = $this->handleActionParamProduct($destination_detail);
        } else if ($destination == 'post_detail') {
            $paramAction = $this->handleActionParamPost($destination_detail);
        } else {
            $paramAction = json_encode($paramAction);
        }

        return $paramAction;
    }

    /**
     * action_params promotion
     * @param $destination_detail
     */

    public function handleActionParamPromotion($destination_detail)
    {
        $paramAction = [];
        $mPromotionMaster = app()->get(PromotionMasterTable::class);
        $promotion =  $mPromotionMaster->find($destination_detail);
        if ($promotion) {
            $paramAction['object_id'] = $promotion->promotion_id;
            $paramAction['object_code'] = $promotion->promotion_code;
        }
        return json_encode($paramAction);
    }

    /**
     * action_params product
     * @param $destination_detail
     */

    public function handleActionParamProduct($destination_detail)
    {
        $paramAction = [];
        $mProduct = app()->get(ProductTable::class);
        $product =  $mProduct->find($destination_detail);
        if ($product) {
            $paramAction['object_id'] = $product->product_id;
            $paramAction['object_code'] = $product->product_code;
        }
        return json_encode($paramAction);
    }

    /**
     * action_params post
     * @param $destination_detail
     */

    public function handleActionParamPost($destination_detail)
    {
        $paramAction = [];
        $mPost = app()->get(NewTable::class);
        $post =  $mPost->find($destination_detail);
        if ($post) {
            $paramAction['object_id'] = $post->new_id;
            $paramAction['object_code'] = "";
        }
        return json_encode($paramAction);
    }

    /**
     * action_params survey
     * @param $destination_detail
     */

    public function handleActionParamSurvey($destination_detail)
    {
        $paramAction = [];
        $mSurvey = app()->get(SurveyTable::class);
        $survey =  $mSurvey->find($destination_detail);
        if ($survey) {
            $paramAction['object_id'] = $survey->survey_id;
            $paramAction['object_code'] = $survey->survey_code;
        }
        return json_encode($paramAction);
    }

    /**
     * lấy 1 record có vị trí nhỏ hơn hoặc bằng vị trí hiện tại
     * @param $id 
     * @param $position 
     * @return mixed
     */

    public function getPositionPre($id, $position)
    {

        $result = $this->mConfigDisplayDetail->getPositionPre($id, $position);
        return $result;
    }

    /**
     * lấy tất record có vị trí lớn hơn hoặc bằng vị trí hiện tại
     * @param $id 
     * @param $position 
     * @return mixed
     */

    public function getPositionNext($id, $position)
    {

        $result = $this->mConfigDisplayDetail->getPositionNext($id, $position);
        return $result;
    }

    /**
     * Đồng bộ lại vị trí hiển thị
     * @param $id 
     * @param $position 
     * @return mixed
     */

    public function syncPositionConfigDisplayDetail($id, $position)
    {

        // vị trí mặc định //
        $positionDefault = $position;
        // vị trí lớn hơn //
        $postionNextDefault = $position;
        // lấy vị trí nhỏ hơn hoặc bằng //
        $positionPre = $this->getPositionPre($id, $position);
        // lấy vị trí lớn hơn hoặc bằng //
        $positionNext = $this->getPositionNext($id, $position);
        // trường hợp tạo phần tử đầu tiên trong cấu hình hiển thị 
        if (!$positionPre && $positionNext->count() <= 0) {
            $positionDefault = 1;
            return $positionDefault;
        }
        // trường hợp vị trí hiện tại là có phần tử có vị trí  nhỏ hơn
        if ($positionPre) {
            // lấy số vị trí của phần tử nhỏ hơn hoặc bằng
            $positionPreValue = $positionPre->position;
            // trường hợp nếu vị trí pre = với vị trí tạo 
            if ($positionPreValue == $positionDefault) {
                $positionPre->update([
                    'position' => $positionDefault + 1
                ]);
                // cập nhật vị trí lớn hơn
                $postionNextDefault = $positionDefault + 1;
            } else {
                // cập nhật vị trí mặc định 
                $positionDefault = $positionPreValue + 1;
                // cập nhật lại vị trí lớn hơn
                $postionNextDefault = $positionPreValue + 1;
            }
        }
        // trường hợp ví trí hiện tại có những phần tử có vị trí lớn hơn 
        if ($positionNext->count() > 0) {
            // cập nhật lại vị trí lớn hơn mặc định //
            foreach ($positionNext as $item) {
                $item->update([
                    'position' => $postionNextDefault
                ]);
                $postionNextDefault = $postionNextDefault + 1;
            }
        }
        return $positionDefault;
    }

    /**
     * Đồng bộ lại vị trí hiển thi khi cập nhật
     * @param $itemConfigDisplayDetail,
     * @param $positionNew
     * @return mixed
     */

    public function syncUpdatePositionConfigDisplayDetail($itemConfigDisplayDetail, $positionNew)
    {
        $idConfigDisplay = $itemConfigDisplayDetail->id_config_display;
        $idConfigDisplayDetail = $itemConfigDisplayDetail->id_config_display_detail;
        $position = $itemConfigDisplayDetail->position;
        // trường hợp nếu item cấu hình mặc định ở trạng thái ngưng hoạt động và cập nhật lại hoat đông
        if ($itemConfigDisplayDetail->status == 0) {
            $positionNew = $this->syncPositionConfigDisplayDetail($idConfigDisplay, $positionNew);
        } else {
            // trường hợp nếu item cấu hình mặt định chi tiết đang ở trạng thái hiển thị 
            // lấy item cấu hình chi tiết cần đảo vị trí //
            $itemReversePosition = $this->mConfigDisplayDetail->getItemByPosition($idConfigDisplay, $positionNew);
            if ($itemReversePosition) {
                $itemReversePosition->update([
                    'position' => $position
                ]);
                return $positionNew;
            }
            $positionMax = $this->mConfigDisplayDetail->getPositionMax($idConfigDisplay);
            // kiểm tra vị trí lớn nhất và cập nhật lại các vị trí
            if ($positionMax) {
                $listPostionNext = $this->mConfigDisplayDetail->getPositionNextCondition($idConfigDisplay, $idConfigDisplayDetail, $position);
                if ($listPostionNext->count() > 0) {
                    foreach ($listPostionNext as $item) {
                        $item->update([
                            'position' => $item->position - 1
                        ]);
                    }
                }
                $positionNew = $positionMax->position;
            } else {
                $positionNew = $this->mConfigDisplayDetail::POSITION_DEFAULT;
            }
        }
        return $positionNew;
    }


    /**
     * Đồng bộ lại vị trí hiển thị nếu trường hợp vị trị cập nhật trạng thái không hoạt động
     * @param int $id
     * @param int $position
     * @return mixed
     */

    public function syncPositionBeforeUpdate($id, $position)
    {
        // lấy vị trí lớn hơn hoặc bằng //
        $positionNext = $this->getPositionNext($id, $position);
        if ($positionNext->count() > 0) {
            foreach ($positionNext as $item) {
                $decrementPosition = $item->position - 1  > 0 ? $item->position - 1 : 1;
                $item->update([
                    'position' => $decrementPosition
                ]);
            }
        }
    }

    /**
     * kiểm tra trang thái hoạt động 
     * @param $status
     * @return bool
     */

    public function checkStatus($status)
    {
        if (!$status) return false;
        return true;
    }

    /**
     * get item cấu hình hiển thị chi tiết
     * @param $id
     * @return mixed
     */

    public function getItem($id)
    {
        $itemConfigDisplayDetail = $this->mConfigDisplayDetail->with([
            'survey' => function ($q) {
                $q->select("survey_id", "survey_name");
            },
            'categoryDestination' => function ($q) {
                $q->select("key_destination", "name");
            },
            'promotion' => function ($q) {
                $q->select("promotion_id", "promotion_name");
            },
            'product' => function ($q) {
                $q->select("product_id", "product_name");
            },
            'post' => function ($q) {
                $q->select("new_id", "title_vi");
            }
        ])->find($id);
        if (!$itemConfigDisplayDetail) return abort(404);
        return $itemConfigDisplayDetail;
    }

    /**
     * Cập nhật banner
     * @param array $params
     * @return mixed
     */

    public function updateConfigDetail($params)
    {
        try {
            DB::beginTransaction();
            // check status //
            $status = $params['status'] ?? "";
            $isActive = $this->checkStatus($status);
            $itemConfigDisplayDetail = $this->mConfigDisplayDetail->find($params['id_config_display_detail']);
            if (!$itemConfigDisplayDetail) return abort(404);
            if ($isActive) {
                // cập nhật lại ví trị và lấy vị trí tạo chuẩn //
                if ($this->checkPostionBeforeUpdate($itemConfigDisplayDetail->position, $params['position'])) {
                    $postion = $this->syncUpdatePositionConfigDisplayDetail($itemConfigDisplayDetail, $params['position']);
                    $params['position'] = $postion;
                }
            } else {
                $params['position'] = 0;
                if ($this->checkPostionBeforeUpdate($itemConfigDisplayDetail->position, $params['position'])) {
                    $this->syncPositionBeforeUpdate($params['id_config_display'], (int)$itemConfigDisplayDetail->position);
                }
            }
            $params['params_action'] = $this->handleActionParam($params['destination'], $params['destination_detail']);
            // tạo banner //
            $itemConfigDisplayDetail->update($params);
            DB::commit();
            return [
                'error' => false,
                'id' => $itemConfigDisplayDetail->id_config_display_detail,
                'message' => __("Cập nhật banner thành công")
            ];
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('Error Created banner : ' . ' ' . $e->getMessage());
            return [
                'error' => true,
                'message' => __('Cập nhật banner thất bại')
            ];
        }
    }

    /**
     * Kiểm tra vị trí hiển thị trước khi cập nhật
     * @param $postionOld
     * @param $postionNew
     * @return boolean
     */

    public function checkPostionBeforeUpdate($postionOld, $postionNew)
    {
        $flash = false;
        if ($postionOld != $postionNew) {
            $flash = true;
        };
        return $flash;
    }

    /**
     * Xoá banner 
     * @param array $params
     * @return mixed
     */

    public function destroy($params)
    {
        $itemConfigDisplayDetail = $this->mConfigDisplayDetail->find($params['idConfigDetail']);
        if (!$itemConfigDisplayDetail) {
            return [
                'error' => true
            ];
        }
        $this->syncPositionBeforeUpdate(
            $itemConfigDisplayDetail->id_config_display,
            $itemConfigDisplayDetail->position
        );
        $itemConfigDisplayDetail->delete();
        return [
            'error' => false
        ];
    }

    /**
     * Load all promition 
     * @return mixed
     */

    public function getAllPromotion()
    {
        try {
            $listPromitions = app()->get(PromotionMasterTable::class);
            $data = $listPromitions->getAll();
            $result = [
                'erorr' => false,
                'data' => $data,
            ];
            return $result;
        } catch (\Exception $e) {
            Log::info("Get all promotion config : " . $e->getMessage());
            return [
                'erorr' => true,
                'data' => ''
            ];
        }
    }

    /**
     * Danh sách sản phẩm
     * @param Request $request
     * @return mixed 
     */

    public function getAllProduct()
    {
        try {
            $listProduct = app()->get(ProductTable::class);
            $data = $listProduct->getAll();
            $result = [
                'erorr' => false,
                'data' => $data,
            ];
            return $result;
        } catch (\Exception $e) {
            Log::info("Get all product config : " . $e->getMessage());
            return [
                'erorr' => true,
                'data' => ''
            ];
        }
    }

    /**
     * Danh sách bài viết
     * @param Request $request
     * @return mixed 
     */

    public function getAllPost()
    {
        try {
            $listPost = app()->get(NewTable::class);
            $data = $listPost->getAll();
            $result = [
                'erorr' => false,
                'data' => $data,
            ];
            return $result;
        } catch (\Exception $e) {
            Log::info("Get all post config : " . $e->getMessage());
            return [
                'erorr' => true,
                'data' => ''
            ];
        }
    }
}

<?php


namespace Modules\ConfigDisplay\Repositories;

use Illuminate\Support\Facades\Log;
use Modules\ConfigDisplay\Models\ConfigDisplayTable;

class ConfigDisplayRepo implements ConfigDisplayRepoInterface
{

    protected $mConfigDisplay;
    public function __construct(ConfigDisplayTable $mConfigDisplay)
    {
        $this->mConfigDisplay = $mConfigDisplay;
    }


    /**
     * Lấy tất cả cấu hình hiển thị 
     * @return mixed
     */

    public function getAll(array $params)
    {
        try {
            // Tên trang //
            $filters['keyword_config_display$name_page'] = $params['namePage'] ?? "";
            // Vị trí trang //
            $filters['config_display$position_page'] = $params['position'] ?? "";
            // Loại template //
            $filters['config_display$type_template'] = $params['typeTemplate'] ?? "";
            // số trang //  
            $filters['page'] = (int) ($params['page'] ?? 1);
            // item trang //
            $filters['perpage'] = $params['perpage'] ?? PAGING_ITEM_PER_PAGE_POPUP;
            // danh sách item cấu hình hiển thị
            $listConfigDisplay = $this->mConfigDisplay->getListNew($filters);
            // view hiển thị list danh sách //
            $view = view('config-display::list.config_display', ['listConfigDisplay' => $listConfigDisplay])->render();
            // dữ liệu trả về view hiển thị
            $result = [
                'view' => $view,
                'error' => false
            ];
            return $result;
        } catch (\Exception $e) {

            Log::info('Load_all_config_display : ' . $e->getMessage());
            return [
                'view' => '',
                'error' => true
            ];
        }
    }

    /**
     * Get type template 
     * @return array
     */

    public function getTypeTemplate()
    {
        return $this->mConfigDisplay::TYPE_TEMPLATE;
    }
}

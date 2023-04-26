<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/4/2019
 * Time: 16:17
 */

namespace Modules\User\Repositories\AdminMenu;


use Carbon\Carbon;
use Modules\User\Models\ActionGroupTable;
use Modules\User\Models\ActionTable;
use Modules\User\Models\AdminFeatureTable;
use Modules\User\Models\AdminMenuTable;
use Modules\User\Models\PageTable;

class AdminMenuRepository implements AdminMenuRepositoryInterface
{
    protected $admin_menu;
    protected $timestamps = true;

    public function __construct(AdminMenuTable $admin_menu)
    {
        $this->admin_menu = $admin_menu;
    }

    public function groupCategory($menu_category_id)
    {
        // TODO: Implement groupCategory() method.
        // dd(1);
        return $this->admin_menu->groupCategory($menu_category_id);
    }

    /**
     * Đồng bộ quyền feature
     *
     * @return mixed|void
     */
    public function syncFeature()
    {
        $mActionGroup = app()->get(ActionGroupTable::class);
        //Lấy tất cả action_group
        $getGroup = $mActionGroup->getGroup();

        if (count($getGroup) > 0) {
            $mAction = app()->get(ActionTable::class);
            $mPage = app()->get(PageTable::class);
            $mAdminFeature = app()->get(AdminFeatureTable::class);

            foreach ($getGroup as $v) {
                $arrAction = [];
                $arrPage = [];
                $arrFullName = [];
                $arrFeature = [];
                //Lấy tất cả quyền action theo group
                $getAction = $mAction->getActionByGroup($v['action_group_id']);
                //Lấy tất cã quyền pages theo group
                $getPage = $mPage->getPageByGroup($v['action_group_id']);
                //Lấy action + page theo group (merge mã code lại)

                if (count($getAction) > 0) {
                    foreach ($getAction as $v1) {
                        $arrAction [] = $v1['route'];
                        $arrFullName [$v1['route']] = $v1['name'];
                    }
                }

                if (count($getPage) > 0) {
                    foreach ($getPage as $v2) {
                        $arrPage [] = $v2['route'];
                        $arrFullName [$v2['route']] = $v2['name'];
                    }
                }
                $arrMerge = array_merge($arrAction, $arrPage);
                //Lấy feature theo group
                $getFeature = $mAdminFeature->getFeatureByGroup($v['action_group_id']);

                if (count($getFeature) > 0) {
                    foreach ($getFeature as $v3) {
                        $arrFeature [] = $v3['feature_code'];
                    }
                }

                $result = array_diff($arrMerge, $arrFeature);
                //So sánh cái nào khác thì insert
                $arrInsert = [];

                if (count($result) > 0) {
                    foreach ($result as $v4) {
                        if (isset($arrFullName[$v4])) {
                            $arrInsert [] = [
                                "feature_group_id" => $v['action_group_id'],
                                "feature_name_vi" => $arrFullName[$v4],
                                "feature_code" => $v4,
                                "service_type" => "portal",
                                "platform_type" => "epoint",
                                "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                                "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                            ];
                        }
                    }
                }
                //Insert admin_feature
                $mAdminFeature->insert($arrInsert);
            }
        }
    }
}
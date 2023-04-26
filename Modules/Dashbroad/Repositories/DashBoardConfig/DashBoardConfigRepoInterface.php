<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 9/29/2021
 * Time: 1:55 PM
 * @author nhandt
 */

namespace Modules\Dashbroad\Repositories\DashBoardConfig;


interface DashBoardConfigRepoInterface
{
    public function getList(array &$filters = []);
    public function popCreateDashboardConfig($input);
    public function savePopCreateDashboardConfig($input);
    public function getListWidget($input);
    public function createDashboardAction($input);
    public function removeDashboardAction($input);
    public function getDetail($input);
    public function changeStatusAction($input);
    public function popEditDashboardConfig($input);
    public function savePopEditDashboardConfig($input);
    public function editDashboardAction($input);
}
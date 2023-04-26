<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/9/2019
 * Time: 9:35 AM
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\Action\ActionRepositoryInterface;
use Modules\Admin\Repositories\Page\PageRepositoryInterface;
use Modules\Admin\Repositories\RoleAction\RoleActionRepositoryInterface;
use Modules\Admin\Repositories\RoleGroup\RoleGroupRepositoryInterface;
use Modules\Admin\Repositories\RolePage\RolePageRepositoryInterface;
use Modules\Admin\Repositories\StaffTitle\StaffTitleRepositoryInterface;

class AuthorizationController extends Controller
{
    protected $staffTitle;
    protected $page;
    protected $action;
    protected $roleAction;
    protected $rolePage;
    protected $roleGroup;

    public function __construct(
        StaffTitleRepositoryInterface $staffTitle,
        PageRepositoryInterface $page,
        ActionRepositoryInterface $action,
        RoleActionRepositoryInterface $roleAction,
        RolePageRepositoryInterface $rolePage,
        RoleGroupRepositoryInterface $roleGroup
    )
    {
        $this->staffTitle = $staffTitle;
        $this->page = $page;
        $this->action = $action;
        $this->roleAction = $roleAction;
        $this->rolePage = $rolePage;
        $this->roleGroup = $roleGroup;
    }

    public function indexAction()
    {
        $roleGroup = $this->roleGroup->getList2();
        return view('admin::authorization.index', ['roleGroup' => $roleGroup]);
    }

    public function editAction($id)
    {
        $pages = $this->page->getList();
        $arrayRolePage = [];
        $arrayRoleAction = [];
        foreach ($pages as $item) {
            $checkRolePage = $this->rolePage->checkIssetRole($id, $item['id']);
            $rolePage = 0;
            if ($checkRolePage != null) {
                $rolePage = $checkRolePage['is_actived'];
            }
            $arrayRolePage[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'route' => $item['route'],
                'role_page' => $rolePage
            ];
        }

        $action = $this->action->getList();

        foreach ($action as $item) {
            $checkRoleAction = $this->roleAction->checkIssetRole($id, $item['id']);
            $roleAction = 0;
            if ($checkRoleAction != null) {
                $roleAction = $checkRoleAction['is_actived'];
            }
            $arrayRoleAction[] = [
                'id' => $item['id'],
                'title' => $item['title'],
                'name' => $item['name'],
                'role_action' => $roleAction
            ];
        }

        return view('admin::authorization.edit', [
            'pages' => $arrayRolePage,
            'action' => $arrayRoleAction,
            'staffTitleId' => $id
        ]);
    }

    public function checkAllRolePage(Request $request)
    {
        $isCheckAll = $request->isCheckAll;
        $groupId = $request->groupId;

        //Danh sách id page.
        $arrayRolePage = $request->arrayRolePage;

        foreach ($arrayRolePage as $key => $value) {
            $checkIssetRolePage = $this->rolePage->checkIssetRole($groupId, $value);
            if ($checkIssetRolePage == null) {
                $data = [
                    'group_id' => $groupId,
                    'page_id' => $value,
                    'is_actived' => $isCheckAll
                ];
//                var_dump($data);die;
                $this->rolePage->add($data);
            } else {
                $dataUpdate = [
                    'is_actived' => $isCheckAll,
                    'group_id' => $groupId,
                ];
                $this->rolePage->edit($dataUpdate, $checkIssetRolePage['role_id']);
            }
        }
    }

    public function checkEachRolePage(Request $request)
    {
        $isCheck = $request->isCheck;
        $staffTitleId = $request->staffTitleId;
        $idPage = $request->idPage;
        $checkIssetRolePage = $this->rolePage->checkIssetRole($staffTitleId, $idPage);
        if ($checkIssetRolePage == null) {
            $data = [
                'group_id' => $staffTitleId,
                'page_id' => $idPage,
                'is_actived' => $isCheck
            ];
            $this->rolePage->add($data);
        } else {
            $dataUpdate = [
                'is_actived' => $isCheck,
                'group_id' => $staffTitleId,
            ];
            $this->rolePage->edit($dataUpdate, $checkIssetRolePage['role_id']);
        }
    }

    public function checkAllRoleAction(Request $request)
    {
        $isCheckAll = $request->isCheckAll;
        $staffTitleId = $request->staffTitleId;
        //Danh sách id page.
        $arrayRoleAction = $request->arrayRolePage;

        foreach ($arrayRoleAction as $key => $value) {
            $checkIssetRoleAction = $this->roleAction->checkIssetRole($staffTitleId, $value);
            if ($checkIssetRoleAction == null) {
                $data = [
                    'group_id' => $staffTitleId,
                    'action_id' => $value,
                    'is_actived' => $isCheckAll
                ];
                $this->roleAction->add($data);
            } else {
                $dataUpdate = [
                    'is_actived' => $isCheckAll,
                    'group_id' => $staffTitleId,
                ];
                $this->roleAction->edit($dataUpdate, $checkIssetRoleAction['role_action_id']);
            }
        }
    }

    public function checkEachRoleAction(Request $request)
    {
        $isCheck = $request->isCheck;
        $staffTitleId = $request->staffTitleId;
        $idAction = $request->idAction;
        $checkIssetRoleAction = $this->roleAction->checkIssetRole($staffTitleId, $idAction);
        if ($checkIssetRoleAction == null) {
            $data = [
                'group_id' => $staffTitleId,
                'action_id' => $idAction,
                'is_actived' => $isCheck
            ];
            $this->roleAction->add($data);
        } else {
            $dataUpdate = [
                'is_actived' => $isCheck,
                'group_id' => $staffTitleId,
            ];
            $this->roleAction->edit($dataUpdate, $checkIssetRoleAction['role_action_id']);
        }
    }

}
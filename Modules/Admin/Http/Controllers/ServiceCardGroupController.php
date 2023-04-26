<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 5/4/2019
 * Time: 11:27 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\ServiceCardGroup\ServiceCardGroupRepositoryInterface;

class ServiceCardGroupController extends Controller
{
    protected $serviceCardGroup;

    public function __construct(ServiceCardGroupRepositoryInterface $serviceCardGroup)
    {
        $this->serviceCardGroup = $serviceCardGroup;
    }

    public function indexAction()
    {
        $serviceCardGroup = $this->serviceCardGroup->list();
        return view('admin::service-card-group.index', [
            'LIST' => $serviceCardGroup,
        ]);
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword']);
        $list = $this->serviceCardGroup->list($filters);
        return view('admin::service-card-group.list', ['LIST' => $list, 'page' => $filters['page']]);
    }

    public function submitAdd(Request $request)
    {
        $name = $request->name;
        $description = $request->description;
        $checkSlug = $this->serviceCardGroup->checkSlug(str_slug($name), 0);
        if ($checkSlug == null) {
            $data = [
                'name' => $name,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->staff_id,
                'slug' => str_slug($name)
            ];
            $this->serviceCardGroup->add($data);
            $option = $this->serviceCardGroup->getOption();
            return response()->json([
                'error' => 0,
                'optionCardGroup' => $option
            ]);
        } else {
            return response()->json(['error' => 1]);
        }
    }

    public function removeAction($id)
    {
        $this->serviceCardGroup->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function editAction(Request $request)
    {
        $id = $request->id;
        $data = $this->serviceCardGroup->getItem($id);
        return response()->json([
            'service_card_group_id' => $data['service_card_group_id'],
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
    }

    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $description = $request->description;
        $checkSlug = $this->serviceCardGroup->checkSlug(str_slug($name), $id);
        if ($checkSlug == null) {
            $data = [
                'name' => $name,
                'description' => $description,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->staff_id,
                'slug' => str_slug($name)
            ];
            $this->serviceCardGroup->edit($data, $id);
            return response()->json(['error' => 0]);
        } else {
            return response()->json(['error' => 1]);
        }
    }
}
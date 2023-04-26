<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 1:13 PM
 */

namespace Modules\Admin\Http\Controllers;

use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Supplier\SupplierRepositoryInterface;

class SupplierController extends Controller
{
    protected $supplier;

    public function __construct(SupplierRepositoryInterface $supplier)
    {
        $this->supplier = $supplier;
    }

    public function indexAction()
    {
        $supplierList = $this->supplier->list();
        return view('admin::supplier.index', [
            'LIST' => $supplierList,
        ]);
    }

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword']);
        $supplierList = $this->supplier->list($filters);
        return view('admin::supplier.list', ['LIST' => $supplierList, 'page' => $filters['page']]);
    }

    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $name = $request->supplierName;
            $parameters = $request->parameters;
            $checkExistDelete = $this->supplier->checkExist($name, 1);
            $checkExistUnDelete = $this->supplier->checkExist($name, 0);
            if ($checkExistUnDelete != null) {
                return response()->json(['status' => 0, 'parameters' => $parameters]);
            } else {
                if ($checkExistDelete != null) {
                    $this->supplier->edit(['is_deleted' => 0], $checkExistDelete->supplier_id);
                    return response()->json(['status' => 1]);
                } else {
                    if ($checkExistUnDelete == null && $checkExistDelete == null) {
                        $data = [
                            'supplier_name' => $name,
                            'description' => $request->description,
                            'address' => $request->address,
                            'contact_name' => $request->contactName,
                            'contact_title' => $request->contactTitle,
                            'contact_phone' => $request->contactPhone,
                            'slug'=>str_slug($name)
                        ];
                        $this->supplier->add($data);
                        return response()->json(['status' => 1, 'parameters' => $parameters]);
                    }
                }
            }
        }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->supplierId;
            $item = $this->supplier->getItem($id);
            $jsonString = [
                "id" => $id,
                "supplierName" => $item->supplier_name,
                "description" => $item->description,
                "address" => $item->address,
                "contact_name" => $item->contact_name,
                "contact_title" => $item->contact_title,
                "contact_phone" => $item->contact_phone,
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $name = $request->supplierName;
            $check = $this->supplier->check($id, $name);
            $testIsDeleted = $this->supplier->checkExist($name, 1);
            if ($request->parameter == 0) {
                if ($testIsDeleted != null) {
                    //Tồn nhà cung cấp trong db. is_deleted = 1.
                    return response()->json(['status' => 2]);
                } else {
                    if ($check == null) {
                        $data = [
                            'supplier_name' => $request->supplierName,
                            'description' => $request->description,
                            'address' => $request->address,
                            'contact_name' => $request->contactName,
                            'contact_title' => $request->contactTitle,
                            'contact_phone' => $request->contactPhone,
                            'slug'=>str_slug($request->supplierName)
                        ];
                        $this->supplier->edit($data, $id);
                        return response()->json(['status' => 1]);
                    } else {
                        return response()->json(['status' => 0]);
                    }
                }
            } else {
                //Kích hoạt lại tên nhóm khách hàng.
                $this->supplier->edit(['is_deleted' => 0], $testIsDeleted->supplier_id);
                return response()->json(['status' => 3]);
            }


        }
    }

    public function removeAction($id)
    {
        $this->supplier->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/28/2018
 * Time: 5:20 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\ProductModel\ProductModelRepositoryInterface;

class ProductModelController extends Controller
{
    /**
     * Product Model Repository Interface
     */
    protected $productModel;

    public function __construct(ProductModelRepositoryInterface $productModel)
    {
        $this->productModel = $productModel;
    }

    //function index
    public function indexAction()
    {
        $productModelList = $this->productModel->list();
        return view('admin::product-model.index', [
            'LIST' => $productModelList,
        ]);
    }

    /*
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $productModelList = $this->productModel->list($filters);
        return view('admin::product-model.list', [
            'LIST' => $productModelList,
            'page'=>$filters['page']
        ]);
    }

    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $name = $request->productModelName;
            $note = $request->productModelNote;

            $check = $this->productModel->check($name, 0);
            $checkIsDelete = $this->productModel->check($name, 1);
            if ($checkIsDelete != null) {
                $this->productModel->editByName($name);
                $model = $this->productModel->getAll();
                return response()->json(['status' => 1, 'model' => $model]);
            } else {
                if ($check == null) {
                    $data = [
                        'product_model_name' => $name,
                        'product_model_note' => $note,
                        'slug'=>str_slug($name)
                    ];
                    $id = $this->productModel->add($data);
                    $model = $this->productModel->getAll();
                    return response()->json(['status' => 1, 'model' => $model, 'id' => $id]);
                } else {
                    return response()->json(['status' => 0]);
                }
            }
        }
    }

    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $item = $this->productModel->getItem($id);
            $data = [
                'product_model_id' => $id,
                'product_model_name' => $item->product_model_name,
                'product_model_note' => $item->product_model_note,
            ];
            return response()->json($data);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $name = $request->productModelName;
            $note = $request->productModelNote;
            $checkedit = $this->productModel->checkEdit($id, $name);
            $checkIsDelete = $this->productModel->check($name, 1);
            if ($request->parameter == 0) {
                if ($checkIsDelete != null) {
                    //Tồn tại nhãn trong db. is_deleted = 1.
                    return response()->json(['status' => 2]);
                } else {
                    if ($checkedit == null) {
                        $data = [
                            'product_model_name' => $name,
                            'product_model_note' => $note,
                            'slug'=>str_slug($name)
                        ];
                        $this->productModel->edit($data, $id);
                        return response()->json(['status' => 1]);
                    } else {
                        //Nhãn sản phẩm đã tồn tại.
                        return response()->json(['status' => 0]);
                    }
                }

            } else {
                //Kích hoạt lại nhãn sản phẩm.
                $this->productModel->edit(['is_deleted' => 0], $checkIsDelete->product_model_id);
                return response()->json(['status' => 3]);
            }
        }
    }

    public function removeAction($id)
    {
        $this->productModel->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }
}
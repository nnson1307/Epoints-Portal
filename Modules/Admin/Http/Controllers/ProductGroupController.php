<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/03/2018
 * Time: 10:38 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\ProductGroupTable;
use Modules\Admin\Repositories\ProductGroup\ProductGroupRepositoryInterface;


class ProductGroupController extends Controller
{

    protected $productGroup;


    public function __construct(ProductGroupRepositoryInterface $productGroup)
//        $nhan->add($data)
    {
        $this->productGroup = $productGroup;
    }


    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction(Request $request)
    {
        $productGroupList = $this->productGroup->list();

        return view('admin::product-group.index', [
            'LIST' => $productGroupList,
            'FILTER' => $this->filters()
        ]);
    }

    public function getProductListAction()
    {

    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'is_active' => [
                'text' => __('Trạng thái:'),
                'data' => [
                    '' => 'Tất cả',
                    1 => 'Đang hoạt động',
                    0 => 'Tạm ngưng'
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách user
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $productGroupList = $this->productGroup->list($filters);


        return view('admin::product-group.list', ['LIST' => $productGroupList]);
    }


    /**
     * Xóa user
     *
     * @param number $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAction($id)
    {
        $this->productGroup->remove($id);

        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }


    /**
     * Form thêm user
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function addAction()
    {

        return view('admin::product-group.add');
    }

    /**
     * Xử lý thêm user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitaddAction(Request $request)
    {
        $data = $this->validate($request, [
            'product_group_name' => 'required|unique:product_group',
            'product_group_code' => 'required|unique:product_group'
        ],[
            'product_group_name.required'   => "Nhóm sản phẩm bắt buộc",
            'product_group_name.unique'     => "Nhóm sản phẩm không được trùng ",
            'product_group_code.required'   => "Mã nhóm sản phẩm bắt buộc",
            'product_group_code.unique'     => "Mã nhóm sản phẩm không được trùng "
        ]);
        $data['is_active']                  = (int) $request->is_active;
        $data['created_at']                 = date('Y-m-d H:i:s') ;
        $oProductGroup       = $this->productGroup->add($data);

        if ($oProductGroup) {
            // display  info  status update
//            dd("status");
            $request->session()->flash('status', 'Tạo nhóm sản phẩm thành công!');
        }

        return redirect()->route('product-group');
    }


    public function editAction($id)
    {

        $item = $this->productGroup->getItem($id);
        return view('admin::product-group.edit', compact('item'));
    }

    public function submitEditAction(Request $request, $id)
    {

        $data = $this->validate($request, [
            'product_group_name' => 'required|unique:product_group,product_group_name,'.$id.',product_group_id',
            'product_group_code' => 'required|unique:product_group,product_group_code,'.$id.',product_group_id',
            'product_group_description' => 'required'
        ],[
            'product_group_name.required'=>"Nhóm sản phẩm bắt buộc",
            'product_group_name.unique'=>"Nhóm sản phẩm không được trùng ",
            'product_group_code.required'=>"Mã nhóm sản phẩm bắt buộc",
            'product_group_code.unique'=>"Mã nhóm sản phẩm không được trùng"
        ]);


//
        $data['is_active'] = (int)$request->is_active;

        $oProductGroup = $this->productGroup->edit($data, $id);

        if ($oProductGroup) {
            // display  info  status update
            $request->session()->flash('status', 'Cập nhât dữ liệu thành công!');
        }


        return redirect()->route('product-group')->with('success', 'Item updated successfully');


    }


    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_active'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->productGroup->edit($data, $params['id']);
        return response()->json([
            'status' => 0,
            'messages' => 'Trạng thái đã được cập nhật '
        ]);
    }





}
<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\ProductLabel\ProductLabelRepositoryInterface;

class ProductLabelController extends Controller
{
    /**
     * @var ProductLabelRepositoryInterface
     */
    protected $productLabel;

    public function __construct(ProductLabelRepositoryInterface $productLabel)
    {
        $this->productLabel = $productLabel;
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

    public function indexAction(Request $request)
    {
        $productlabelList = $this->productLabel->list();
        return view('admin::product-label.index', [
            'LIST' => $productlabelList,
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Lấy danh sách product label sản phẩm
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $productlabelList = $this->productLabel->list($filters);

        return view('admin::product-label.list', ['LIST' => $productlabelList]);
    }

    /**
     * Xóa product label
     * @param number $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAction($id)
    {
        $this->productLabel->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    /**
     * return view add product label
     */
    public function addAction()
    {
        return view('admin::product-label.add');
    }

    /**
     * Xử lí thêm services
     * @param Request $request
     * @return mixed
     */
    public function submitAddAction(Request $request)
    {
        $data = $this->validate($request,
            [
                'product_label_name' => 'unique:product_label,product_label_name|required',
                'product_label_code' => 'unique:product_label,product_label_code|required',
                'is_active' => 'integer'
            ],
            [
                'product_label_name.required' => 'Vui lòng nhập tên nhãn hiệu',
                'product_label_code.required' => 'Vui lòng nhập mã nhãn hiệu',
                'product_label_name.unique' => 'Tên nhãn hiệu đã tồn tại',
                'product_label_code.unique' => 'Mã nhãn hiệu đã tồn tại',
            ]);
        $data['product_label_description']  = $request->input('product_label_description');
        $data['created_at']                 = date('Y-m-d H:i:s') ;
        $this->productLabel->add($data);
        return redirect()->route('admin.product-label');
    }

    /**
     * return view edit product label
     */
    public function editAction($id)
    {
        $item = $this->productLabel->getItem($id);
        return view('admin::product-label.edit', compact('item'));
    }

    /**
     * Xử lý sửa product label
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitEditAction(Request $request, $id)
    {
        $data = $this->validate($request,
            [
                'product_label_name' => 'required|unique:product_label,product_label_name,' . $id . ",product_label_id",
                'product_label_code' => 'required|unique:product_label,product_label_code,' . $id . ",product_label_id",
                'is_active' => 'integer'
            ],
            [
                'product_label_name.required' => 'Vui lòng nhập tên nhãn hiệu',
                'product_label_code.required' => 'Vui lòng nhập mã nhãn hiệu',
                'product_label_name.unique' => 'Tên nhãn hiệu đã tồn tại',
                'product_label_code.unique' => 'Mã nhãn hiệu đã tồn tại',
            ]);
        $data['product_label_description']  = $request->input('product_label_description');
        $this->productLabel->edit($data, $id);
        return redirect()->route('admin.product-label');
    }

    /**
     * Xử lý thay đổi trạng thái
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_active'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->productLabel->edit($data, $params['id']);
        return response()->json();
    }
}
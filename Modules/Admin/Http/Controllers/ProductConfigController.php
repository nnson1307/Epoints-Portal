<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 15:46
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\ProductConfig\ProductConfigRepoInterface;

class ProductConfigController extends Controller
{
    protected $productConfig;

    public function __construct(
        ProductConfigRepoInterface $productConfig
    ) {
        $this->productConfig = $productConfig;
    }

    /**
     * Lấy dữ liệu view
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->productConfig->getDataView();

        return view('admin::product-config.index', $data);
    }

    /**
     * Lưu thông tin
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        return $this->productConfig->update($request->all());
    }
}
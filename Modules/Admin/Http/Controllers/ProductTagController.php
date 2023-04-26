<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 10:09
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\ProductTag\StoreRequest;
use Modules\Admin\Http\Requests\ProductTag\UpdateRequest;
use Modules\Admin\Repositories\ProductTag\ProductTagRepoInterface;

class ProductTagController extends Controller
{
    protected $tag;

    public function __construct(
        ProductTagRepoInterface $tag
    ) {
        $this->tag = $tag;
    }

    /**
     * Thêm tag
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->tag->store($request->all());
    }

    /**
     * Danh sách tag
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->tag->list();

        return view('admin::product-tag.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {

        return [

        ];
    }

    /**
     * Ajax filter, phân trang ds pipeline
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at',
        ]);

        $data = $this->tag->list($filter);

        return view('admin::product-tag.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm tag
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function create()
    {
        return view('admin::product-tag.create');
    }

    /**
     * View chỉnh sửa tag
     *
     * @param $tagId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit($tagId)
    {
        $data = $this->tag->getDetail($tagId);

        return view('admin::product-tag.edit', [
            'data' => $data
        ]);
    }

    /**
     * Chỉnh sửa tag
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->tag->update($request->all());
    }

    /**
     * Xoá tag
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        $tagId = $request->product_tag_id;

        return $this->tag->deleteTag($tagId);
    }

}
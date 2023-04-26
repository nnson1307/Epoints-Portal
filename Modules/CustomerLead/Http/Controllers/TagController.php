<?php

namespace Modules\CustomerLead\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CustomerLead\Http\Requests\Tag\StoreRequest;
use Modules\CustomerLead\Http\Requests\Tag\UpdateRequest;
use Modules\CustomerLead\Repositories\Tag\TagRepoInterface;


class TagController extends Controller
{
    protected $tag;
    public function __construct(
        TagRepoInterface $tag
    ) {
        $this->tag = $tag;
    }

    public function index()
    {
        $data = $this->tag->list();
        return view('customer-lead::tag.index', [
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

        return view('customer-lead::tag.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    public function create()
    {
        return view('customer-lead::tag.create');
    }

    public function store(StoreRequest $request)
    {
        $input = $request->tag_name;
        return $this->tag->store($input);
    }

    public function edit($tagId)
    {
        $data = $this->tag->getDetail($tagId);
        return view('customer-lead::tag.edit', [
            'data' => $data
        ]);
    }

    public function update(UpdateRequest $request)
    {
        $input = $request->all();
        return $this->tag->update($input);
    }

    public function destroy(Request $request)
    {
        $tagId = $request->tag_id;
        return $this->tag->deleteTag($tagId);
    }
}
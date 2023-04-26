<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/11/2021
 * Time: 16:51
 */

namespace Modules\Contract\Http\Controllers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Modules\Contract\Repositories\Browse\BrowseRepoInterface;

class ContractBrowseController extends Model
{
    protected $contractBrowse;

    public function __construct(
        BrowseRepoInterface $contractBrowse
    ) {
        $this->contractBrowse = $contractBrowse;
    }

    /**
     * Danh sách HĐ cần phê duyệt
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->contractBrowse->list();

        return view('contract::browse.index', [
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
     * Ajax filter ds HĐ cần phê duyệt
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search',
            'created_at'
        ]);

        $data = $this->contractBrowse->list($filter);

        return view('contract::browse.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * Duyệt HĐ
     *
     * @param Request $request
     * @return mixed
     */
    public function confirmAction(Request $request)
    {
        return $this->contractBrowse->confirm($request->all());
    }

    /**
     * Show modal từ chối duyệt HĐ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalRefuseAction(Request $request)
    {
        $html = \View::make('contract::browse.pop.modal-reason-refuse', [
            'contract_browse_id' => $request->contract_browse_id
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Từ chối duyệt HĐ
     *
     * @param Request $request
     * @return mixed
     */
    public function refuseAction(Request $request)
    {
        return $this->contractBrowse->refuse($request->all());
    }
}
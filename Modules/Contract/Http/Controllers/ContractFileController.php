<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 10:39
 */

namespace Modules\Contract\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Contract\Http\Requests\ContractFile\StoreRequest;
use Modules\Contract\Http\Requests\ContractFile\UpdateRequest;
use Modules\Contract\Repositories\ContractFile\ContractFileRepoInterface;

class ContractFileController extends Controller
{
    protected $contractFile;

    public function __construct(
        ContractFileRepoInterface $contractFile
    ) {
        $this->contractFile = $contractFile;
    }

    /**
     * Lấy ds file đính kèm
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        //Lấy data đợt thu
        $data = $this->contractFile->list($request->all());

        return view('contract::contract.inc.contract-file.list', [
            'LIST' => $data['list'],
            'page' => (int) ($request->all()['page'] ?? 1)
        ]);
    }

    /**
     * Show modal thêm file đính kèm
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalCreateAction(Request $request)
    {
        $html = \View::make('contract::contract.pop.contract-file.create')->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm file HĐ
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        return $this->contractFile->store($request->all());
    }

    /**
     * Show modal edit file đính kèm
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showModalEditAction(Request $request)
    {
        //Lấy data view edit
        $data = $this->contractFile->getDataEdit($request->all());

        $html = \View::make('contract::contract.pop.contract-file.edit', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Chỉnh sửa file HĐ
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        return $this->contractFile->update($request->all());
    }

    /**
     * Xoá file HĐ
     *
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request)
    {
        return $this->contractFile->destroy($request->all());
    }
}
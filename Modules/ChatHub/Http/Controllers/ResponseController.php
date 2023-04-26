<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\ChatHub\Http\Requests\Response\StoreRequest;
use Modules\ChatHub\Http\Requests\Response\UpdateRequest;
use Modules\ChatHub\Repositories\Response\ResponseRepositoryInterface;
use Auth;
use Modules\ChatHub\Repositories\ResponseDetail\ResponseDetailRepositoryInterface;


class ResponseController extends Controller
{
    protected $response;
    protected $responseDetail;
    public function __construct(
        ResponseRepositoryInterface $response,
        ResponseDetailRepositoryInterface $responseDetail
    ) {
        $this->response = $response;
        $this->responseDetail = $responseDetail;
    }
    /**
     * Trang index của Config Response
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string
     */
    public function indexAction(Request $request){
        try{
            $filters = request()->all();
            $data=$this->response->getList($filters);
            return view('chathub::response.index',[
                'LIST' => $data
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    /**
     * Dữ liệu bảng của Config Response
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $data=$this->response->getList($filters);
        return view('chathub::response.list',
            [
                'LIST' => $data,
                'page' => $filters['page']
            ]);
    }

    /**
     * Trang tạo Config Response
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function createAction()
    {
        $data = $this->response->getDataCreateAction();
        return view('chathub::response.add', $data);
    }

    /**
     * Thêm Config Response
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAction(StoreRequest $request){

        $arrParams = $request->all();
        $this->response->storeAction($arrParams);
        return redirect()->route('chathub.response');
    }

    /**
     * Trang edit Config Response
     *
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function editAction(Request $request, $id){
        $data = $this->response->getDateEditAction($id);
        return view('chathub::response.edit', $data);
    }

    /**
     * Cập nhật thay đổi của Config Response
     *
     * @param UpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function updateAction(UpdateRequest $request, $id){
        $arrParams = $request->all();
        $this->response->updateAction($arrParams, $id);
        return redirect()->route('chathub.response');;

    }


    /**
     * Chi tiết 1 hoặc tất cả config response detail theo response id
     *
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function detailAction(Request $request, $id){
        $params = $request->all();
        $data = $this->responseDetail->getDetail($params,$id);
        return view('chathub::response.detail', $data);
    }

    /**
     * Danh sách 1 hoặc tất cả config repsonse detail có filter + paging
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function detailListAction(Request $request){
        $params = $request->all();
        $id = $params['response_id'];
        $data = $this->responseDetail->getDetail($params,$id);
        return view('chathub::response.detail_list',$data);
    }
}
<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\ChatHub\Http\Requests\ResponseContent\StoreRequest;
use Modules\ChatHub\Http\Requests\ResponseContent\UpdateRequest;
use Modules\ChatHub\Models\ChatHubBrandTable;
use Modules\ChatHub\Models\ChatHubResponseButtonTable;
use Modules\ChatHub\Models\ChatHubResponseElementButtonTable;
use Modules\ChatHub\Models\ChatHubResponseElementTable;
use Modules\ChatHub\Repositories\ResponseContent\ResponseContentRepositoryInterface;
use Auth;


class ResponseContentController extends Controller
{
    protected $response_content;
    public function __construct(
        ResponseContentRepositoryInterface $response_content
    ) {
        $this->response_content = $response_content;
    }

    /**
     * lấy thông tin response content
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string
     */
    public function indexAction(){
        try{
            $filters = request()->all();
            $response_content=$this->response_content->getList($filters);
            return view('chathub::response_content.index',[
                'LIST' => $response_content
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    /**
     * Lấy thông tin response content có filter + paging
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $response_content=$this->response_content->getList($filters);
        return view('chathub::response_content.list',
            [
                'LIST' => $response_content,
                'page' => $filters['page']
            ]);
    }

    /**
     * Xoá 1 response content
     *
     * @param Request $request
     * @return mixed
     */
    public function remove(Request $request)
    {
        $id = $request->all()['response_content_id'];
        return $this->response_content->remove($id);
    }

    /**
     * View chỉnh sửa 1 response content
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function editAction(Request $request)
    {
        $id = $request->response_content_id;
        $data = $this->response_content->getDataViewEdit($id);
        return view('chathub::response_content.edit',
        [
            'item' => $data['data'],
            'brand' => $data['brand'],
            'element' => $data['element'],
        ]);
    }

    /**
     * Lưu chỉnh sửa 1 response content
     *
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(UpdateRequest $request)
    {
        $id = $request->response_content_id;
        return $this->response_content->saveUpdate($request->all(),$id);
    }

    /**
     * View thêm 1 response content
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function createAction(Request $request)
    {
        $brandTable = new ChatHubBrandTable();
        $brand = $brandTable->getOptionChatHubBrand();
        return view('chathub::response_content.add',
            [
                'brand' => $brand,
            ]);
    }

    /**
     * Thêm 1 response content
     *
     * @param StoreRequest $request
     * @return mixed
     */
    public function insert(StoreRequest $request)
    {
        return $this->response_content->insertData($request->all());
    }

    /**
     * Upload tệp hình ảnh của template
     *
     * @param Request $request
     * @return string
     */
    public function uploadImage(Request $request)
    {
        try {
            if ($request->file('file') != null) {
                $image = $request->file('file');
                $getImage = time().'_'.$image->getClientOriginalName();
                $destinationPath = public_path('static/image/');
                $image->move($destinationPath, $getImage);
                $url ='http://' . $_SERVER['HTTP_HOST'] .'/static/image/'.$getImage;
                return $url;
            }

        }catch (\Exception $e) {
            return $e->getMessage();

        }
    }
    public function popupAddTemplate()
    {
        return view('chathub::config-template.popup');
    }
    public function popupEditTemplate(Request $request)
    {
        $mTemplate = new ChatHubResponseElementTable();
        $id=$request['response_element_id'];
        $template=$mTemplate->getById($id);
        return view('chathub::config-template.popup-edit',[
            'template'=> $template,

        ]);
    }

    public function popupAddButton(Request $request)
    {
        return view('chathub::button.popup',[
            'response_element_id' => $request['response_element_id']
        ]);
    }
    public function popupEditButton(Request $request)
    {
        $mButton = new ChatHubResponseButtonTable();
        $id=$request['response_button_id'];
        $button=$mButton->getById($id);
        // dd($template);
        return view('chathub::button.popup-edit',[
            'button'=> $button,

        ]);
    }
    public function removeButton(Request $request){
        $this->button->removeButton($request['response_button_id']);
    }
}
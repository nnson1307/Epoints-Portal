<?php
/**
 * Created by PhpStorm.
 * User: Như
 * Date: 15/03/2018
 * Time: 1:21 CH
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\CustomerSource\CustomerSourceRepositoryInterface;

class CustomerSourceController extends Controller
{
    /**
     * customer Source Repository Interface
     */
    protected $customerSource;

    public function __construct(CustomerSourceRepositoryInterface $customerSource)
    {
        $this->customerSource = $customerSource;
    }

    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction()
    {
        $customerSourceList = $this->customerSource->list();
        return view('admin::customer-source.index', [
            'LIST' => $customerSourceList,
            'FILTER' => $this->filters()
        ]);
    }

    // function  filter
    protected function filters()
    {
        return [
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách customer source
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'is_actived']);
        $customerSourceList = $this->customerSource->list($filters);
        return view('admin::customer-source.list', [
            'LIST' => $customerSourceList,
            'page'=>$filters['page']
        ]);
    }

    // FUNCTION SUBMIT SUBMIT ADD
    public function submitAddAction(Request $request)
    {
        if ($request->ajax()) {
            $customerSourceName = $request->customer_source_name;
            $testCustomerSourceName = $this->customerSource->testCustomerSourceName($customerSourceName);
            if ($this->customerSource->testIsDeleted($customerSourceName) != null) {
                $this->customerSource->editByName($customerSourceName);
                return response()->json(['success' => 1]);
            } else {
                if ($testCustomerSourceName == null) {
                    $data = [
                        'customer_source_name' => $customerSourceName,
                        'customer_source_type' => $request->customer_source_type,
                        'is_actived' => $request->is_inactive,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'slug'=>str_slug($customerSourceName)
                    ];
                    $this->customerSource->add($data);
                    return response()->json(['success' => 1]);
                } else {
                    return response()->json(['success' => 0]);
                }
            }
        }
    }

// FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->customerSourceId;
            $item = $this->customerSource->getItem($id);
            $jsonString = [
                "customer_source_id" => $item->customer_source_id,
                "customer_source_name" => $item->customer_source_name,
                "customer_source_type" => $item->customer_source_type,
                "is_actived" => $item->is_actived
            ];
            return response()->json($jsonString);
        }
    }

// function submit update customer source
    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $customerSourceName = $request->customer_source_name;
        $testCustomerSourceName = $this->customerSource->testCustomerSourceNameEdit($id, $customerSourceName);
        $testIsDeleted = $this->customerSource->testIsDeleted($customerSourceName);
        if ($request->parameter == 0) {
            if ($testIsDeleted != null) {
                //Tồn tại nguồn khách hàng trong db. is_deleted = 1.
                return response()->json(['status' => 2]);
            } else {
                if ($testCustomerSourceName == null) {
                    $data = [
                        'updated_by' => Auth::id(),
                        'customer_source_name' => $customerSourceName,
                        'customer_source_type' => $request->customer_source_type,
                        'is_actived' => $request->is_actived,
                        'slug'=>str_slug($customerSourceName)
                    ];
                    $this->customerSource->edit($data, $id);
                    return response()->json(['status' => 1]);
                } else {
                    return response()->json(['status' => 0]);
                }
            }
        } else {
            //Kích hoạt lại nguồn khách hàng.
            $this->customerSource->edit(['is_deleted' => 0], $testIsDeleted->customer_source_id);
            return response()->json(['status' => 3]);
        }

    }

// function change status
    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_actived'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->customerSource->edit($data, $params['id']);
        return response()->json([
            'status' => 0,
            'messages' => __('Trạng thái đã được cập nhật')
        ]);
    }

// FUNCTION DELETE ITEM
    public function removeAction($id)
    {
        $this->customerSource->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }
}
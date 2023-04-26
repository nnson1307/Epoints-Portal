<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ZNS\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Modules\ZNS\Repositories\CustomerCare\CustomerCareRepositoryInterface;


class CustomerCareController extends Controller
{
    protected $customerCare;

    public function __construct(CustomerCareRepositoryInterface $customerCare)
    {
        $this->customerCare = $customerCare;
    }

    public function list(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'zalo_customer_tag_id', 'created_at']);
        return view('zns::customer_care.index', $this->customerCare->list($filters));
    }

    public function editCustomerCare(Request $request)
    {
        $params = $request->all();
        return $this->customerCare->editCustomerCare($params);
    }

    public function editCustomerCareAction(Request $request)
    {
        $params = $request->all();
        $rule = [
            'full_name' => 'required|max:191',
            'phone_number' => "required|numeric|digits:10",
            'province_id' => 'required',
            'district_id' => 'required',
        ];

        $mess = [
            'full_name.required' => __('Họ tên không được để trống'),
            'full_name.191' => __('Họ tên không được quá 191 ký tự'),
            "phone_number.required" => __('Vui lòng nhập số điện thoại'),
            "phone_number.numeric" => __('Vui lòng kiểm tra lại định dạng số điện thoại'),
            "phone_number.digits" => __('Vui lòng kiểm tra lại định dạng số điện thoại'),
            'province_id.required' => __('Vui lòng chọn thành phố'),
            'district_id.required' => __('Vui lòng chọn quận huyện'),
        ];
        $validated = $request->validate($rule, $mess);
        if (isset($validated->message)) {
            return $validated->message;
        }
        return $this->customerCare->editCustomerCareAction($params);
    }

    public function view()
    {
        return view('zns::customer_care.view', []);
    }

    public function listTag(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'zalo_customer_tag_id', 'created_at']);
        return view('zns::customer_tag.index', $this->customerCare->listTag($filters));
    }

    public function addTagAction(Request $request)
    {
        $params = $request->all();
        $rule = [
            'tag_name' => 'required',
        ];

        $mess = [
            'tag_name.required' => __('Tên thẻ không được để trống'),
        ];
        $validated = $request->validate($rule, $mess);
        if (isset($validated->message)) {
            return $validated->message;
        }
        return $this->customerCare->addTagAction($params);
    }

    public function removeAction(Request $request)
    {
        $params = $request->all();
        return $this->customerCare->removeAction($params);
    }

    public function removeTagAction(Request $request)
    {
        $params = $request->all();
        return $this->customerCare->removeTagAction($params);
    }

    public function getDistrict(Request $request)
    {
        $params = $request->province_id;
        return $this->customerCare->getDistrict($params);
    }

    public function editCustomerCareTagAction(Request $request)
    {
        $params = $request->all();
        if($request->color_code){
            $params['color_code'] = $request['color_code'];
        }
        return $this->customerCare->editTagAction($params,$request->zalo_customer_tag_id);
    }

    public function synchronized()
    {
        return $this->customerCare->synchronized();
    }

}
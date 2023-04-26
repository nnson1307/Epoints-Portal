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
use Modules\ZNS\Repositories\Template\TemplateRepositoryInterface;


class TemplateController extends Controller
{
    protected $template;

    public function __construct(TemplateRepositoryInterface $template)
    {
        $this->template = $template;
    }

    public function list(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'status', 'created_at']);
        return view('zns::template.index', $this->template->list($filters));
    }

    public function add()
    {
        return view('zns::template.add', []);
    }

    public function edit($id)
    {
        return view('zns::template.edit', []);
    }

    public function view()
    {
        return view('zns::template.view', []);
    }

    public function getTemplate(Request $request)
    {
        return $this->template->getTemplate($request->type, $request->zns_template_id);
    }

    public function getTemplateFollower(Request $request)
    {
        $params = $request->all();
        return $this->template->getTemplateFollower($request->type, $request->zns_template_id);
    }

    public function synchronized()
    {
        return $this->template->synchronized();
    }

    public function listTemplateFollower(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'type_template_follower', 'created_at']);
        return view('zns::template.follower.index', $this->template->listFollower($filters));
    }

    public function addViewFollower(Request $request)
    {
        $params = $request->all();
        return view('zns::template.follower.add', $this->template->addViewFollower($params));
    }

    public function editViewFollower($id,Request $request)
    {
        $params = $request->all();
        return view('zns::template.follower.edit', $this->template->editViewFollower($id,$params));
    }

    public function previewTemplateFollower($id)
    {
        return view('zns::template.follower.preview', [
            'item' => $this->template->getItem($id)
        ]);
    }

    public function viewFollower($id,Request $request)
    {
        $params = $request->all();
        return view('zns::template.follower.view', $this->template->editViewFollower($id,$params));
    }

    public function addFollower(Request $request)
    {
        $params = $request->all();
        $validate = $this->validateAddTemplateFollower($request->type_template_follower, $params);
        $validated = $request->validate($validate['rule'], $validate['mess']);
        if (isset($validated->message)) {
            return $validated->message;
        }
        return $this->template->addFollower($params);
    }

    public function editSubmitFollower(Request $request)
    {
        $params = $request->all();
        $validate = $this->validateAddTemplateFollower($request->type_template_follower, $params);
        $validated = $request->validate($validate['rule'], $validate['mess']);
        if (isset($validated->message)) {
            return $validated->message;
        }
        return $this->template->editFollower($params);
    }

    public function cloneActionFollower(Request $request)
    {
        $rule = [
            // 'oa' => 'required',
            'zns_template_id' => 'required',
            'template_name' => 'required|max:191',
        ];

        $mess = [
            // 'oa.required' => __('Vui lòng chọn OA'),
            'zns_template_id.required' => __('Vui lòng nhập tên template'),
            'name.required' => __('Vui lòng nhập tên template'),
            'name.max' => __('Tên template không quá 191 ký tự'),
        ];
        $validated = $request->validate($rule, $mess);
        if (isset($validated->message)) {
            return $validated->message;
        }
        $params = $request->all();
        return $this->template->cloneActionFollower($params);
    }

    public function addButtonFollower(Request $request)
    {
        $params = $request->all();
        return $this->template->addButtonFollower($params);
    }

    public function validateAddTemplateFollower($type_template_follower, $params)
    {
        if ($type_template_follower == 0) {
            $rule = [
                'template_name' => 'required',
                'type_template_follower' => 'required',
                'preview' => 'required|max:2000',
            ];

            $mess = [
                'template_name.required' => __('Vui lòng nhập tên template'),
                'type_template_follower.required' => __('Vui lòng chọn loại template'),
                'preview.required' => __('Vui lòng nhập nội dung'),
                'preview.max' => __('Nội dung không quá 2000 ký tự'),
            ];
        }
        if ($type_template_follower == 1) {
            $rule = [
                'template_name' => 'required',
                'image' => 'required',
                'type_template_follower' => 'required',
                'image_title' => 'required|max:2000'
            ];

            $mess = [
                'template_name.required' => __('Vui lòng nhập tên template'),
                'type_template_follower.required' => __('Vui lòng chọn loại template'),
                'image.required' => __('Hình ảnh không được trống'),
                'image_title.required' => __('Vui lòng nhập tiêu đề ảnh'),
                'image_title.max' => __('Tiêu đề ảnh không quá 2000 ký tự')
            ];
        }
        if ($type_template_follower == 2) {
            $rule = [
                'template_name' => 'required',
                'type_template_follower' => 'required',
                'preview' => 'required|max:2000',
                'image' => 'required',
                'link_image' => 'required',
                'image_title' => 'required|max:2000'
            ];

            $mess = [
                'template_name.required' => __('Vui lòng nhập tên template'),
                'type_template_follower.required' => __('Vui lòng chọn loại template'),
                'preview.required' => __('Vui lòng nhập nội dung'),
                'preview.max' => __('Nội dung không quá 2000 ký tự'),
                'image.required' => __('Hình ảnh không được trống'),
                'link_image.required' => __('Url không được trống'),
                'image_title.required' => __('Vui lòng nhập tiêu đề ảnh'),
                'image_title.max' => __('Tiêu đề ảnh không quá 2000 ký tự')
            ];
            if (isset($params['title']) && count($params['title'])) {
                $rule["title.*"] = "required|max:100";
                $mess["title.*.required"] = __("Vui lòng nhập tiêu đề");
                $mess["title.*.max"] = __("Tiêu đề không được quá 100 ký tự");
            }
            if (isset($params['phone_number']) && count($params['phone_number'])) {
                $rule["phone_number.*"] = "required|numeric|digits:10";
                $mess["phone_number.*.required"] = __('Vui lòng nhập số điện thoại');
                $mess["phone_number.*.numeric"] = __('Vui lòng kiểm tra lại định dạng số điện thoại');
                $mess["phone_number.*.digits"] = __('Vui lòng kiểm tra lại định dạng số điện thoại');
            }
            if (isset($params['link']) && count($params['link'])) {
                $rule["link.*"] = "required";
                $mess["link.*.required"] = __("Vui lòng nhập đường dẫn liên kết");
            }
            if (isset($params['icon']) && count($params['icon'])) {
                $rule["icon.*"] = "required";
                $mess["icon.*.required"] = __("Vui lòng chọn icon");
            }
            if (isset($params['content']) && count($params['content'])) {
                $rule["content.*"] = "required";
                $mess["content.*.required"] = __("Vui lòng nhập nội dung tin nhắn");
            }
        }
        if ($type_template_follower == 3) {
            $rule = [
                'template_name' => 'required',
                'file' => 'required',
                'type_template_follower' => 'required',
//                'file_title' => 'required|max:2000',
            ];

            $mess = [
                'template_name.required' => __('Vui lòng nhập tên template'),
                'type_template_follower.required' => __('Vui lòng chọn loại template'),
                'file.required' => __('Vui lòng chọn file'),
//                'file_title.required' => __('Vui lòng nhập tiêu đề file'),
//                'file_title.max' => __('Tiêu đề file không quá 2000 ký tự'),
            ];
        }
        if ($type_template_follower == 4) {
            $rule = [
                'template_name' => 'required',
                'title_show' => 'required',
                'sub_title' => 'required',
            ];

            $mess = [
                'template_name.required' => __('Vui lòng nhập tên template'),
                'title_show.required' => __('Vui lòng nhập tiêu đề hiển thị'),
                'sub_title.required' => __('Vui lòng nhập tiêu đề phụ'),
            ];
        }
        return [
            "rule" => $rule,
            "mess" => $mess
        ];
    }

}
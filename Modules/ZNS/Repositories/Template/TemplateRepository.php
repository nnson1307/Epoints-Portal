<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ZNS\Repositories\Template;

use Modules\ZNS\Http\Api\ZnsApi;
use Modules\ZNS\Models\TemplateTable;
use Modules\ZNS\Models\ListParramsTable;
use Modules\ZNS\Models\TemplateButtonTable;
use GuzzleHttp\Client;
use Carbon\Carbon;


class TemplateRepository implements TemplateRepositoryInterface
{
    /**
     * @var TemplateTable
     */
    protected $template;
    protected $timestamps = true;
    protected $statusList;


    public function __construct(TemplateTable $template)
    {
        $this->template = $template;
        $this->statusList = [
            1 => __("Đang hoạt động"),
            2 => __("Đang duyệt"),
            3 => __("Bị từ chối"),
            4 => __("Bị vô hiệu hóa"),
            5 => __("Bị xóa"),
        ];
    }

    /**
     *get list template
     */
    public function list(array $filters = [])
    {
        $filters['type'] = "zns";
        return [
            'list' => $this->template->getList($filters),
            'params' => $filters,
            'status_template' => $this->status_template(),
        ];
    }

    /**
     * @param array $filters
     * @return array
     */
    public function listFollower(array $filters = [])
    {
        $filters['type'] = 'follower';
        return [
            'list' => $this->template->getList($filters),
            'params' => $filters,
            'list_type_template_follower' => $this->listTypeTemplateFollower(),
        ];
    }

    /**
     * @param $params
     * @return array
     */
    public function addViewFollower($params = [])
    {
        return [
            'params' => $params,
            'param_list' => $this->listParamsUsing(),
            'list_type_template_follower' => $this->listTypeTemplateFollower(),
            'list_type_button' => $this->listTypeButton(),
        ];
    }

    /**
     * @param $id
     * @return id
     */
    public function editViewFollower($id, $params)
    {
        $item = $this->template->getItem($id);
        $params['type_template_follower'] = isset($params['type_template_follower']) ? $params['type_template_follower'] : $item->type_template_follower;
        $data = [
            'item' => $item,
            'param_list' => $this->listParamsUsing(),
            'list_type_template_follower' => $this->listTypeTemplateFollower(),
            'params' => $params,
            'list_type_button' => $this->listTypeButton(),
        ];
        return $data;
    }

    /**
     * @param $params
     * @return array
     */
    public function addFollower($params = [])
    {
        $params['created_at'] = Carbon::now()->format("Y-m-d H:i:s");
        $params['type'] = "follower";
        $zns_template_id = $this->insertDataTypeFollower($params);
        if ($zns_template_id) {
            if ($params['type_template_follower'] == 2) {
                $params['zns_template_id'] = $zns_template_id;
                $this->insertButtonType($params);
            }
            return [
                'status' => 1,
                'zns_template_id' => $zns_template_id,
                'message' => __('Thêm thành công')
            ];
        } else {
            return [
                'status' => 0,
                'message' => __('Thêm không thành công')
            ];
        }
    }

    /**
     * @param $params
     * @return array
     */
    public function editFollower($params = [])
    {
        $params['updated_at'] = Carbon::now()->format("Y-m-d H:i:s");
        $params['type'] = "follower";
        $zns_template_id = $this->insertDataTypeFollower($params, 0);
        if ($zns_template_id) {
//            if($params['type_template_follower'] == 2){
//
//            }
//            $params['zns_template_id'] = $zns_template_id;
            $this->insertButtonType($params);
            return [
                'status' => 1,
                'message' => __('Chỉnh sửa thành công')
            ];
        } else {
            return [
                'status' => 0,
                'message' => __('Chỉnh sửa không thành công')
            ];
        }
    }

    public function cloneActionFollower($params = [])
    {
        if ($params['zns_template_id']) {
            $zns_template_id = $this->template->duplicateRowWithNewId($params);
            $mTemplateButtonTable = app()->get(TemplateButtonTable::class);
            $list_button = $mTemplateButtonTable->getItemByZnsTemplateId($params['zns_template_id']);
            if ($list_button) {
                foreach ($list_button as $value) {
                    unset($value['zns_template_button_id']);
                    $value['zns_template_id'] = $zns_template_id;
                    $mTemplateButtonTable->add($value);
                }
            }
        }
    }

    /**
     * @param $params
     * @return array
     */
    public function insertDataTypeFollower($params = [], $is_insert = 1)
    {
        $dataInsert = [
            "type_template_follower" => $params['type_template_follower'],
            "template_name" => $params['template_name'],
            "type" => $params['type'],
        ];
        if ($params['type_template_follower'] == 0) {
            $dataInsert["preview"] = $params['preview'];
        } elseif ($params['type_template_follower'] == 1) {
            $dataInsert["image"] = $params['image'];
            $dataInsert["image_title"] = $params['image_title'];
        } elseif ($params['type_template_follower'] == 2) {
            $dataInsert["image"] = $params['image'];
            $dataInsert["image_title"] = $params['image_title'];
            $dataInsert["preview"] = $params['preview'];
            $dataInsert["link_image"] = $params['link_image'];
        } elseif ($params['type_template_follower'] == 3) {
            $dataInsert["file"] = $params['file'];
//            $dataInsert["file_title"] = $params['file_title'];
        } elseif ($params['type_template_follower'] == 4) {
            $dataInsert["image"] = $params['image'];
            $dataInsert["title_show"] = $params['title_show'];
            $dataInsert["sub_title"] = $params['sub_title'];
        }
        if (isset($dataInsert['image'])) {
            $mZnsApi = app()->get(ZnsApi::class);
            $dataInsert["attachment_id"] = $mZnsApi->getAttachment($dataInsert['image']);
        }
        if (isset($dataInsert['file'])) {
            $mZnsApi = app()->get(ZnsApi::class);
            $dataInsert["token_upload_file"] = $mZnsApi->getTokenuploadFile($dataInsert['file']);
        }
        if ($is_insert == 1) {
            return $this->template->add($dataInsert);
        } else {
            if ($this->template->edit($dataInsert, $params['zns_template_id'])) {
                return $params['zns_template_id'];
            }
            return 0;
        }
    }

    public function insertButtonType($params = [])
    {
        $mTemplateButtonTable = app()->get(TemplateButtonTable::class);
        $mTemplateButtonTable->removeByZnsTemplateId($params['zns_template_id']);
        if (isset($params['type_button']) && count($params['type_button'])) {
            foreach ($params['type_button'] as $key => $value) {
                $dataInsert = [];
                if ($value == 1) {
                    $dataInsert = [
                        "type_button" => $value,
                        "title" => $params['title'][$key],
                        "link" => $params['link'][$key],
                        "icon" => $params['icon'][$key],
                        "zns_template_id" => $params["zns_template_id"],
                    ];
                } elseif ($value == 2) {
                    $dataInsert = [
                        "type_button" => $value,
                        "title" => $params['title'][$key],
                        "phone_number" => $params['phone_number'][$key],
                        "icon" => $params['icon'][$key],
                        "zns_template_id" => $params["zns_template_id"],
                    ];
                } elseif ($value == 3) {
                    $dataInsert = [
                        "type_button" => $value,
                        "title" => $params['title'][$key],
                        "content" => $params['content'][$key],
                        "phone_number" => $params['phone_number'][$key],
                        "icon" => $params['icon'][$key],
                        "zns_template_id" => $params["zns_template_id"],
                    ];
                }
                $mTemplateButtonTable->add($dataInsert);
            }
        }
    }

    /**
     * @param $params
     * @return array
     */
    public function addButtonFollower($params = [])
    {
        $params['list_type_button'] = $this->listTypeButton();
        if (isset($params['type_button']) && $params['type_button']) {
            if ($params['stt'] > 4) {
                return [
                    'status' => 0,
                    'html' => '',
                    'message' => __('Số lượng nút tối đa là 4 phần tử')
                ];
            }
            return [
                'status' => 1,
                'html' => view('zns::template.follower.include.button_item', $params)->render(),
                'message' => __('Thêm thành công')
            ];
        } else {
            return [
                'status' => 0,
                'html' => '',
                'message' => __('Hành động thất bại')
            ];
        }
    }

    /**
     * delete template
     */
    public function remove($id)
    {
        $this->template->remove($id);
    }

    /**
     * add template
     */
    public function add(array $data)
    {

        return $this->template->add($data);
    }

    /*
     * edit template
     */
    public function edit(array $data, $id)
    {
        return $this->template->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->template->getItem($id);
    }

    public function status_template()
    {
        return [
            1 => __('Nháp'),
            2 => __('Đang duyệt'),
            3 => __('Bị từ chối'),
            4 => __('Đã duyệt'),
            5 => __('Bị khóa')
        ];
    }

    /*
     *  có 2 loại là danh sách và chi tiết type [list,item]
     */
    public function getTemplate($type = "list", $id = '')
    {
        $mListParramsTable = app()->get(ListParramsTable::class);
        if ($type == 'item') {
            return [
                'status' => 1,
                'detail' => $this->template->getItem($id),
                'html_params' => view('zns::campaign.render.param_render', [
                    'params' => $mListParramsTable->getItemByTemplateId($id),
                ])->render()
            ];
        }
        if ($type == 'user') {
            return [
                'status' => 1,
                'detail' => $this->template->getItem($id)->template_tag
            ];
        }
        $is_trigger_config = 0;
        if ($id) {
            $is_trigger_config = 1;
        }
        return [
            'status' => 1,
            'option' => getOption($this->template->getName($is_trigger_config))
        ];
    }

    /*
     *  có 2 loại là danh sách và chi tiết type [list,item]
     */
    public function getTemplateFollower($type = "list", $id)
    {
        if ($type == 'item') {
            return [
                'status' => 1,
                'detail' => $this->template->getItem($id),
//                'html_params' => view('zns::campaign.render.param_render', [
//                    'params' => $mListParramsTable->getItemByTemplateFollowerId($id),
//                ])->render()
            ];
        }
        return [
            'status' => 1,
            'option' => getOption($this->template->getNameFollower())
        ];
    }

    public function synchronized()
    {
        $mListParramsTable = new ListParramsTable();

        $mZnsApi = app()->get(ZnsApi::class);
        //Call api lấy template zns
        $list_template = $mZnsApi->getTemplate();

        $data = [];
        $list_params = [];
        if ($list_template['ErrorCode'] == 0 && $list_template['Data']) {
            foreach ($list_template['Data'] as $template_detail) {
                //Call api lấy chi tiết template
                $info_template = $mZnsApi->getTemplateDetail([
                    'template_id' => $template_detail['template_id']
                ]);

                if ($info_template['ErrorCode'] == 0 && $info_template['Data']) {
                    $data = [
                        'template_id' => $template_detail['template_id'],
                        'type' => "zns",
                        'template_name' => $template_detail['template_name'],
                        'status' => $this->getStatus($template_detail['status']),
                        'preview' => isset($info_template['Data']['preview_url']) ? $info_template['Data']['preview_url'] : '',
                        'price' => isset($info_template['Data']['price']) ? $info_template['Data']['price'] : 0,
                        'template_tag' => isset($info_template['Data']['template_tag']) ? $info_template['Data']['template_tag'] : '',
                        'created_at' => Carbon::createFromFormat('d/m/Y H:i:s', $template_detail['created_time'])->format('Y-m-d H:i:s')
                    ];

                    $zns_template_id = $this->template->insertOrUpdateMultipleRows($data);
                    if (isset($info_template['Data']['list_params']) && $info_template['Data']['list_params']) {
                        $mListParramsTable->removeByZnsTemplateId($zns_template_id);
                        foreach ($info_template['Data']['list_params'] as $param) {
                            $list_params = [
                                'zns_template_id' => $zns_template_id,
                                'value' => isset($param['name']) ? $param['name'] : '',
                                'required' => '1',
                                'type' => isset($param['type']) ? $param['type'] : '',
                                'max_length' => isset($param['max_length']) ? $param['max_length'] : 0,
                                'min_length' => isset($param['min_length']) ? $param['min_length'] : 0,
                                'accept_null' => ($param['accept_null'] == true) ? 'true' : 'false',
                            ];
                            $list_params_id = $mListParramsTable->add($list_params);
                        }
                    }
                } else {
                    $data = [
                        'template_id' => $template_detail['template_id'],
                        'type' => "zns",
                        'template_name' => $template_detail['template_name'],
                        'status' => $this->getStatus($template_detail['status']),
                        'preview' => isset($info_template['Data']['preview_url']) ? $info_template['Data']['preview_url'] : '',
                        'price' => isset($info_template['Data']['price']) ? $info_template['Data']['price'] : 0,
                        'template_tag' => isset($info_template['Data']['template_tag']) ? $info_template['Data']['template_tag'] : '',
                        'created_at' => Carbon::createFromFormat('d/m/Y H:i:s', $template_detail['created_time'])->format('Y-m-d H:i:s'),
                    ];
                    $zns_template_id = $this->template->insertOrUpdateMultipleRows($data);
                }
            }
        }
//        if($data){
//            $return['template'] =  $this->template->insertOrUpdateMultipleRows($data);
//        }
//        if($list_params){
//            $return['params'] = $mListParramsTable->insertOrUpdateMultipleRows($list_params);
//        }
        return [
            'status' => 1,
            'message' => __('Đồng bộ thành công')
        ];
    }

    private function getStatus($status)
    {
        /*
           status = 1: Lấy các template có trạng thái Enable.
           status = 2: Lấy các template có trạng thái Pending review.
           status = 3: Lấy các template có trạng thái Reject.
           status = 4: Lấy các template có trạng thái Disable.
           status = 5: Lấy các template có trạng thái Delete.`
        */
        $status_id = 0;
        if ($status == "ENABLE") {
            return 1;
        } elseif ($status == "PENDING_REVIEW") {
            return 2;
        } elseif ($status == "REJECT") {
            return 3;
        } elseif ($status == "DISABLE") {
            return 4;
        } elseif ($status == "DELETE") {
            return 5;
        }
        return 0;
    }

    private function listTypeTemplateFollower()
    {
        return [
            0 => __('Gửi thông báo văn bản'),
            1 => __('Gửi thông báo theo mẫu đính kèm ảnh'),
            2 => __('Gửi thông báo theo mẫu đính kèm danh sách'),
            3 => __('Gửi thông báo theo mẫu đính kèm file'),
            4 => __('Gửi thông báo theo mẫu yêu cầu thông tin người dùng'),
        ];
    }

    private function listParamsUsing()
    {
        return [
            "customer_full_name" => __("Họ và tên khách hàng"),
            "gender" => __("Giới tính"),
            "date_time" => __("Ngày sinh"),
        ];
    }

    private function listTypeButton()
    {
        return [
            1 => __('Đến trang web khác'),
            2 => __("Gọi điện"),
            3 => __("Gửi tin nhắn"),
        ];
    }

}
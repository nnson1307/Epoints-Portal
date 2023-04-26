<?php
/**
 * Created by PhpStorm
 * User: Huniel
 * Date: 4/26/2022
 * Time: 5:37 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Repositories\Collection\CollectionRepoIf;
use Illuminate\Routing\Controller as BaseController;

class CollectionController extends BaseController
{
    protected $collection;

    public function __construct(
        CollectionRepoIf $collection
    )
    {
        $this->collection = $collection;
    }

    // collection

    /**
     * Danh sách filter
     *
     * @return array
     */
    public function filters($param = [])
    {
        $result = [];

        if (in_array('age', $param)) {
            $dataAge[''] = 'Chọn tuổi công dân';
            for ($i = 18; $i <= 27; $i++) {
                $dataAge[$i] = $i;
            }
            $result['age'] = [
                'data' => $dataAge,
            ];
        }

        if (in_array('year', $param)) {
            $dataAge[''] = 'Chọn năm';
            for ($i = 2022; $i >= 1975; $i--) {
                $dataAge[$i] = $i;
            }
            $result['year'] = [
                'data' => $dataAge,
            ];
        }


        if (in_array('gender', $param)) {
            //$genderOptions[''] = __('Chọn giới tính');
            $genderOptions['male'] = __('Nam');
            $genderOptions['female'] = __('Nữ');
            $genderOptions['others'] = __('Khác');
            $result['gender'] = [
                'data' => $genderOptions
            ];
        }

        if (in_array('is_active', $param)) {
            $result['is_active'] = [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    '1' => __('Đang hoạt động'),
                    '0' => __('Tạm ngừng'),
                ]
            ];
        }

        if (in_array('is_verified', $param)) {
            $result['is_verified'] = [
                'data' => [
                    '' => __('Chọn trạng thái phúc tra'),
                    '1' => __('Đã phúc tra'),
                    '0' => __('Chưa phúc tra'),
                ]
            ];
        }



        return $result;
    }


    /**
     * Page danh sách
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function list(Request $request)
    {
        $field = [
        ];
        $filters = $this->filters($field);

        $param = $request->only("current_page", "perpage", "search");

        $param['is_deleted'] = 0;

        return view('admin::collection.list', [
            'list' => $this->collection->getPaginate($param),
            'param' => $param,
            'filters' => $filters
        ]);

    }

    /**
     * ajax search List
     *
     * @return array;
     */
    public function ajaxList(Request $request)
    {
        $field = [
        ];
        $filters = $this->filters($field);

        $param = $request->only("current_page", "perpage", "search");
        $data['param'] = $param;

        $param['is_deleted'] = 0;


        $data['list'] = $this->collection->getPaginate($param);

        return [
            "status" => "success",
            "action" => ["html", ""],
            "html" => [
                '.collection-table' => view("admin::collection.table", $data)->render(),
            ],
        ];

    }

    /**
     * ajax modal add
     *
     * @return array;
     */
    public function ajaxAddModal(Request $request)
    {
        $param = $request->only([
        ]);

        $data['filters'] = $this->filters([
        ]);


        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".collection-add-modal" => view("admin::collection.add-modal", $data)->render(),
            ],
            "modal" => [
                ".collection-add-modal" => "show",
            ],
        ];
    }

    /**
     * ajax action add
     *
     * @return array;
     */
    public function ajaxAdd(Request $request)
    {
        $param = $request->only([
            "image_web",
            "image_app",
            "link",
            "source",
        ]);
        $param['is_active'] = 1;
        $param['is_deleted'] = 0;
        $param['created_at'] = Carbon::now()->toDateTimeString();
        $param['updated_at'] = Carbon::now()->toDateTimeString();

        $result = $this->collection->actionAdd($param);

        if ($result['status'] == 'error') {
            return [
                "status" => "error",
                "action" => ["swal"],
                "swal" => [
                    "text" => $result['error'] ?? "",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
            ];
        }
        if ($result['status'] == 'success') {
            return [
                "status" => "success",
                "action" => ["swal", "modal", "submitForm"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".collection-add-modal" => "hide",
                ],
                "submitForm" => '.ajax-collection-list-form',
                "action2" => $request->action2,
            ];
        }
        return [
            "status" => "error",
            "action" => ["swal"],
            "swal" => [
                "text" => __("Lỗi không xác định"),
                "title" => __('Lỗi'),
                "type" => 'error',
            ],
        ];

    }

    /**
     * ajax mở modal edit
     *
     * @return array;
     */
    public function ajaxEditModal(Request $request)
    {
        $param = $request->only([
            "checkin_collection_id",
        ]);

        $data['filters'] = $this->filters([
        ]);

        $data['item'] = $this->collection->getItem(["checkin_collection_id" => $param["checkin_collection_id"]]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".collection-edit-modal" => view("admin::collection.edit-modal", $data)->render(),
            ],
            "modal" => [
                ".collection-edit-modal" => "show",
                ".collection-detail-modal" => "hide",
            ],
        ];

    }

    /**
     * ajax action edit
     *
     * @return array;
     */
    public function ajaxEdit(Request $request)
    {
        $param = $request->only([
            "checkin_collection_id",
            "image_web",
            "image_app",
            "link",
            "source",
        ]);
        //if(($param['is_active']??'')=='on') $param['is_active'] = 1;

        $result = $this->collection->actionEdit($param);

        if ($result['status'] == 'error') {
            return [
                "status" => "error",
                "action" => ["swal"],
                "swal" => [
                    "text" => $result['error'] ?? "",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
            ];
        }

        if ($result['status'] == 'success') {
            return [
                "status" => "success",
                "action" => ["swal", "modal", "submitForm"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".collection-edit-modal" => "hide",
                    ".collection-delete-modal" => "hide",
                ],
                "submitForm" => ".ajax-collection-list-form",
            ];
        }
        return [
            "status" => "error",
            "action" => ["swal"],
            "swal" => [
                "text" => __("Lỗi không xác định"),
                "title" => __('Lỗi'),
                "type" => 'error',
            ],
        ];
    }

    /**
     * ajax xóa công dân
     *
     * @return array;
     */
    public function ajaxDelete(Request $request)
    {
        $param = $request->only([
            "checkin_collection_id",
        ]);

        $result = $this->collection->actionDelete($param);

        if ($result['status'] == 'error') {
            return [
                "status" => "error",
                "action" => ["swal", "modal", ""],
                "swal" => [
                    "text" => $result['error'] ?? "",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
                "modal" => [
                    ".collection-delete-modal" => "hide",
                ],
            ];
        }
        if ($result['status'] == 'success') {
            return [
                "status" => "success",
                "action" => ["swal", "modal", "submitForm"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".collection-delete-modal" => "hide",
                ],
                "submitForm" => '.ajax-collection-list-form',
            ];
        }
        return [
            "status" => "error",
            "action" => ["swal"],
            "swal" => [
                "text" => __("Lỗi không xác định"),
                "title" => __('Lỗi'),
                "type" => 'error',
            ],
        ];

    }


    /**
     * ajax mở modal xem chi tiết
     *
     * @return array;
     */
    public function ajaxDetailModal(Request $request)
    {
        $param = $request->only([
            "checkin_collection_id",
        ]);

        $data['filters'] = $this->filters([
            "search",
        ]);
        $data['item'] = $this->collection->getItem(["checkin_collection_id" => $param["checkin_collection_id"]]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal", "remove"],
            "appendOrReplace" => [
                ".collection-detail-modal" => view("admin::collection.detail-modal", $data)->render(),
            ],
            "modal" => [
                ".collection-detail-modal" => "show",
            ],
            "remove" => [
                0 => ".collection-verify-add-modal"
            ],
        ];
    }


}
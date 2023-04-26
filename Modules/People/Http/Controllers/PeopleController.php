<?php

/**
 * Created by PhpStorm
 * User: Huniel
 * Date: 4/26/2022
 * Time: 5:37 PM
 */

namespace Modules\People\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Modules\People\Http\Requests\PeopleObjectGroup\Edit as PeopleObjectGroupEditRequest;
use Modules\People\Http\Requests\PeopleObjectGroup\Add as PeopleObjectGroupAddRequest;
use Modules\People\Http\Requests\PeopleObjectGroup\Delete as PeopleObjectGroupDeleteRequest;
use Modules\People\Http\Requests\PeopleObject\Edit as PeopleObjectEditRequest;
use Modules\People\Http\Requests\PeopleObject\Add as PeopleObjectAddRequest;
use Modules\People\Http\Requests\PeopleObject\Delete as PeopleObjectDeleteRequest;
use Modules\People\Http\Requests\People\Edit as PeopleEditRequest;
use Modules\People\Http\Requests\People\Add as PeopleAddRequest;
use Modules\People\Http\Requests\People\Delete as PeopleDeleteRequest;
use Modules\People\Repositories\People\PeopleRepoIf;
use Illuminate\Routing\Controller as BaseController;
use Modules\People\Models\PeopleTable;
use Modules\People\Repositories\PeopleVerify\PeopleVerifyRepoIf;

class PeopleController extends BaseController
{
    protected $people;
    protected $peopleVerify;

    public function __construct(
        PeopleRepoIf $people,
        PeopleVerifyRepoIf $peopleVerify
    ) {
        $this->people = $people;
        $this->peopleVerify = $peopleVerify;
    }

    public function test(Request $request)
    {
        $this->people->test();
    }

    /**
     * Danh sách nhóm đối tượng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function objectGroupList(Request $request)
    {
        $param = $request->only([
            "current_page",
            "perpage",
            "name",
            "is_active",
        ]);
        $param['is_deleted'] = 0;

        return view('People::object-group.list', [
            'list' => $this->people->objectGroupPaginate($param),
            'param' => $param,
            'filters' => [
                'is_active' => [
                    'data' => [
                        '' => __('Chọn trạng thái'),
                        '1' => __('Đang hoạt động'),
                        '0' => __('Tạm ngừng'),
                    ]
                ],
            ],
        ]);
    }

    /**
     * Danh sách nhóm đối tượng nhưng ajax
     *
     * @return array;
     */
    public function ajaxObjectGroupList(Request $request)
    {
        $param = $request->only([
            "current_page",
            "perpage",
            "name",
            "is_active",
            "show_area",
        ]);
        $param['is_deleted'] = 0;

        $data = [];
        $data['list'] = $this->people->objectGroupPaginate($param);


        return [
            "status" => "success",
            "action" => ["html", ""],
            "html" => [
                $param['show_area'] ?? '.people-object-group-table' => view("People::object-group.table", $data)->render(),
            ],
        ];
    }

    /**
     * ajax mở modal thêm nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectGroupAddModal(Request $request)
    {
        $param = $request->only([]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".people-object-add-modal" => view("People::object-group.add-modal")->render(),
            ],
            "modal" => [
                ".people-object-add-modal" => "show",
            ],
        ];
    }

    /**
     * ajax thêm nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectGroupAdd(PeopleObjectGroupAddRequest $request)
    {
        $param = $request->only([
            "name",
            "is_active",
        ]);
        $param['is_active'] = 1;

        if ($request->input('is_skip') == 'on') $param['is_skip'] = 1;

        $result = $this->people->objectGroupAdd($param);

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
                "action" => ["swal", "modal"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".people-object-add-modal" => "hide",
                ],
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
     * ajax mở modal sửa nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectGroupEditModal(Request $request)
    {
        $param = $request->only([
            "people_object_group_id",
        ]);
        $data = $this->people->objectGroup(["people_object_group_id" => $param["people_object_group_id"]]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".people-object-edit-modal" => view("People::object-group.edit-modal", ['item' => $data])->render(),
            ],
            "modal" => [
                ".people-object-edit-modal" => "show",
            ],
        ];
    }

    /**
     * ajax sửa nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectGroupEdit(PeopleObjectGroupEditRequest $request)
    {
        $param = $request->only([
            "people_object_group_id",
            "name",
            "is_active",
            "is_deleted",
            "is_skip",
        ]);
        //if(($param['is_active']??'')=='on') $param['is_active'] = 1;
        $param['is_active'] = ($param['is_active'] ?? '') == 'on' ? 1 : 0;
        $param['is_skip'] = ($param['is_skip'] ?? '') == 'on' ? 1 : 0;

        $result = $this->people->objectGroupEdit($param);

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
                "action" => ["swal", "modal"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".people-object-edit-modal" => "hide",
                    ".people-object-delete-modal" => "hide",
                ],
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

    public function ajaxObjectGroupChangeStatus(Request $request)
    {
        $param = $request->only([
            "people_object_group_id",
            "is_active",
        ]);
        $param['is_active'] = ($param['is_active'] ?? '') == 'on' ? 1 : 0;

        $result = $this->people->objectGroupEdit($param);

        if ($result['status'] == 'error') {
            return [
                "status" => "error",
                "action" => ["swal"],
                "swal" => [
                    "text" => "Đổi trạng thái thất bại",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
            ];
        }
        if ($result['status'] == 'success') {
            return [
                "status" => "success",
                "action" => ["swal", "modal"],
                "swal" => [
                    "text" => "Đổi trạng thái thành công",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".people-object-edit-modal" => "hide",
                    ".people-object-delete-modal" => "hide",
                ],
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
     * ajax mở modal xóa nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectGroupDeleteModal(PeopleObjectGroupDeleteRequest $request)
    {
        $param = $request->only([
            "people_object_group_id",
        ]);
        $data['item'] = $this->people->deletable(["people_object_group_id" => $param["people_object_group_id"]]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".people-object-delete-modal" => view("People::object-group.delete-modal", $data)->render(),
            ],
            "modal" => [
                ".people-object-delete-modal" => "show",
            ],
        ];
    }


    /**
     * ajax xóa nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectGroupDelete(Request $request)
    {
        $param = $request->only([
            "people_object_group_id",
        ]);

        $result = $this->people->objectGroupDelete($param);

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
                    ".people-object-delete-modal" => "hide",
                ],
                "submitForm" => ".ajax-people-object-group-list-form",
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
     * Danh sách đối tượng
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function objectList(Request $request)
    {
        $param = $request->only([
            "current_page",
            "perpage",
            "name",
            "is_active",
            "people_object_group_id",
        ]);
        $param['is_deleted'] = 0;

        $objectGroups = ['' => __('chọn nhóm đối tượng')] + $this->people->objectGroups(['is_active' => 1, 'is_deleted' => 0]);

        return view('People::object.list', [
            'list' => $this->people->objectPaginate($param),
            'param' => $param,
            'filters' => [
                'is_active' => [
                    'data' => [
                        '' => __('Chọn trạng thái'),
                        '1' => __('Đang hoạt động'),
                        '0' => __('Tạm ngừng'),
                    ]
                ],
                'people_object_group_id' => [
                    'data' => $objectGroups,
                ],
            ],
        ]);
    }

    /**
     * Danh sách đối tượng nhưng ajax
     *
     * @return array;
     */
    public function ajaxObjectList(Request $request)
    {
        $param = $request->only([
            "current_page",
            "perpage",
            "name",
            "is_active",
            "show_area",
            "people_object_group_id",
        ]);
        $param['is_deleted'] = 0;

        $data = [];
        $data['list'] = $this->people->objectPaginate($param);

        return [
            "status" => "success",
            "action" => ["html", ""],
            "html" => [
                $param['show_area'] ?? '.people-object-table' => view("People::object.table", $data)->render(),
            ],
        ];
    }

    /**
     * ajax mở modal thêm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectAddModal(Request $request)
    {
        $param = $request->only([]);

        $objectGroups = $this->people->objectGroups(['is_active' => 1, 'is_deleted' => 0]);
        $objectGroups[''] = __('Chọn nhóm đối tượng');

        $data['filters'] = [
            'people_object_group_id' => [
                'data' => $objectGroups,
            ],
        ];

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".people-object-add-modal" => view("People::object.add-modal", $data)->render(),
            ],
            "modal" => [
                ".people-object-add-modal" => "show",
            ],
        ];
    }

    /**
     * ajax thêm nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectAdd(PeopleObjectAddRequest $request)
    {
        $param = $request->only([
            "name",
            "code",
            "is_active",
            "people_object_group_id",
            "is_skip",
        ]);
        $param['is_active'] = 1;
        $param['is_skip'] = 0;

        $result = $this->people->objectAdd($param);

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
                "action" => ["swal", "modal"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".people-object-add-modal" => "hide",
                ],
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
     * ajax mở modal sửa nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectEditModal(Request $request)
    {
        $param = $request->only([
            "people_object_id",
        ]);
        $data['item'] = $this->people->object(["people_object_id" => $param["people_object_id"]]);

        $objectGroups = ['' => __('Chọn nhóm đối tượng')] + $this->people->objectGroups(['is_active' => 1, 'is_deleted' => 0]);

        $data['filters'] = [
            'people_object_group_id' => [
                'data' => $objectGroups,
            ],
        ];

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".people-object-edit-modal" => view("People::object.edit-modal", $data)->render(),
            ],
            "modal" => [
                ".people-object-edit-modal" => "show",
            ],
        ];
    }

    /**
     * ajax sửa đối tượng
     *
     * @return array;
     */
    public function ajaxObjectEdit(PeopleObjectEditRequest $request)
    {
        $param = $request->only([
            "people_object_id",
            "people_object_group_id",
            "name",
            "code",
            "is_active",
            "is_deleted",
            "is_skip",
        ]);
        //if(($param['is_active']??'')=='on') $param['is_active'] = 1;
        $param['is_active'] = ($param['is_active'] ?? '') == 'on' ? 1 : 0;
        $param['is_skip'] = ($param['is_skip'] ?? '') == 'on' ? 1 : 0;

        $result = $this->people->objectEdit($param);

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
                "action" => ["swal", "modal"],
                "swal" => [
                    "text" => $result['success'] ?? "",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".people-object-edit-modal" => "hide",
                    ".people-object-delete-modal" => "hide",
                ],
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

    public function ajaxObjectChangeStatus(Request $request)
    {
        $param = $request->only([
            "people_object_id",
            "is_active",
        ]);
        $param['is_active'] = ($param['is_active'] ?? '') == 'on' ? 1 : 0;

        $result = $this->people->objectEdit($param);

        if ($result['status'] == 'error') {
            return [
                "status" => "error",
                "action" => ["swal"],
                "swal" => [
                    "text" => "Đổi trạng thái thất bại",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
            ];
        }

        if ($result['status'] == 'success') {
            return [
                "status" => "success",
                "action" => ["swal", "modal"],
                "swal" => [
                    "text" => "Đổi trạng thái thành công",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".people-object-edit-modal" => "hide",
                    ".people-object-delete-modal" => "hide",
                ],
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
     * ajax mở modal xóa đối tượng
     *
     * @return array;
     */
    public function ajaxObjectDeleteModal(PeopleObjectDeleteRequest $request)
    {
        $param = $request->only([
            "people_object_id",
        ]);
        $data['item'] = $this->people->deletable(["people_object_id" => $param["people_object_id"]]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".people-object-delete-modal" => view("People::object.delete-modal", $data)->render(),
            ],
            "modal" => [
                ".people-object-delete-modal" => "show",
            ],
        ];
    }

    /**
     * ajax xóa nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxObjectDelete(Request $request)
    {
        $param = $request->only([
            "people_object_id",
        ]);

        $result = $this->people->objectDelete($param);

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
                    ".people-object-delete-modal" => "hide",
                ],
                "submitForm" => ".ajax-people-object-list-form",
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

    // quản lý công dân

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

        if (in_array('people_verification_id', $param)) {
            $array = $this->people->peopleVerificationOptions() ?? [];
            $option[''] = 'Chọn năm phúc tra';
            $result['people_verification_id'] = [
                'data' => $option + $array,
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

        if (in_array('people_id_license_place_id', $param)) {
            $option = [];
            $option[''] = 'Chọn nơi cấp';
            $array = $this->people->peopleIdLicensePlaceOptions() ?? [];

            $result['people_id_license_place_id'] = [
                'data' => $option + $array,
            ];
        }

        if (in_array('hometown_id', $param)) {
            $option = [];
            $option[''] = 'Chọn quê quán';
            $array = $this->people->provinceOptions() ?? [];

            $result['hometown_id'] = [
                'data' => $option + $array,
            ];
        }

        if (in_array('birthplace_id', $param)) {
            $option = [];
            $option[''] = 'Chọn nơi đăng ký khai sinh';
            $array = $this->people->provinceOptions() ?? [];

            $result['birthplace_id'] = [
                'data' => $option + $array,
            ];
        }


        if (in_array('people_group_id', $param)) {
            $option = [];
            $option[''] = 'Chọn khu phố';
            $array = $this->people->peopleGroupOptions() ?? [];

            $result['people_group_id'] = [
                'data' => $option + $array,
            ];
        }

        if (in_array('people_quarter_id', $param)) {
            $option = [];
            $option[''] = 'Chọn tổ dân phố';
            $array = $this->people->peopleQuarterOptions() ?? [];

            $result['people_quarter_id'] = [
                'data' => $option + $array,
            ];
        }

        if (in_array('people_family_type_id', $param)) {
            $option = [];
            $option[''] = 'Chọn thành phần gia đình';
            $array = $this->people->peopleFamilyTypeOptions() ?? [];

            $result['people_family_type_id'] = [
                'data' => $option + $array,
            ];
        }

        if (in_array('people_family_relationship_type_id', $param)) {
            $option = [];
            $option[''] = 'Chọn';
            $array = $this->people->peopleFamilyRelationshipTypeOptions() ?? [];

            $result['people_family_relationship_type_id'] = [
                'data' => $option + $array,
            ];
        }


        if (in_array('ethnic_id', $param)) {
            $option = [];
            //$option[''] = 'Chọn dân tộc';
            $array = $this->people->ethnicOptions() ?? [];

            $result['ethnic_id'] = [
                'data' => $option + $array,
            ];
        }

        if (in_array('religion_id', $param)) {
            $option = [];
            $option[''] = 'Không';
            $array = $this->people->religionOptions() ?? [];

            $result['religion_id'] = [
                'data' => $option + $array,
            ];
        }


        if (in_array('educational_level_id', $param)) {
            $option = [];
            $option[''] = 'Chọn văn hóa';
            $array = $this->people->educationalLevelOptions() ?? [];

            $result['educational_level_id'] = [
                'data' => $option + $array
            ];
        }

        if (in_array('people_job_id', $param)) {
            $option = [];
            $option[''] = 'Chọn nghề nghiệp';
            $array = $this->people->peopleJobOptions() ?? [];

            $result['people_job_id'] = [
                'data' => $option + $array
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


        if (in_array('people_object_id', $param)) {
            $option = [];
            $option[''] = 'Chọn đối tượng';
            $array = $this->people->peopleObjectOptions() ?? [];

            $result['people_object_id'] = [
                'data' => $option + $array
            ];
        }


        return $result;
    }


    /**
     * Danh sách công dân
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function list(Request $request)
    {
        //Forget session chọn
        session()->forget('people_choose');

        $field = [
            "age",
            "people_verification_id",
            "is_verified",
            "people_object_id",
        ];
        $param = $request->only("current_page", "perpage", "search", "age", "people_verification_id", "is_verified", "people_object_id");


        $filters = $this->filters($field);
        $param['people_verification_id'] = 0;
        foreach ($filters['people_verification_id']['data'] as $key => $dump) {
            $param['people_verification_id'] = $key;
            break;
        }


        $param['is_deleted'] = 0;

        return view('People::people.list', [
            'list' => $this->people->peoplePaginate($param),
            'param' => $param,
            'filters' => $filters
        ]);
    }

    /**
     * Danh sách công dân nhưng popup
     *
     * @return array;
     */
    public function ajaxListModal(Request $request)
    {
        $field = [
            "people_verification_year",
            "people_object_id",
            "people_object_group_id",
        ];

        $param = $request->only("current_page", "perpage", "search", "people_object_id", "people_verification_year", "people_object_group_id");
        $data['param'] = $param;

        $filters = $this->filters(["people_verification_id"]);
        foreach ($filters['people_verification_id']['data'] as $key => $val) {
            if ($param['people_verification_year'] == $val) {
                $param['people_verification_id'] = $key;
                $data['param']['people_verification_id'] = $key;
                break;
            }
        }

        $param['is_deleted'] = 0;

        $data['list'] = $this->people->peoplePaginate($param);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                '.people-list-modal' => view("People::people.list-modal", $data)->render(),
            ],
            "modal" => [
                '.people-list-modal' => "show",
            ],
        ];
    }

    /**
     * Danh sách công dân nhưng ajax
     *
     * @return array;
     */
    public function ajaxList(Request $request)
    {
        $field = [
            "age",
            "people_verification_id",
            "is_verified",
            "people_object_id",
        ];

        $param = $request->only("current_page", "perpage", "search", "age", "people_verification_id", "is_verified", "people_object_id", "people_object_group_id", "people_verification_year");
        $data['param'] = $param;

        $filters = $this->filters($field);

        if (!($param['people_verification_id'] ?? false)) {
            $param['people_verification_id'] = 0;
            foreach ($filters['people_verification_id']['data'] as $key => $dump) {
                $param['people_verification_id'] = $key;
                break;
            }
        }

        $param['is_deleted'] = 0;

        $arrCheck = [];

        //Lấy session đã chọn
        if (session()->get('people_choose')) {
            $arrCheck = session()->get('people_choose');
        }

        $data['arrCheck'] = $arrCheck;

        $data['list'] = $this->people->peoplePaginate($param);

        return [
            "status" => "success",
            "action" => ["html", ""],
            "html" => [
                '.people-table' => view("People::people.table", $data)->render(),
            ],
        ];
    }

    /**
     * ajax mở modal thêm công dân
     *
     * @return array;
     */
    public function ajaxAddModal(Request $request)
    {
        $param = $request->only([]);

        $data['filters'] = $this->filters([
            "age",
            "people_id_license_place_id",
            "is_verified",
            "people_object_id",
            "hometown_id",
            "ethnic_id",
            "religion_id",
            "people_job_id",
            "birthplace_id",
            "people_group_id",
            "people_quarter_id",
            "people_family_type_id",
            "educational_level_id",
            "people_family_relationship_type_id",
        ]);


        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".people-add-modal" => view("People::people.add-modal", $data)->render(),
            ],
            "modal" => [
                ".people-add-modal" => "show",
            ],
        ];
    }

    /**
     * ajax thêm công dân
     *
     * @return array;
     */
    public function ajaxAdd(PeopleAddRequest $request)
    {
        $param = $request->only([
            "full_name",
            "code",
            "birthday",
            "gender",
            "temporary_address",
            "permanent_address",
            "id_number",
            "id_license_date",
            "people_id_license_place_id",
            "hometown_id",
            "ethnic_id",
            "religion_id",
            "people_job_id",
            "birthplace_id",
            "people_group_id",
            "people_quarter_id",
            "group",
            "quarter",
            "people_family_type_id",
            "educational_level_id",
            "elementary_school",
            "middle_school",
            "high_school",
            "from_18_to_21",
            "from_21_to_now",
            "birth_year",
            "avatar",
            "is_deleted",
            "family_member",
            "union_join_date",
            "group_join_date",
            "graduation_year",
            "specialized",
            "foreign_language",
            "hometown",
            "birthplace",
            "workplace",
        ]);

        $param['is_active'] = 1;

        $result = $this->people->peopleAdd($param);

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
                    ".people-add-modal" => "hide",
                ],
                "submitForm" => '.ajax-people-list-form',
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
     * ajax mở modal sửa nhóm đối tượng
     *
     * @return array;
     */
    public function ajaxEditModal(Request $request)
    {
        $param = $request->only([
            "people_id",
        ]);

        $data['filters'] = $this->filters([
            "age",
            "people_id_license_place_id",
            "is_verified",
            "people_object_id",
            "hometown_id",
            "ethnic_id",
            "religion_id",
            "people_job_id",
            "birthplace_id",
            "people_group_id",
            "people_quarter_id",
            "people_family_type_id",
            "educational_level_id",
            "people_family_relationship_type_id",
        ]);

        $data['item'] = $this->people->people(["people_id" => $param["people_id"]]);


        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal"],
            "appendOrReplace" => [
                ".people-edit-modal" => view("People::people.edit-modal", $data)->render(),
            ],
            "modal" => [
                ".people-edit-modal" => "show",
                ".people-detail-modal" => "hide",
            ],
        ];
    }

    /**
     * ajax sửa đối tượng
     *
     * @return array;
     */
    public function ajaxEdit(PeopleEditRequest $request)
    {
        $param = $request->only([
            "people_id",
            "full_name",
            "code",
            "birthday",
            "gender",
            "temporary_address",
            "permanent_address",
            "id_number",
            "id_license_date",
            "people_id_license_place_id",
            "hometown_id",
            "ethnic_id",
            "religion_id",
            "people_job_id",
            "birthplace_id",
            "people_group_id",
            "people_quarter_id",
            "group",
            "quarter",
            "people_family_type_id",
            "educational_level_id",
            "elementary_school",
            "middle_school",
            "high_school",
            "from_18_to_21",
            "from_21_to_now",
            "birth_year",
            "avatar",
            "is_deleted",
            "family_member",
            "union_join_date",
            "group_join_date",
            "graduation_year",
            "specialized",
            "foreign_language",
            "hometown",
            "birthplace",
            "workplace",
        ]);
        //if(($param['is_active']??'')=='on') $param['is_active'] = 1;
        $param['birthday'] = Carbon::createFromFormat('d/m/Y', $param['birthday'])->toDateString();

        $result = $this->people->peopleEdit($param);

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
                    ".people-edit-modal" => "hide",
                    ".people-delete-modal" => "hide",
                ],
                "submitForm" => ".ajax-people-list-form",
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
            "people_id",
        ]);

        $result = $this->people->peopleDelete($param);

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
                    ".people-delete-modal" => "hide",
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
                    ".people-delete-modal" => "hide",
                ],
                "submitForm" => '.ajax-people-list-form',
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
            "people_id",
        ]);

        $data['filters'] = $this->filters([
            "age",
            "people_id_license_place_id",
            "is_verified",
            "people_object_id",
            "hometown_id",
            "ethnic_id",
            "religion_id",
            "people_job_id",
            "birthplace_id",
            "people_group_id",
            "people_quarter_id",
            "people_family_type_id",
            "educational_level_id",
            "people_family_relationship_type_id",
        ]);
        $data['item'] = $this->people->people(["people_id" => $param["people_id"]]);

        $data['filters2'] = app()->get(PeopleVerifyController::class)->options(["people_verification_id", "people_object_id"]);

        $data['list'] = $this->peopleVerify->getPaginate(['people_id' => $param['people_id']]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace", "modal", "remove"],
            "appendOrReplace" => [
                ".people-detail-modal" => view("People::people.detail-modal", $data)->render(),
            ],
            "modal" => [
                ".people-detail-modal" => "show",
            ],
            "remove" => [
                0 => ".people-verify-add-modal"
            ],
        ];
    }

    /**
     * Import excel
     *
     * @param Request $request
     * @return mixed
     */
    public function importAction(Request $request)
    {
        return $this->people->importExcel($request->file('file'));
    }

    /**
     * Export excel file error
     *
     * @param Request $request
     * @return mixed
     */
    public function exportExcelError(Request $request)
    {
        return $this->people->exportExcelError($request->all());
    }

    public function testView()
    {
        return view('People::people.error.table-error');
    }

    public function printPreview(Request $request)
    {
        $peopleId = $request->people_id;
        $data['item'] = $this->people->people(['people_id' => $peopleId]);

        $data['father'] = [];
        $data['mother'] = [];
        $data['partner'] = [];
        $data['member'] = [];

        foreach ($data['item']['family_member'] as $val) {

            switch ($val['people_family_relationship_type_id']) {
                case '1':
                    $data['father'] = $val;
                    break;
                case '2':
                    $data['mother'] = $val;
                    break;
                case '5':
                    $data['partner'] = $val;
                    break;
                case '6':
                    $data['partner'] = $val;
                    break;
                case '3':
                    $data['member'][] = $val;
                    break;
            }
        }

        $view = '';

        switch ($request->type) {
            case 'citizen':
                $view = 'People::people.print-template';
                break;
            case 'military':
                $view = 'People::people.print-military-service';
                break;
            case 'absent':
                $view = 'People::people.print-absent';
                break;
            case 'move':
                $view = 'People::people.print-move';
                break;
        }

        return view($view, [
            'arrayData' => [
                $data
            ]
        ]);
    }

    /**
     * Chọn công dân
     *
     * @param Request $request
     * @return mixed
     */
    public function choosePeopleAction(Request $request)
    {
        return $this->people->choosePeople($request->all());
    }

    /**
     * Bỏ chọn công dân
     *
     * @param Request $request
     * @return mixed
     */
    public function unChoosePeopleAction(Request $request)
    {
        return $this->people->unChoosePeople($request->all());
    }

    public function printMultipleAction()
    {
        $arrCheck = [];

        //Lấy session đã chọn
        if (session()->get('people_choose')) {
            $arrCheck = session()->get('people_choose');
        }

        if (count($arrCheck) > 0) {
            $dataResult = [];

            foreach ($arrCheck as $v) {
                //Lấy thông tin công dân
                $data['item'] = $this->people->people(['people_id' => $v['people_id']]);

                $data['father'] = [];
                $data['mother'] = [];
                $data['partner'] = [];
                $data['member'] = [];

                foreach ($data['item']['family_member'] as $val) {

                    switch ($val['people_family_relationship_type_id']) {
                        case '1':
                            $data['father'] = $val;
                            break;
                        case '2':
                            $data['mother'] = $val;
                            break;
                        case '5':
                            $data['partner'] = $val;
                            break;
                        case '6':
                            $data['partner'] = $val;
                            break;
                        case '3':
                            $data['member'][] = $val;
                            break;
                    }
                }

                $dataResult[] = $data;
            }

            return view('People::people.print-template', [
                'arrayData' => $dataResult
            ]);
        } else {
            echo ('Không có dữ liệu');
        }
    }

    /**
     * Cập nhật nhanh công dân
     *
     * @param Request $request
     * @return mixed
     */
    public function quickUpdateAction(Request $request)
    {
        try {
            $mPeople = app()->get(PeopleTable::class);

            $param = $request->only([
                "register_nvqs",
                "issuer_register_nvqs"
            ]);
            //if(($param['is_active']??'')=='on') $param['is_active'] = 1;
            $param['date_register_nvqs'] = $request->date_register_nvqs != null ? Carbon::createFromFormat('d/m/Y', $request->date_register_nvqs)->format('Y-m-d') : null;
    
            $result = $mPeople->edit($param, $request->people_id);

            return response()->json([
                'error' => false,
                'message' => 'Cập nhật thành công'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => false,
                'message' => 'Cập nhật thất b'
            ]);
        }
    }

     /**
     * Show popup camera
     *
     * @return mixed
     */
    public function showPopCameraAction()
    {
        $html = \View::make('People::people.pop-camera')->render();

        return response()->json([
            'html' => $html
        ]);
    }
}

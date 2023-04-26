<?php
/**
 * Created by PhpStorm
 * User: Huniel
 * Date: 4/26/2022
 * Time: 5:37 PM
 */

namespace Modules\People\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\People\Http\Requests\PeopleVerify\Edit as PeopleVerifyEditRequest;
use Modules\People\Http\Requests\PeopleVerify\Add as PeopleVerifyAddRequest;
use Modules\People\Http\Requests\PeopleVerify\Delete as PeopleVerifyDeleteRequest;
use Modules\People\Repositories\People\PeopleRepoIf;
use Illuminate\Routing\Controller as BaseController;
use Modules\People\Repositories\PeopleVerify\PeopleVerifyRepoIf;

class PeopleVerifyController extends BaseController
{
    protected $peopleVerify;
    protected $people;

    public function __construct(
        PeopleRepoIf $people,
        PeopleVerifyRepoIf $peopleVerify
    ){
        $this->peopleVerify = $peopleVerify;
        $this->people = $people;
    }


    // quản lý phúc tra
    /**
     * Danh sách option
     *
     * @return array
     */
    public function options($param=[])
    {
        $result = [];

        if( in_array('people_verification_id',$param) ){
            $array = $this->people->peopleVerificationOptions()??[];
            $option = [];
            $option[''] = 'Chọn năm phúc tra';
            $result['people_verification_id']=[
                'data'=>$option+$array
            ];
        }
        if( in_array('people_object_id',$param) ){
            $option = [];
            $option[''] = 'Chọn đối tượng';
            $array = $this->people->peopleObjectOptions()??[];
            $result['people_object_id']=[
                'data'=>$option+$array
            ];
        }
        if( in_array('people_health_type_id',$param) ){
            $option = [];
            $option[''] = 'Chọn tình trạng sức khỏe';
            $array = $this->people->peopleHealthTypeOptions()??[];
            $result['people_health_type_id']=[
                'data'=>$option+$array
            ];
        }



        return $result;
    }
    /**
     * Danh sách phúc tra
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function list(Request $request){
        $field = [
            "age",
            "people_verification_id",
            "is_verified",
            "people_object_id",
        ];
        $param = $request->only("current_page","perpage","search","age","people_verification_id","is_verified","people_object_id");

        $param['is_deleted'] = 0;

        return view('People::people.list', [
            'list' => $this->people->peoplePaginate($param),
            'param' => $param,
            'filters' => $this->filters($field)
        ]);

    }

    /**
     * Danh sách phúc tra nhưng ajax
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

        $param = $request->only("current_page","perpage","search","age","people_verification_id","is_verified","people_object_id","people_id");

        $data['list'] = $this->peopleVerify->getPaginate($param);
        return [
            "status" => "success",
            "action" => ["html"],
            "html" => [
                '.people-verify-table'=>view("People::verify.table",$data)->render(),
                ".people-verify-filters"=>view("People::verify.filters", ['filters2' => $this->options($field),'param' => $param] )->render(),
            ],
        ];

    }

    /**
     * ajax mở modal thêm phúc tra
     *
     * @return array;
     */
    public function ajaxAddModal(Request $request)
    {
        $param = $request->only([
            "people_id",
            "people_verification_id",
        ]);

        $data['item'] = $this->people->people(['people_id'=>$param['people_id']]);

        $data['options'] = $this->options([
            "people_verification_id",
            "people_object_id",
            "people_health_type_id",
        ]);
        $data['param'] = $param;
        $data['param']['birth_year'] = Carbon::parse($data['item']['birthday'])->format('Y');
        $data['param']['year'] = Carbon::now()->format('Y');
        if($param['people_verification_id']??false){
            $data['param']['year'] = $data['options']['people_verification_id']['data'][ $param['people_verification_id'] ] ?? Carbon::now()->format('Y');
        }
        $data['param']['age'] = $data['param']['year'] - $data['param']['birth_year'];

        return [
            "status" => "success",
            "action" => ["appendOrReplace","modal"],
            "appendOrReplace" => [
                ".people-verify-add-modal"=>view("People::verify.add-modal",$data)->render(),
            ],
            "modal" => [
                ".people-verify-add-modal"=>"show",
            ],
        ];
    }

    /**
     * ajax thêm phúc tra
     *
     * @return array;
     */
    public function ajaxAdd(PeopleVerifyAddRequest $request)
    {
        $param = $request->only([
            "people_id",
            "people_verification_id",
            "people_verification_year",
            "people_object_id",
            "content",
            "people_health_type_id",
            "note",
        ]);

        $result = $this->peopleVerify->verifyAdd($param);

        if ($result['status']=='error'){
            return  [
                "status" => "error",
                "action" => ["swal"],
                "swal" => [
                    "text" => $result['error']??"",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
            ];
        }
        if ($result['status']=='success'){
            return  [
                "status" => "success",
                "action" => ["swal","modal","ajaxFormSubmit"],
                "swal" => [
                    "text" => $result['success']??"",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".people-verify-add-modal" => "hide",
                ],
                "ajaxFormSubmit" => ['.ajax-people-verify-list-form','.ajax-people-list-form'],
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
     * ajax mở modal sửa phúc tra
     *
     * @return array;
     */
    public function ajaxEditModal(Request $request)
    {
        $param = $request->only([
            "people_verify_id",
        ]);

        $data['options'] = $this->options([
            "people_verification_id",
            "people_object_id",
            "people_health_type_id",
        ]);

        $data['item'] = $this->peopleVerify->getVerify([ "people_verify_id"=>$param["people_verify_id"] ]);

        $data['param'] = $param;
        $data['param']['age'] = Carbon::parse($data['item']['birthday'])->age;
        $data['param']['birth_year'] = Carbon::parse($data['item']['birthday'])->format('Y');


        return [
            "status" => "success",
            "action" => ["appendOrReplace","modal"],
            "appendOrReplace" => [
                ".people-verify-edit-modal"=>view("People::verify.edit-modal",$data)->render(),
            ],
            "modal" => [
                ".people-verify-edit-modal"=>"show",
            ],
        ];

    }

    /**
     * ajax sửa đối tượng
     *
     * @return array;
     */
    public function ajaxEdit(PeopleVerifyEditRequest $request)
    {
        $param = $request->only([
            "people_id",
            "people_verify_id",
            "people_verification_id",
            "people_verification_year",
            "people_object_id",
            "content",
            "people_health_type_id",
            "note",
        ]);
        //if(($param['is_active']??'')=='on') $param['is_active'] = 1;

        $result = $this->peopleVerify->verifyEdit($param);

        if ($result['status']=='error'){
            return [
                "status" => "error",
                "action" => ["swal"],
                "swal" => [
                    "text" => $result['error']??"",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
            ];
        }

        if ($result['status']=='success'){
            return  [
                "status" => "success",
                "action" => ["swal","modal","submitForm"],
                "swal" => [
                    "text" => $result['success']??"",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".people-verify-edit-modal" => "hide",
                    ".people-verify-delete-modal" => "hide",
                ],
                "submitForm"=>".ajax-people-verify-list-form",
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
     * ajax mở modal xóa công dân
     *
     * @return array;
     */
    public function ajaxDeleteModal(PeopleDeleteRequest $request)
    {
        $param = $request->only([
            "people_id",
        ]);
        $data['item'] = $this->people->deletable([ "people_id"=>$param["people_id"] ]);

        return [
            "status" => "success",
            "action" => ["appendOrReplace","modal"],
            "appendOrReplace" => [
                ".people-delete-modal"=>view("People::people.delete-modal",$data)->render(),
            ],
            "modal" => [
                ".people-delete-modal"=>"show",
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
            "people_verify_id",
        ]);

        $result = $this->peopleVerify->peopleVerifyDelete($param);

        if ($result['status']??false=='error'){
            return  [
                "status" => "error",
                "action" => ["swal","modal"],
                "swal" => [
                    "text" => $result['error']??"",
                    "title" => __('Lỗi'),
                    "type" => 'error',
                ],
                "modal" => [
                    ".people-delete-modal" => "hide",
                ],
            ];
        }
        if ($result['status']??'success'=='success'){
            return  [
                "status" => "success",
                "action" => ["swal","modal","submitForm"],
                "swal" => [
                    "text" => $result['success']??"",
                    "title" => __('Thành công'),
                    "type" => 'success',
                ],
                "modal" => [
                    ".people-delete-modal" => "hide",
                ],
                "submitForm" => '.ajax-people-verify-list-form',
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

        $data['item'] = $this->people->people([ "people_id"=>$param["people_id"] ]);


        return [
            "status" => "success",
            "action" => ["appendOrReplace","modal"],
            "appendOrReplace" => [
                ".people-detail-modal"=>view("People::people.detail-modal",$data)->render(),
            ],
            "modal" => [
                ".people-detail-modal"=>"show",
            ],
        ];

    }


}
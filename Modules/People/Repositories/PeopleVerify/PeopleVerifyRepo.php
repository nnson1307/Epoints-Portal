<?php
/**
 * Created by PhpStorm.
 * User: Huniel
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\People\Repositories\PeopleVerify;


use Modules\People\Models\EthnicTable;
use Modules\People\Models\PeopleDeletableTable;
use Modules\People\Models\PeopleFamilyTable;
use Modules\People\Models\PeopleObjectGroupTable;
use Modules\People\Models\PeopleObjectTable;
use Modules\People\Models\PeopleVerificationTable;
use Modules\People\Models\PeopleTable;
use Modules\People\Models\PeopleIdLicensePlaceTable;
use Modules\People\Models\PeopleJobTable;
use Modules\People\Models\PeopleGroupTable;
use Modules\People\Models\PeopleQuarterTable;
use Modules\People\Models\PeopleFamilyTypeTable;
use Modules\People\Models\EducationalLevelTable;
use Modules\People\Models\PeopleVerifyTable;
use Modules\People\Models\PeopleFamilyRelationshipTypeTable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\People\Models\ReligionTable;
use Modules\ZNS\Models\ProvinceTable;

class PeopleVerifyRepo implements PeopleVerifyRepoIf
{
    protected $peopleObjectGroup;
    protected $peopleObject;
    protected $peopleDeletable;
    protected $peopleVerification;
    protected $people;
    protected $peopleIdLicensePlace;
    protected $province;
    protected $ethnic;
    protected $peopleGroup;
    protected $peopleQuarter;
    protected $peopleFamilyType;
    protected $educationalLevel;
    protected $peopleFamilyRelationshipType;
    protected $peopleVerify;

    public function __construct(
        PeopleObjectGroupTable $peopleObjectGroup,
        PeopleObjectTable $peopleObject,
        PeopleDeletableTable $peopleDeletable,
        PeopleVerificationTable $peopleVerification,
        PeopleTable $people,
        PeopleIdLicensePlaceTable $peopleIdLicensePlace,
        ProvinceTable $province,
        EthnicTable $ethnic,
        ReligionTable $religon,
        PeopleJobTable $peopleJob,
        PeopleGroupTable $peopleGroup,
        PeopleQuarterTable $peopleQuarter,
        PeopleFamilyTypeTable $peopleFamilyType,
        EducationalLevelTable $educationalLevel,
        PeopleFamilyRelationshipTypeTable $peopleFamilyRelationshipType,
        PeopleFamilyTable $peopleFamily,
        PeopleVerifyTable $peopleVerify

    ){
        $this->peopleObjectGroup = $peopleObjectGroup;
        $this->peopleObject = $peopleObject;
        $this->peopleDeletable = $peopleDeletable;
        $this->peopleVerification = $peopleVerification;
        $this->people = $people;
        $this->peopleIdLicensePlace = $peopleIdLicensePlace;
        $this->province = $province;
        $this->ethnic = $ethnic;
        $this->religon = $religon;
        $this->peopleJob = $peopleJob;
        $this->peopleGroup = $peopleGroup;
        $this->peopleQuarter = $peopleQuarter;
        $this->peopleFamilyType = $peopleFamilyType;
        $this->educationalLevel = $educationalLevel;
        $this->peopleFamilyRelationshipType = $peopleFamilyRelationshipType;
        $this->peopleFamily = $peopleFamily;
        $this->peopleVerify = $peopleVerify;
    }

    public function getPaginate($param = [])
    {
        return $this->peopleVerify->getPaginate($param);
    }

    public function deletable($param) {
        return $this->peopleDeletable->where($param)->first()??['deletable'=>0];
    }



    // people
    public function peoplePaginate($param = [])
    {
        return $this->people->getPaginate($param);
    }
    public function people($param = [])
    {
        $data = $this->people->getPaginate($param+['perpage'=>'1'])->items()[0]->toArray();
        $data['family_member'] = $this->peopleFamily->where('people_id',$data['people_id'])->get()->toArray();
        return $data;
    }

    public function getVerify($param = []){
        return $this->peopleVerify->detail($param);
    }

    public function peopleDetail($param = []){
        return $this->people->detail($param);
    }

    public function verifyAdd($param = []){
        $verifyId=0;
        try{
            DB::beginTransaction();

            // check verification exist
            if( isset($param['people_verification_year'])&&!isset($param['people_verification_id']) ){
                $exist = $this->peopleVerification->where('name',$param['people_verification_year'])->first();
                if(!$exist){
                    $year = $param['people_verification_year'];
                    $param['people_verification_id'] = $this->peopleVerification->insertGetId([ 'name'=>$year,'date'=>$year."-01-01",'year'=>$year,'month'=>1,'day'=>1 ]);
                }else{
                    $param['people_verification_id'] = $exist->toArray()['people_verification_id'];
                }
                unset($param['people_verification_year']);
            }

            if( !isset($param['people_verification_year'])&&!isset($param['people_verification_id']) ){
                return [
                    'status'=>'error',
                    'error'=>'Lỗi thiếu dữ liệu năm phúc tra',
                ];
            }


            // check exist
            $exist = $this->peopleVerify->where([
                ['people_verification_id',$param['people_verification_id']],
                ['people_id',$param['people_id']],
            ])->count();

            if($exist){
                DB::rollBack();
                return [
                    'status'=>'error',
                    'error'=>'Đã thực hiện phúc tra vào năm này',
                ];
            }

            //deletable
            $verifyId =  $this->peopleVerify->insertGetId($param);

            $this->peopleDeletable->where('people_object_id',$param['people_object_id'])->update(['deletable'=>0]);
            $this->peopleDeletable->where('people_id',$param['people_id'])->update(['deletable'=>0]);


            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            dd($e);

        }

        if(true??false) {
            return [
                'status'=>'success',
                'success'=>__('Thêm phúc tra thành công'),
            ];
        }else{
            return [
                'status'=>'error',
                'error'=>'Lỗi không xác định',
            ];
        }
    }

    public function verifyEdit($param = []) {
        $people_verify_id = $param['people_verify_id'];
        unset($param['people_verify_id']);
//        $people_verification_year = $param['people_verification_year'];
//        unset($param['people_verification_year']);

        try{
            DB::beginTransaction();

            $data = [];

            // check exist
//            $exist = $this->peopleVerify->where( [
//                ['people_verify.people_verify_id',"<>",$people_verify_id],
//                ['people_verification.name','LIKE',$people_verification_year ],
//                ['people_verify.people_id',$param['people_id'] ],
//            ] )->join('people_verification','people_verification.people_verification_id','people_verify.people_verification_id')
//                ->count();
            $exist = 0;

            if($exist){
                return [
                    'status'=>error,
                    'error'=>'Lỗi phúc tra bị trùng.',
                ];
            }

            $result = $this->peopleVerify->where( ['people_verify_id'=>$people_verify_id] )->update($param);
            $this->peopleDeletable->where('people_object_id',$param['people_object_id'])->update(['deletable'=>0]);
            $this->peopleDeletable->where('people_object_id',$param['people_id'])->update(['deletable'=>0]);


            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            dd($e);

        }


        if($result){
            return [
                'status'=>'success',
                'success'=>__('Sửa phúc tra thành công'),
            ];
        }else{
            return false;
        }
    }

    public function peopleVerifyDelete($param = []){
        // check deletable
//        $deletable = $this->peopleDeletable->where([
//            [ "people_deletable.people_id", $param['people_id'] ],
//            [ "people_deletable.deletable",0],
//        ])->count();
//        if($deletable){
//            $result = $this->people->where("people_id",$param['people_id'])->update(['is_deleted'=>1]);
//        }else{
//            return [
//                'status'=>'error',
//                'error'=>__('Lỗi không thể xóa do công dân đang được sử dụng'),
//            ];
//        }
        $result = $this->peopleVerify->where("people_verify_id",$param['people_verify_id'])->delete();

        if($result){
            return [
                'status'=>'success',
                'success'=>__('Đã xóa phúc tra thành công'),
            ];
        }else{
            return false;
        }

    }

}
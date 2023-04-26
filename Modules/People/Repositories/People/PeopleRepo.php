<?php
/**
 * Created by PhpStorm.
 * User: Huniel
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\People\Repositories\People;

use finfo;
use App\Exports\ExportErrorPeople;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Repositories\Upload\UploadRepo;
use Modules\People\Models\EthnicTable;
use Modules\People\Models\PeopleDeletableTable;
use Modules\People\Models\PeopleFamilyTable;
use Modules\People\Models\PeopleImportError;
use Modules\People\Models\PeopleObjectGroupTable;
use Modules\People\Models\PeopleObjectTable;
use Modules\People\Models\PeopleReportLogTable;
use Modules\People\Models\PeopleVerificationTable;
use Modules\People\Models\PeopleTable;
use Modules\People\Models\PeopleIdLicensePlaceTable;
use Modules\People\Models\PeopleJobTable;
use Modules\People\Models\PeopleGroupTable;
use Modules\People\Models\PeopleQuarterTable;
use Modules\People\Models\PeopleFamilyTypeTable;
use Modules\People\Models\PeopleHealthTypeTable;
use Modules\People\Models\EducationalLevelTable;
use Modules\People\Models\PeopleFamilyRelationshipTypeTable;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\People\Models\ReligionTable;
use Modules\ZNS\Models\ProvinceTable;

class PeopleRepo implements PeopleRepoIf
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
    protected $peopleReportLog;
    protected $peopleHealthType;

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
        PeopleReportLogTable $peopleReportLog,
        PeopleHealthTypeTable $peopleHealthType


    )
    {
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
        $this->peopleReportLog = $peopleReportLog;
        $this->peopleHealthType = $peopleHealthType;


    }

    public function test()
    {
        $province = $this->province->get();
        foreach ($province as $key => $val) {
            $name = "Công an {$val['type']} {$val['name']}";
            $this->peopleIdLicensePlace->insert([
                'people_id_license_place_id' => $val['provinceid'],
                'name' => $name,
            ]);
        }
    }

    public function objectGroupPaginate($param = [])
    {
        return $this->peopleObjectGroup->getPaginate($param);
    }

    public function objectGroup($param = [])
    {
        return $this->peopleObjectGroup->objectGroup($param);
    }

    public function objectGroups($param = [])
    {
        $param['is_deleted'] = 0;
        $param['is_active'] = 1;
        return $this->peopleObjectGroup->queryBuild($param)->get()->pluck('name', 'people_object_group_id')->toArray();
    }

    public function objectGroupAdd($param = [])
    {
        $param['created_by'] = Auth::id();
        try {
            DB::beginTransaction();
            $peopleObjectGroupId = $this->peopleObjectGroup->insertGetId($param);
            $result = $this->peopleDeletable->insert([
                'people_object_group_id' => $peopleObjectGroupId,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }

        if ($result) {
            return [
                'status' => 'success',
                'success' => __('Thêm nhóm đối tượng thành công'),
            ];
        } else {
            return false;
        }
    }

    public function objectGroupEdit($param = [])
    {
        $people_object_group_id = $param['people_object_group_id'];
        unset($param['people_object_group_id']);
        $result = $this->peopleObjectGroup->where(['people_object_group_id' => $people_object_group_id])->update($param);
        if ($result) {
            return [
                'status' => 'success',
                'success' => __('Thực hiện thành công'),
            ];
        } else {
            return false;
        }
    }


    public function objectGroupDelete($param = [])
    {
        // check deletable
        $deletable = $this->deletable([
            'people_object_group_id'=>$param['people_object_group_id'],
        ]);

        if ($deletable) {
            $result = $this->peopleObjectGroup->where("people_object_group_id", $param['people_object_group_id'])->update(['is_deleted' => 1]);
        } else {
            return [
                'status' => 'error',
                'error' => __('Lỗi không thể xóa do nhóm đối tượng đang được sử dụng'),
            ];
        }

        if ($result) {
            return [
                'status' => 'success',
                'success' => __('Đã xóa nhóm đối tượng'),
            ];
        } else {
            return false;
        }

    }


    public function objectPaginate($param = [])
    {
        return $this->peopleObject->getPaginate($param);
    }

    public function object($param = [])
    {
        $result = $this->peopleObject->object($param);
        if($result){
            return $result->toArray();
        }else{
            return null;
        }
    }

    public function objects($param = [])
    {
        return $this->peopleObject->queryBuild($param)->get()->pluck('name', 'people_object_id')->toArray();
    }

    public function objectAdd($param = [])
    {
        $param['created_by'] = Auth::id();
        try {
            DB::beginTransaction();
            $peopleObjectId = $this->peopleObject->insertGetId($param);
            $result = $this->peopleDeletable->insert([
                'people_object_id' => $peopleObjectId,
                'deletable' => 1,
            ]);
            $this->peopleDeletable->where('people_object_group_id',$param['people_object_group_id'])->update([
                'deletable' => 0,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }

        if ($result) {
            return [
                'status' => 'success',
                'success' => __('Thêm đối tượng thành công'),
            ];
        } else {
            return false;
        }
    }

    public function objectEdit($param = [])
    {
        $people_object_id = $param['people_object_id'];
        unset($param['people_object_id']);

        try {
            DB::beginTransaction();

            $result = $this->peopleObject->where(['people_object_id' => $people_object_id])->update($param);

            $this->peopleDeletable->where('people_object_id',$people_object_id )->update([
                'deletable' => 0,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }




        if ($result) {
            return [
                'status' => 'success',
                'success' => __('Thực hiện thành công'),
            ];
        } else {
            return false;
        }
    }


    public function objectDelete($param = [])
    {
        // check deletable
        $deletable = $this->deletable([
            'people_object_id'=>$param['people_object_id'],
        ]);

        if ($deletable) {
            $result = $this->peopleObject->where("people_object_id", $param['people_object_id'])->update(['is_deleted' => 1]);
        } else {
            return [
                'status' => 'error',
                'error' => __('Lỗi không thể xóa do đối tượng đang được sử dụng'),
            ];
        }

        if ($result??false) {
            return [
                'status' => 'success',
                'success' => __('Đã xóa đối tượng'),
            ];
        } else {
            return false;
        }

    }

    public function deletable($param)
    {
        $result = $this->peopleDeletable->where($param)->where('deletable',1)->count();
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function peopleVerificationOptions($param=[]){
        return $this->peopleVerification->orderBy('date','DESC')->get()->pluck('name','people_verification_id')->toArray();
    }

    public function peopleIdLicensePlaceOptions($param = [])
    {
        return $this->peopleIdLicensePlace->get()->pluck('name', 'people_id_license_place_id')->toArray();
    }

    public function provinceOptions($param = [])
    {
        return $this->province->get()->pluck('name', 'provinceid')->toArray();
    }

    public function ethnicOptions($param = [])
    {
        return $this->ethnic->get()->pluck('name', 'ethnic_id')->toArray();
    }

    public function religionOptions($param = [])
    {
        return $this->religon->get()->pluck('name', 'religion_id')->toArray();
    }

    public function peopleJobOptions($param = [])
    {
        return $this->peopleJob->get()->pluck('name', 'people_job_id')->toArray();
    }

    public function peopleGroupOptions($param = [])
    {
        return $this->peopleGroup->get()->pluck('name', 'people_group_id')->toArray();
    }

    public function peopleQuarterOptions($param = [])
    {
        return $this->peopleQuarter->get()->pluck('name', 'people_quarter_id')->toArray();
    }

    public function peopleFamilyTypeOptions($param = [])
    {
        return $this->peopleFamilyType->get()->pluck('name', 'people_family_type_id')->toArray();
    }

    public function educationalLevelOptions($param = [])
    {
        return $this->educationalLevel->get()->pluck('name', 'educational_level_id')->toArray();
    }

    public function peopleFamilyRelationshipTypeOptions($param = [])
    {
        return $this->peopleFamilyRelationshipType->get()->pluck('name', 'people_family_relationship_type_id')->toArray();
    }

    public function peopleObjectOptions($param = [])
    {
        return $this->peopleObject->where(['is_deleted'=>0,'is_active'=>1])->get()->pluck('name', 'people_object_id')->toArray();
    }

    public function peopleHealthTypeOptions($param=[]){
        return $this->peopleHealthType->get()->pluck('name','people_health_type_id')->toArray();
    }



    // people
    public function peoplePaginate($param = [])
    {
        return $this->people->getPaginate($param);
    }

    public function people($param = [])
    {
        $result = $this->people->getPaginate($param + ['perpage' => '1'])->items();

        if($result){
            $data = $result[0]->toArray();
            $data['family_member'] = $this->peopleFamily
                ->leftjoin('people_job','people_job.people_job_id','people_family.people_job_id')
                ->where('people_family.people_id', $data['people_id'])
                ->addSelect('people_family.*','people_job.name as people_job_name')
                ->get()->toArray();
        }else{
            $data=[];
        }
        return $data;
    }

    public function peopleDetail($param = [])
    {
        return $this->people->detail($param);
    }

    public function peopleAdd($param = [])
    {
        try {
            DB::beginTransaction();

            $data = [];
            // family member
            $family_member = $param['family_member'];

            unset($param['family_member']);
            // add birthyear to report log
            $birthyear = Carbon::createFromFormat('d/m/Y',$param['birthday'])->format('Y');
            if($param['union_join_date']??false) $param['union_join_date'] = Carbon::createFromFormat('d/m/Y',$param['union_join_date'])->toDateString();
            if($param['group_join_date']??false) $param['group_join_date'] = Carbon::createFromFormat('d/m/Y',$param['group_join_date'])->toDateString();
            $exist = $this->peopleReportLog->where('birthyear',$birthyear)->count();
            if(!$exist) $this->peopleReportLog->insert(['birthyear'=>$birthyear]);

            $param['birthday'] = Carbon::createFromFormat('d/m/Y',$param['birthday'])->format('Y-m-d');
            if( $param['id_license_date']??false ){
                $param['id_license_date'] = Carbon::createFromFormat('d/m/Y',$param['id_license_date'])->format('Y-m-d');
            }
            //dd();
            $peopleId = $this->people->insertGetId($param);
            foreach ($family_member as $field => $val) {
                foreach ($val as $key => $value) {
                    if ( $family_member['people_family_relationship_type_id'][$key] && $family_member['full_name'][$key] ) {
                        $data[$key][$field] = $value??null;
                        $data[$key]['people_id'] = $peopleId;
                    }
                }
            }

            foreach ($data as $key => $value) {
                if( !($value['full_name']??0) ){
                    unset($data[$key]);
                }
            }
            $peopleFamily = $this->peopleFamily->insert($data);

            //deletable
            $result = $this->peopleDeletable->insert([
                'people_id' => $peopleId,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];

        }

        if ($result ?? false) {
            return [
                'status' => 'success',
                'success' => __('Thêm công dân thành công'),
            ];
        } else {
            return false;
        }
    }

    public function peopleEdit($param = [])
    {
        $people_id = $param['people_id'];
        unset($param['people_id']);

        try {
            DB::beginTransaction();

            $family_member = $param['family_member'];
            unset($param['family_member']);

            $dataUpdate = [];
            $dataInsert = [];
            $dataDelete = [];
            foreach ($family_member as $field => $val) {
                foreach ($val as $key => $value) {
                    if ( isset($val[$key]) ) {
                        if ( isset($family_member['people_family_id'][$key]) && isset($family_member['full_name'][$key]) ) {
                            $dataUpdate[$family_member['people_family_id'][$key]][$field] = $value;
                            $dataUpdate[$family_member['people_family_id'][$key]]['people_id'] = $people_id;
                        } elseif( isset($family_member['people_family_id'][$key]) ){
                            $dataDelete[$family_member['people_family_id'][$key]][$field] = $value;
                            $dataDelete[$family_member['people_family_id'][$key]]['people_id'] = $people_id;
                        } else {
                            $dataInsert[$key][$field] = $value;
                            $dataInsert[$key]['people_id'] = $people_id;
                        }
                    }
                }
            }
            foreach ($dataUpdate as $key => $value) {
                if( !($value['full_name']??0) ){
                    unset($dataUpdate[$key]);
                }
            }
            foreach ($dataInsert as $key => $value) {
                if( !($value['full_name']??0) ){
                    unset($dataInsert[$key]);
                }
            }

            //$param['birthday'] = Carbon::createFromFormat('d/m/Y',$param['birthday'])->format('Y-m-d');
            if($param['union_join_date']??false) $param['union_join_date'] = Carbon::createFromFormat('d/m/Y',$param['union_join_date'])->toDateString();
            if($param['group_join_date']??false) $param['group_join_date'] = Carbon::createFromFormat('d/m/Y',$param['group_join_date'])->toDateString();
            if($param['id_license_date']??false){
                $param['id_license_date'] = Carbon::createFromFormat('d/m/Y',$param['id_license_date'])->format('Y-m-d');
            }
            $result = $this->people->where(['people_id' => $people_id])->update($param);
            $resultInsert = $this->peopleFamily->insert($dataInsert);
            //$resultUpdate = $this->peopleFamily->update($dataUpdate);

            foreach ($dataUpdate as $key => $val) {
                $this->peopleFamily->where('people_family_id', $key)->update($val);
            }
            foreach ($dataDelete as $key => $val) {
                $this->peopleFamily->where('people_family_id', $key)->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];

        }



        return [
            'status' => 'success',
            'success' => __('Chỉnh sửa thành công'),
        ];
    }



    public function peopleDelete($param = [])
    {
        // check deletable
        $deletable = $this->deletable([
            'people_id'=>$param['people_id'],
        ]);
        if ($deletable) {
            $result = $this->people->where("people_id", $param['people_id'])->update(['is_deleted' => 1]);
        } else {
            return [
                'status' => 'error',
                'error' => __('Không thể xoá công dân đã được phúc tra'),
            ];
        }

        if ($result) {
            return [
                'status' => 'success',
                'success' => __('Đã xóa công dân thành công'),
            ];
        } else {
            return false;
        }

    }

    /**
     * Import excel
     *
     * @param $file
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function importExcel($file)
    {
        try {
            if (isset($file)) {
                $typeFileExcel = $file->getClientOriginalExtension();

                if ($typeFileExcel == "xlsx") {
                    $reader = ReaderFactory::create(Type::XLSX);
                    $reader->open($file);

                    //Khai báo model
                    $mIdLicensePlace = app()->get(PeopleIdLicensePlaceTable::class);
                    $mProvince = app()->get(\Modules\People\Models\ProvinceTable::class);
                    $mReligion = app()->get(ReligionTable::class);
                    $mEthnic = app()->get(EthnicTable::class);
                    $mPeopleJob = app()->get(PeopleJobTable::class);
                    $mPeopleGroup = app()->get(PeopleGroupTable::class);
                    $mPeopleQuarter = app()->get(PeopleQuarterTable::class);
                    $mFamilyType = app()->get(PeopleFamilyTypeTable::class);
                    $mEducationLevel = app()->get(EducationalLevelTable::class);
                    $mFamily = app()->get(PeopleFamilyTable::class);
                    $mPeopleDeletable = app()->get(PeopleDeletableTable::class);

                    $arrError = [];
                    $numberSuccess = 0;
                    $numberError = 0;

                    // sẽ trả về các object gồm các sheet
                    foreach ($reader->getSheetIterator() as $sheet) {
                        // đọc từng dòng
                        foreach ($sheet->getRowIterator() as $key => $row) {
                            if ($key > 3) {
                                //Lấy dòng dữ liệu
                                $rowFile = $this->getRowFilExcel($row);
                                //Lưu log lỗi có gì xuất excel
                                $errorRow = $this->getRowFilExcel($row);
                                $errorRow['error'] = '';

                                if ($rowFile['code'] == '' && $rowFile['full_name'] == '' && $rowFile['gender'] == ''
                                    && $rowFile['id_number'] == '' && $rowFile['id_license_date'] == '' && $rowFile['people_id_license_place'] == ''
                                    && $rowFile['birth_day'] == '' && $rowFile['birth_month'] == '' && $rowFile['birth_year'] == ''
                                    && $rowFile['permanent_address'] == '' && $rowFile['temporary_address'] == '' && $rowFile['birthplace'] == ''
                                    && $rowFile['hometown'] == '' && $rowFile['people_group'] == '' && $rowFile['people_quarter'] == ''
                                    && $rowFile['ethnic'] == '' && $rowFile['religion'] == '' && $rowFile['people_family'] == ''
                                    && $rowFile['educational_level'] == '' && $rowFile['people_job'] == '' && $rowFile['elementary_school'] == ''
                                    && $rowFile['middle_school'] == '' && $rowFile['high_school'] == '' && $rowFile['from_18_to_21'] == ''
                                    && $rowFile['from_21_to_now'] == '' && $rowFile['full_name_dad'] == '' && $rowFile['birth_year_dad'] == ''
                                    && $rowFile['job_dad'] == '' && $rowFile['before_30_04_dad'] == '' && $rowFile['after_30_04_dad'] == '' && $rowFile['current_dad'] == ''
                                    && $rowFile['full_name_mom'] == '' && $rowFile['birth_year_mom'] == '' && $rowFile['job_mom'] == '' && $rowFile['current_mom'] == ''
                                    && $rowFile['before_30_04_mom'] == '' && $rowFile['after_30_04_mom'] == '' && $rowFile['info_brother_1'] == ''
                                    && $rowFile['info_brother_2'] == '' && $rowFile['info_brother_3'] == '' && $rowFile['info_brother_4'] == ''
                                    && $rowFile['info_brother_5'] == '' && $rowFile['info_brother_6'] == '' && $rowFile['full_name_couple'] == ''
                                    && $rowFile['birth_year_couple'] == '' && $rowFile['job_couple'] == '' && $rowFile['info_child_1'] == '' && $rowFile['info_child_2'] == '') {
                                    continue;
                                }

                                //Validate các thông tin cơ bản của bảng people
                                $errorRow['error'] = $this->validatePeople($rowFile);

                                if (!empty($errorRow['error'])) {
                                    $numberError++;
                                    $arrError [] = $errorRow;
                                    continue;
                                }

                                $dataPeople = [
                                    'code' => $rowFile['code'],
                                    'full_name' => $rowFile['full_name'],
                                    'gender' => 'others',
                                    'id_number' => $rowFile['id_number'],
                                    'id_license_date' => $rowFile['id_license_date'] != '' ? Carbon::createFromFormat('d/m/Y', $rowFile['id_license_date'])->format('Y-m-d') : null,
                                    'birthday' => $rowFile['birth_year'] . '-' . $rowFile['birth_month'] . '-' . $rowFile['birth_day'],
                                    'permanent_address' => $rowFile['permanent_address'],
                                    'temporary_address' => $rowFile['temporary_address'],
                                    'birth_year' => $rowFile['birth_year'],
                                    'elementary_school' => $rowFile['elementary_school'],
                                    'middle_school' => $rowFile['middle_school'],
                                    'high_school' => $rowFile['high_school'],
                                    'from_18_to_21' => $rowFile['from_18_to_21'],
                                    'from_21_to_now' => $rowFile['from_21_to_now'],
                                    'group' => $rowFile['people_group'], //Khu phố
                                    'quarter' => $rowFile['people_quarter'], //Tổ dân phố
                                    'hometown' => $rowFile['hometown'], //quê quán
                                    'birthplace' => $rowFile['birthplace'], //khai sinh
                                    'union_join_date' => $rowFile['union_join_date'] != '' ? Carbon::createFromFormat('d/m/Y', $rowFile['union_join_date'])->format('Y-m-d') : null,
                                    'group_join_date' => $rowFile['group_join_date'] != '' ? Carbon::createFromFormat('d/m/Y', $rowFile['group_join_date'])->format('Y-m-d') : null,
                                    'graduation_year' => $rowFile['graduation_year'],
                                    'specialized' => $rowFile['specialized'],
                                    'foreign_language' => $rowFile['foreign_language'],
                                ];

                                if ($rowFile['gender'] != null && !empty($rowFile['gender']) && $rowFile['gender'] == "Nam") {
                                    $dataPeople['gender'] = 'male';
                                } else if ($rowFile['gender'] != null && !empty($rowFile['gender']) && $rowFile['gender'] == "Nữ") {
                                    $dataPeople['gender'] = 'female';
                                }

                                //Lấy id nơi cấp
                                $getLicensePlace = $mIdLicensePlace->getLicensePlaceByName($rowFile['people_id_license_place']);

                                if ($getLicensePlace != null) {
                                    $dataPeople['people_id_license_place_id'] = $getLicensePlace['people_id_license_place_id'];
                                }

                                //Lấy id tôn giáo
                                $getReligion = $mReligion->getReligionByName($rowFile['religion']);

                                if ($getReligion != null) {
                                    $dataPeople['religion_id'] = $getReligion['religion_id'];
                                }

                                //Lấy id dân tộc
                                $getEthnic = $mEthnic->getEthnicByName($rowFile['ethnic']);

                                if ($getEthnic != null) {
                                    $dataPeople['ethnic_id'] = $getEthnic['ethnic_id'];
                                }

                                //Lấy id nghề nghiệp
                                $getPeopleJob = $mPeopleJob->getPeopleJobByName($rowFile['people_job']);

                                if ($getPeopleJob != null) {
                                    $dataPeople['people_job_id'] = $getPeopleJob['people_job_id'];
                                }

                                //Lấy id thành phần gia đình
                                $getFamilyType = $mFamilyType->getFamilyTypeByName($rowFile['people_family']);

                                if ($getFamilyType != null) {
                                    $dataPeople['people_family_type_id'] = $getFamilyType['people_family_type_id'];
                                }

                                //Lấy id trình độ học vấn
                                $getEducationLevel = $mEducationLevel->getEducationLevelByName($rowFile['educational_level']);

                                if ($getEducationLevel != null) {
                                    $dataPeople['educational_level_id'] = $getEducationLevel['educational_level_id'];
                                }

                                //Thêm thông tin nhân sự
                                $peopleId = $this->people->add($dataPeople);

                                $dataDad = [];

                                if ($rowFile['full_name_dad'] != null && !empty($rowFile['full_name_dad'])) {
                                    $dataDad['full_name'] = $rowFile['full_name_dad'];
                                }

                                if ($rowFile['birth_year_dad'] != null && !empty($rowFile['birth_year_dad'])) {
                                    $dataDad['birth_year'] = $rowFile['birth_year_dad'];
                                }

                                if ($rowFile['job_dad'] != null && !empty($rowFile['job_dad'])) {
                                    $getPeopleJobDad = $mPeopleJob->getPeopleJobByName($rowFile['job_dad']);

                                    if ($getPeopleJobDad != null) {
                                        $dataDad['people_job_id'] = $getPeopleJobDad['people_job_id'];
                                    }
                                }

                                if ($rowFile['before_30_04_dad'] != null && !empty($rowFile['before_30_04_dad'])) {
                                    $dataDad['before_30041975'] = $rowFile['before_30_04_dad'];
                                }

                                if ($rowFile['after_30_04_dad'] != null && !empty($rowFile['after_30_04_dad'])) {
                                    $dataDad['after_30041975'] = $rowFile['after_30_04_dad'];
                                }

                                if ($rowFile['current_dad'] != null && !empty($rowFile['current_dad'])) {
                                    $dataDad['current'] = $rowFile['current_dad'];
                                }

                                if (count($dataDad) > 0) {
                                    $dataDad['people_id'] = $peopleId;
                                    $dataDad['people_family_relationship_type_id'] = 1;
                                    //Thêm thông tin cha
                                    $mFamily->add($dataDad);
                                }

                                $dataMom = [];

                                if ($rowFile['full_name_mom'] != null && !empty($rowFile['full_name_mom'])) {
                                    $dataMom['full_name'] = $rowFile['full_name_mom'];
                                }

                                if ($rowFile['birth_year_mom'] != null && !empty($rowFile['birth_year_mom'])) {
                                    $dataMom['birth_year'] = $rowFile['birth_year_mom'];
                                }

                                if ($rowFile['job_mom'] != null && !empty($rowFile['job_mom'])) {
                                    $getPeopleJobDad = $mPeopleJob->getPeopleJobByName($rowFile['job_mom']);

                                    if ($getPeopleJobDad != null) {
                                        $dataMom['people_job_id'] = $getPeopleJobDad['people_job_id'];
                                    }
                                }

                                if ($rowFile['before_30_04_mom'] != null && !empty($rowFile['before_30_04_mom'])) {
                                    $dataMom['before_30041975'] = $rowFile['before_30_04_mom'];
                                }

                                if ($rowFile['after_30_04_mom'] != null && !empty($rowFile['after_30_04_mom'])) {
                                    $dataMom['after_30041975'] = $rowFile['after_30_04_mom'];
                                }

                                if ($rowFile['current_mom'] != null && !empty($rowFile['current_mom'])) {
                                    $dataMom['current'] = $rowFile['current_mom'];
                                }

                                if (count($dataMom) > 0) {
                                    $dataMom['people_id'] = $peopleId;
                                    $dataMom['people_family_relationship_type_id'] = 2;
                                    //Thêm thông tin mẹ
                                    $mFamily->add($dataMom);
                                }

                                //Thông tin anh/chị
                                for ($i = 1; $i <= 6; $i ++) {
                                    if ($rowFile["info_brother_$i"] != null && !empty($rowFile["info_brother_$i"])) {
                                        $mFamily->add([
                                            'people_id' => $peopleId,
                                            'full_name' => $rowFile["info_brother_$i"],
                                            'people_family_relationship_type_id' => 3
                                        ]);
                                    }
                                }

                                //Thông tin vợ/chồng
                                $dataCouple = [];

                                if ($rowFile['full_name_couple'] != null && !empty($rowFile['full_name_couple'])) {
                                    $dataCouple['full_name'] = $rowFile['full_name_couple'];
                                }

                                if ($rowFile['birth_year_couple'] != null && !empty($rowFile['birth_year_couple'])) {
                                    $dataCouple['birth_year'] = $rowFile['birth_year_couple'];
                                }

                                if ($rowFile['job_couple'] != null && !empty($rowFile['job_couple'])) {
                                    $getPeopleJoCouple = $mPeopleJob->getPeopleJobByName($rowFile['job_couple']);

                                    if ($getPeopleJoCouple != null) {
                                        $dataCouple['people_job_id'] = $getPeopleJoCouple['people_job_id'];
                                    }
                                }

                                if (count($dataCouple) > 0) {
                                    $dataCouple['people_id'] = $peopleId;
                                    $dataCouple['people_family_relationship_type_id'] = $dataPeople['gender'] == 'male' ? '5': '6';
                                    //Thêm thông tin vợ/chồng
                                    $mFamily->add($dataCouple);
                                }

                                //Thông tin con
                                for ($i = 1; $i <= 2; $i ++) {
                                    if ($rowFile["info_child_$i"] != null && !empty($rowFile["info_child_$i"])) {
                                        $mFamily->add([
                                            'people_id' => $peopleId,
                                            'full_name' => $rowFile["info_child_$i"],
                                            'people_family_relationship_type_id' => 7
                                        ]);
                                    }
                                }

                                //Insert data vào people_deletable (bảng có thể xoá)
                                $mPeopleDeletable->add([
                                    'people_id' => $peopleId
                                ]);

                                //Thành công
                                $numberSuccess++;
                            }
                        }
                    }

                    $reader->close();


                    $this->storeImportError($arrError);


                    return response()->json([
                        'success' => 1,
                        'message' => __('Số dòng thành công') . ':' . $numberSuccess . '<br/>' . __('Số dòng thất bại') . ':' . $numberError,
                        'number_error' => $numberError,
                        //'data_error' => $arrError
                    ]);
                } else {
                    return response()->json([
                        'success' => 0,
                        'message' => __('File không đúng định dạng')
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => __('Import thông tin khách hàng thất bại'),
                '_message' => $e->getMessage() . ' ' . $e->getLine() . $e->getFile()
            ]);
        }
    }

    public function storeImportError($array=[]){
        $peopleImportErrorTable = app()->get(PeopleImportError::class);
        $peopleImportErrorTable::truncate();
        foreach ($array as $item){
            $peopleImportErrorTable -> insert($item);
        }
    }

    /**
     * Lấy row của excel
     *
     * @param $row
     * @return array
     */
    private function getRowFilExcel($row)
    {
        $rowFile = [
            'code' => strip_tags(isset($row[0]) ? $row[0] : ''), //Mã hồ sơ,
            'full_name' => strip_tags(isset($row[1]) ? $row[1] : ''), //Họ tên,
            'gender' => strip_tags(isset($row[2]) ? $row[2] : ''), //Giới tính
            'id_number' => strip_tags(isset($row[3]) ? $row[3] : ''), //CMND
            'id_license_date' => isset($row[4])&&$row[4] ? $row[4] : null, //Ngày cấp
            'people_id_license_place' => strip_tags(isset($row[5]) ? $row[5] : ''), //Nơi cấp
            'birth_day' => strip_tags(isset($row[6]) ? $row[6] : ''), //Ngày sinh
            'birth_month' => strip_tags(isset($row[7]) ? $row[7] : ''), //Tháng sinh
            'birth_year' => strip_tags(isset($row[8]) ? $row[8] : ''), //Năm Sinh
            'permanent_address' => strip_tags(isset($row[9]) ? $row[9] : ''), //Địa chỉ thường trú
            'temporary_address' => strip_tags(isset($row[10]) ? $row[10] : ''), //Địa chỉ tạm trú
            'birthplace' => strip_tags(isset($row[11]) ? $row[11] : ''), //Đăng ký khai sinh
            'hometown' => strip_tags(isset($row[12]) ? $row[12] : ''), //Quê quán
            'people_group' => strip_tags(isset($row[13]) ? $row[13] : ''), //Khu phố
            'people_quarter' => strip_tags(isset($row[14]) ? $row[14] : ''), //Tổ dân phố
            'ethnic' => strip_tags(isset($row[15]) ? $row[15] : ''), //Dân tộc
            'religion' => strip_tags(isset($row[16]) ? $row[16] : ''), //Tôn giáo
            'people_family' => strip_tags(isset($row[17]) ? $row[17] : ''), //Thành phần gia đình
            'educational_level' => strip_tags(isset($row[18]) ? $row[18] : ''), //Trình độ văn hoá

            'graduation_year' => strip_tags(isset($row[19]) ? $row[19] : ''), //Năm tốt nghiệp
            'specialized' => strip_tags(isset($row[20]) ? $row[20] : ''), //Chuyên ngành đào tạo
            'foreign_language' => strip_tags(isset($row[21]) ? $row[21] : ''), //Ngoại ngữ
            'union_join_date' => isset($row[22])&&$row[22] ? $row[22] : null, //Ngày vào đoàn
            'group_join_date' => isset($row[23])&&$row[23] ? $row[23] : null, //Ngày vào đảng

            'people_job' => strip_tags(isset($row[24]) ? $row[24] : ''), //Nghề nghiệp
            'elementary_school' => strip_tags(isset($row[25]) ? $row[25] : ''), //Trường cấp 1
            'middle_school' => strip_tags(isset($row[26]) ? $row[26] : ''), //Trường cấp 2
            'high_school' => strip_tags(isset($row[27]) ? $row[27] : ''), //Trường cấp 3
            'from_18_to_21' => strip_tags(isset($row[28]) ? $row[28] : ''), //Từ 18 -> 21
            'from_21_to_now' => strip_tags(isset($row[29]) ? $row[29] : ''), //Từ 21 -> now
            'full_name_dad' => strip_tags(isset($row[30]) ? $row[30] : ''), //Họ tên cha
            'birth_year_dad' => strip_tags(isset($row[31]) ? $row[31] : ''), //Năm sinh cha
            'job_dad' => strip_tags(isset($row[32]) ? $row[32] : ''), //Nghề nghiệp cha
            'before_30_04_dad' => strip_tags(isset($row[33]) ? $row[33] : ''), //Trước 30/04 cha
            'after_30_04_dad' => strip_tags(isset($row[34]) ? $row[34] : ''), //Sau 30/04 cha
            'current_dad' => strip_tags(isset($row[35]) ? $row[35] : ''), //Hiện tại cha
            'full_name_mom' => strip_tags(isset($row[36]) ? $row[36] : ''), //Họ tên mẹ
            'birth_year_mom' => strip_tags(isset($row[37]) ? $row[37] : ''), //Năm sinh mẹ
            'job_mom' => strip_tags(isset($row[38]) ? $row[38] : ''), //Nghề nghiệp mẹ
            'before_30_04_mom' => strip_tags(isset($row[39]) ? $row[39] : ''), //Trước 30/04 mẹ
            'after_30_04_mom' => strip_tags(isset($row[40]) ? $row[40] : ''), //Sau 30/04 mẹ
            'current_mom' => strip_tags(isset($row[41]) ? $row[41] : ''), //Hiện tại mẹ
            'info_brother_1' => strip_tags(isset($row[42]) ? $row[42] : ''), //Thông tin anh/em 1
            'info_brother_2' => strip_tags(isset($row[43]) ? $row[43] : ''), //Thông tin anh/em 2
            'info_brother_3' => strip_tags(isset($row[44]) ? $row[44] : ''), //Thông tin anh/em 3
            'info_brother_4' => strip_tags(isset($row[45]) ? $row[45] : ''), //Thông tin anh/em 4
            'info_brother_5' => strip_tags(isset($row[46]) ? $row[46] : ''), //Thông tin anh/em 5
            'info_brother_6' => strip_tags(isset($row[47]) ? $row[47] : ''), //Thông tin anh/em 6
            'full_name_couple' => strip_tags(isset($row[48]) ? $row[48] : ''), //Họ và tên vợ/chồng
            'birth_year_couple' => strip_tags(isset($row[49]) ? $row[49] : ''), //Năm sinh vợ/chồng
            'job_couple' => strip_tags(isset($row[50]) ? $row[50] : ''), //Nghê nghiệp vợ/chồng
            'info_child_1' => strip_tags(isset($row[51]) ? $row[51] : ''), //Con thứ 1
            'info_child_2' => strip_tags(isset($row[52]) ? $row[52] : ''), //Con thứ 2
        ];

        return $rowFile;
    }

    /**
     * Validate các trường của file import
     *
     * @param $input
     * @return string
     */
    private function validatePeople($input)
    {
        $error = '';

        if ($input['code'] == null || empty($input['code'])) {
            $error .= __('Mã hồ sơ không được trống') .';';
        }

        if ($input['code'] != null && !empty($input['code']) && strlen($input['code']) > 15) {
            $error .= __('Mã hồ sơ tối đa 15 kí tự') .';';
        }

        if ($input['code'] != null && !empty($input['code'])) {
            //Check unique code
            $checkUnique = $this->people->checkUniqueCode($input['code']);

            if ($checkUnique != null) {
                $error .= __('Mã hồ sơ không được trùng') .';';
            }
        }

        if ($input['full_name'] == null || empty($input['full_name'])) {
            $error .= __('Tên công dân không được trống') .';';
        }

        if ($input['full_name'] != null && !empty($input['full_name']) && strlen($input['full_name']) > 50) {
            $error .= __('Tên công dân tối đa 50 kí tự') .';';
        }

        if ($input['birth_day'] == null || empty($input['birth_day'])) {
            $error .= __('Ngày sinh không được trống') .';';
        }

        if ($input['birth_month'] == null || empty($input['birth_month'])) {
            $error .= __('Tháng sinh không được trống') .';';
        }

        if ($input['birth_year'] == null || empty($input['birth_year'])) {
            $error .= __('Năm sinh không được trống') .';';
        }

        if (checkdate(intval($input['birth_month']), intval($input['birth_day']), intval($input['birth_year'])) == false) {
            $error .= __('Ngày/tháng/năm sinh không đúng định dạng') .';';
        }

        if ($input['permanent_address'] == null || empty($input['permanent_address'])) {
            $error .= __('Địa chỉ thường trú không được trống') .';';
        }

        if ($input['permanent_address'] != null && !empty($input['permanent_address']) && strlen($input['permanent_address']) > 190) {
            $error .= __('Địa chỉ thường trú tối đa 190 kí tự') .';';
        }

        if ($input['temporary_address'] == null || empty($input['temporary_address'])) {
            $error .= __('Địa chỉ tạm trú không được trống') .';';
        }

        if ($input['temporary_address'] != null && !empty($input['temporary_address']) && strlen($input['temporary_address']) > 190) {
            $error .= __('Địa chỉ tạm trú tối đa 190 kí tự') .';';
        }

        if ($input['id_number'] == null || empty($input['id_number'])) {
            $error .= __('CMND/CCCD không được trống') .';';
        }

        if ($input['id_number'] != null && !empty($input['id_number'])) {
            if (strlen($input['id_number']) < 9) {
                $error .= __('CMND/CCCD tối thiểu 9 kí tự') .';';
            } else if (strlen($input['id_number']) > 12) {
                $error .= __('CMND/CCCD tối đa 12 kí tự') .';';
            }

            //Check unique CMND
            $checkUnique = $this->people->checkUniqueIdNumber($input['id_number']);

            if ($checkUnique != null) {
                $error .= __('CMND/CCCD đã tồn tại') .';';
            }
        }

        if ($input['id_license_date'] != null && !empty($input['id_license_date'])) {
            $checkFormatDate = $this->_validateDate($input['id_license_date']);

            if ($checkFormatDate == false) {
                $error .= __('Ngày cấp không đúng định dạng') . ';';
            }
        }

        if ($input['hometown'] == null || empty($input['hometown'])) {
            $error .= __('Quê quán không được trống') .';';
        }

        if ($input['ethnic'] == null || empty($input['ethnic'])) {
            $error .= __('Dân tộc không được trống') .';';
        }

        if ($input['religion'] == null || empty($input['religion'])) {
            $error .= __('Tôn giáo không được trống') .';';
        }

        if ($input['people_family'] == null || empty($input['people_family'])) {
            $error .= __('Thành phần gia đình không được trống') .';';
        }

        if ($input['elementary_school'] != null && !empty($input['elementary_school'])) {
            if (strlen($input['elementary_school']) < 5) {
                $error .= __('Tên trường cấp 1 tối thiểu 5 kí tự') .';';
            } else if (strlen($input['elementary_school']) > 50) {
                $error .= __('Tên trường cấp 1 tối đa 50 kí tự') .';';
            }
        }

        if ($input['middle_school'] != null && !empty($input['middle_school'])) {
            if (strlen($input['middle_school']) < 5) {
                $error .= __('Tên trường cấp 2 tối thiểu 5 kí tự') .';';
            } else if (strlen($input['middle_school']) > 50) {
                $error .= __('Tên trường cấp 2 tối đa 50 kí tự') .';';
            }
        }

        if ($input['high_school'] != null && !empty($input['high_school'])) {
            if (strlen($input['high_school']) < 5) {
                $error .= __('Tên trường cấp 3 tối thiểu 5 kí tự') .';';
            } else if (strlen($input['high_school']) > 50) {
                $error .= __('Tên trường cấp 3 tối đa 50 kí tự') .';';
            }
        }

        if ($input['full_name_dad'] != null && !empty($input['full_name_dad']) && strlen($input['full_name_dad']) > 50) {
            $error .= __('Họ tên cha tối đa 50 kí tự') .';';
        }

        return $error;
    }

    /**
     * Validate date
     *
     * @param $date
     * @param string $format
     * @return bool
     */
    private function _validateDate($date, $format = 'd/m/Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Export excel error
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelError($input=[])
    {
        $list = [];

        if (count($input['code']??[]) > 0) {
            foreach ($input['code'] as $k => $v) {
                $list [] = [
                    'code' => $v??'',
                    'full_name' => $input['full_name'][$k]??'',
                    'gender' => $input['gender'][$k]??'',
                    'id_number' => $input['id_number'][$k]??'',
                    'id_license_date' => $input['id_license_date'][$k]??'',
                    'people_id_license_place' => $input['people_id_license_place'][$k]??'',
                    'birth_day' => $input['birth_day'][$k]??'',
                    'birth_month' => $input['birth_month'][$k]??'',
                    'birth_year' => $input['birth_year'][$k]??'',
                    'permanent_address' => $input['permanent_address'][$k]??'',
                    'temporary_address' => $input['temporary_address'][$k]??'',
                    'birthplace' => $input['birthplace'][$k]??'',
                    'hometown' => $input['hometown'][$k]??'',
                    'people_group' => $input['people_group'][$k]??'',
                    'people_quarter' => $input['people_quarter'][$k]??'',
                    'ethnic' => $input['ethnic'][$k]??'',
                    'religion' => $input['religion'][$k]??'',
                    'people_family' => $input['people_family'][$k]??'',
                    'educational_level' => $input['educational_level'][$k]??'',
                    'group_join_date' => $input['group_join_date'][$k]??'',
                    'graduation_year' => $input['graduation_year'][$k]??'',
                    'specialized' => $input['specialized'][$k]??'',
                    'foreign_language' => $input['foreign_language'][$k]??'',
                    'union_join_date' => $input['union_join_date'][$k]??'',

                    'people_job' => $input['people_job'][$k]??'',
                    'elementary_school' => $input['elementary_school'][$k]??'',
                    'middle_school' => $input['middle_school'][$k]??'',
                    'high_school' => $input['high_school'][$k]??'',
                    'from_18_to_21' => $input['from_18_to_21'][$k]??'',
                    'from_21_to_now' => $input['from_21_to_now'][$k]??'',
                    'full_name_dad' => $input['full_name_dad'][$k]??'',
                    'birth_year_dad' => $input['birth_year_dad'][$k]??'',
                    'job_dad' => $input['job_dad'][$k]??'',
                    'before_30_04_dad' => $input['before_30_04_dad'][$k]??'',
                    'after_30_04_dad' => $input['after_30_04_dad'][$k]??'',
                    'current_dad' => $input['current_dad'][$k]??'',
                    'full_name_mom' => $input['full_name_mom'][$k]??'',
                    'birth_year_mom' => $input['birth_year_mom'][$k]??'',
                    'job_mom' => $input['job_mom'][$k]??'',
                    'before_30_04_mom' => $input['before_30_04_mom'][$k]??'',
                    'after_30_04_mom' => $input['after_30_04_mom'][$k]??'',
                    'current_mom' => $input['current_mom'][$k]??'',
                    'info_brother_1' => $input['info_brother_1'][$k]??'',
                    'info_brother_2' => $input['info_brother_2'][$k]??'',
                    'info_brother_3' => $input['info_brother_3'][$k]??'',
                    'info_brother_4' => $input['info_brother_4'][$k]??'',
                    'info_brother_5' => $input['info_brother_5'][$k]??'',
                    'info_brother_6' => $input['info_brother_6'][$k]??'',
                    'full_name_couple' => $input['full_name_couple'][$k]??'',
                    'birth_year_couple' => $input['birth_year_couple'][$k]??'',
                    'job_couple' => $input['job_couple'][$k]??'',
                    'info_child_1' => $input['info_child_1'][$k]??'',
                    'info_child_2' => $input['info_child_2'][$k]??'',
                    'error' => $input['error'][$k]??''
                ];
            }
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        //Lấy dữ liệu export
        $peopleImportErrorTable = app()->get(PeopleImportError::class);
        $list = $peopleImportErrorTable->get()->toArray();
        $data = [
            'LIST' => $list
        ];

        return Excel::download(new ExportErrorPeople($data), 'export-error-people.xlsx');
    }

    /**
     * Chuyển dạng file để có thể up được lên Azure.
     *
     * @param $file_path
     *
     * @return UploadedFile
     */
    public function convertFile($file_path)
    {
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        return new UploadedFile(
            $file_path,
            $file_path,
            $fileInfo->file($file_path),
            filesize($file_path),
            0,
            false
        );
    }

    /**
 * Export excel error without download
 *
 * @param $input
 * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
 */
    public function exportError($list)
    {
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        //Lấy dữ liệu export
        $data = [
            'LIST' => $list
        ];


        $pathView = 'People::people.error.table-error';
        $filePatch = "export-error-people.xlsx";
        $a = Excel::store(new ExportErrorPeople($data, $pathView), $filePatch);

        $uploadRepo = app()->get(UploadRepo::class);
        $file = $this->convertFile( storage_path('app/export-error-people.xlsx') );
        //dd($file);

        $upload = $uploadRepo->uploadImage([
            'file' => $file,
            'link' => '_people.'
        ]);

        return $upload['file'];
    }

    /**
     * Export excel error without download
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadExcelError($list)
    {

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        //Lấy dữ liệu export
        $data = [
            'LIST' => $list
        ];


        $pathView = 'People::people.error.table-error';
        $filePatch = "export-error-people.xlsx";
        return Excel::download(new ExportErrorPeople($data, $pathView),'export-error-people.xlsx');
        $a = Excel::store(new ExportErrorPeople($data), $filePatch);

        $uploadRepo = app()->get(UploadRepo::class);
        $file = $this->convertFile( storage_path('app/export-error-people.xlsx') );
        //dd($file);

        $upload = $uploadRepo->uploadImage([
            'file' => $file,
            'link' => '_people.'
        ]);

        return $upload['file'];
    }

    /**
     * Chọn công dân
     *
     * @param $input
     * @return mixed|void
     */
    public function choosePeople($input)
    {
        $arrCheck = [];

        //Lấy session đã chọn
        if (session()->get('people_choose')) {
            $arrCheck = session()->get('people_choose');
        }

        if (count($input['arrayPeopleId']) > 0) {
            foreach ($input['arrayPeopleId'] as $v) {
                //Push công dân mới chọn vào
                $arrCheck[$v] = [
                    "people_id" => $v,
                ];
            }
        }

        //Forget session chọn
        session()->forget('people_choose');
        //Push session chọn mới
        session()->put('people_choose', $arrCheck);

        return [
            'count_choose' => count($arrCheck),
        ];
    }

    /**
     * Bỏ chọn công dân
     *
     * @param $input
     * @return mixed|void
     */
    public function unChoosePeople($input)
    {
        $arrCheck = [];

        //Lấy session đã chọn
        if (session()->get('people_choose')) {
            $arrCheck = session()->get('people_choose');
        }

        if (count($input['arrayPeopleId']) > 0) {
            foreach ($input['arrayPeopleId'] as $v) {
                unset($arrCheck[$v]);
            }
        }

        //Forget session chọn
        session()->forget('people_choose');
        //Push session chọn mới
        session()->put('people_choose', $arrCheck);

        return [
            'count_choose' => count($arrCheck)
        ];
    }
}
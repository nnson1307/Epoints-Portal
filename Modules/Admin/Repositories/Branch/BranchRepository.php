<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:40 AM
 */

namespace Modules\Admin\Repositories\Branch;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\BranchImageTable;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\WarehouseTable;


class BranchRepository implements BranchRepositoryInterface
{
    protected $branches;
    protected $branch_image;
    protected $customerGroup;
    protected $timestamps = true;

    public function __construct(
        BranchTable $branch,
        BranchImageTable $branch_image,
        CustomerGroupTable $customerGroup
    ) {
        $this->branches = $branch;
        $this->branch_image = $branch_image;
        $this->customerGroup = $customerGroup;
    }

    /**
     * Lấy danh sách Branches
     */
    public function list(array $filters = [])
    {
        return $this->branches->getList($filters);
    }

    /**
     * Thêm chi nhánh
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function add($input)
    {
        try {
            DB::beginTransaction();
            $branch_name = $input['branch_name'];
            //Check tên chi nhánh tồn tại chưa
            $test = $this->branches->testName(str_slug($branch_name), '0');

            if ($test != null) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Tên chi nhánh đã tồn tại'
                ]);
            }

            if ($input['is_representative'] == 1) {
                //Check chi nhánh chính
                $checkRepresentative = $this->branches->checkRepresentative('');

                if ($checkRepresentative != null) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Đã tồn tại chi nhánh chính'
                    ]);
                }
            }

            $data = [
                'branch_name' => $branch_name,
                'slug'=>str_slug($branch_name),
                'representative_code' => $input['representative_code'],
                'email' => $input['email'],
                'hot_line' => $input['hot_line'],
                'address' => $input['address'],
                'phone' => $input['phone'],
                'is_representative' => $input['is_representative'],
                'description' => $input['description'],
                'provinceid' => $input['provinceid'],
                'districtid' => $input['districtid'],
                'created_by' => Auth::id(),
                'updated_by'=> Auth::id(),
                'is_actived' => 1,
            ];
            $id = $this->branches->add($data);

            $branchCode = 'CN_' . date('dmY') . sprintf("%02d", $id);
            //Update branch code
            $this->branches->edit([
                'branch_code' => $branchCode
            ], $id);

            if (isset($input['img']) &&  $input['img'] != "") {
                $aData = array_chunk($input['img'], 1, false);
                foreach ($aData as $key => $value) {
                    $data = [
                        'branch_id' => $id,
                        'name' => $value[0],
                        'created_by' => Auth::id()
                    ];
                    $this->branch_image->add($data);
                }
            }

            $warehouseName = __('Kho bán lẻ chi nhánh') . ' ' . $branch_name;
            //Tạo 1 kho bán lẻ cho chi nhánh
            $mWarehouse = new WarehouseTable();
            $mWarehouse->add([
                'name' => $warehouseName,
                'slug' => str_slug($warehouseName),
                'branch_id' => $id,
                'province_id' => $input['provinceid'],
                'district_id' => $input['districtid'],
                'address' => $input['address'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                'is_retail' => 1
            ]);



            DB::commit();
            return response()->json([
                'success' => 1,
                'message' => 'Thêm chi nhánh thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => 0,
                'message' => 'Thêm thất bại',
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    public function getBranch(array $listId = [])
    {

        $array = array();
        foreach ($this->branches->getBranch($listId) as $item) {
            $array[$item['branch_id']] = $item['branch_name'];

        }
        return $array;
    }

    public function customerGroup()
    {
        return $this->customerGroup->getOption();
    }

    public function remove($id)
    {
        $this->branches->remove($id);
    }

    public function edit($input)
    {
        try {
            DB::beginTransaction();

            $id = $input['branch_id'];
            $name = $input['branch_name'];
            //Check tên chi nhánh tồn tại chưa
            $test = $this->branches->testName(str_slug($name), $id);

            if ($test != null) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Tên chi nhánh đã tồn tại'
                ]);
            }

            if ($input['is_representative'] == 1) {
                //Check chi nhánh chính
                $checkRepresentative = $this->branches->checkRepresentative($id);

                if ($checkRepresentative != null) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Đã tồn tại chi nhánh chính'
                    ]);
                }
            }

            $data = [
                'branch_name' => $input['branch_name'],
                'slug'=>str_slug($input['branch_name']),
                'representative_code' => $input['representative_code'],
                'email' => $input['email'],
                'hot_line' => $input['hot_line'],
                'address' => $input['address'],
                'phone' => $input['phone'],
                'is_representative' => $input['is_representative'],
                'description' => $input['description'],
                'provinceid' => $input['provinceid'],
                'districtid' => $input['districtid'],
                'is_actived'=> $input['is_actived'] ,
                'updated_by'=>Auth::id(),
                'latitude' => $input['latitude'],
                'longitude' => $input['longitude']
            ];

            $this->branches->edit($data, $id);
            //Xóa ảnh cũ
            if (isset($input['img_delete']) &&   $input['img_delete'] != null) {
                $aData_image = $input['img_delete'];

            } else {
                $aData_image = [];
            }

            $list_image = $this->branch_image->getItem($id);
            if (count($list_image) > 0) {
                $name = [];
                foreach ($list_image as $ima_key => $ima_val) {
                    $name[] = $ima_val['name'];

                }

                $cut = array_diff($name, $aData_image);
                foreach ($cut as $i_cut) {
                    $this->branch_image->remove($i_cut);
                }
            }
            //Thêm hình ảnh mới
            if (isset($input['img']) && $input['img'] != "") {
                $aData = array_chunk($input['img'], 1, false);

                foreach ($aData as $key => $value) {
                    $data_img = [
                        'branch_id' => $id,
                        'name' => $value[0],
                        'created_by' => Auth::id()
                    ];
                    $this->branch_image->add($data_img);
                }
            }
            DB::commit();
            return response()->json([
                'success' => 1,
                'message' => 'Chỉnh sửa thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => 0,
                'message' => 'Chỉnh sửa thất bại',
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    public function getItem($id)
    {
        return $this->branches->getItem($id);
    }

    public function getNameBranch($id)
    {
        return $this->branches->getNameBranch($id);
    }

    public function testName($name, $id)
    {
        return $this->branches->testName($name, $id);
    }

    //search where in branch.
    public function searchWhereIn(array $branch)
    {
        return $this->branches->searchWhereIn($branch);
    }

    public function getBranchOption()
    {
        $array = array();
        foreach ($this->branches->getBranchOption() as $item) {
            $array[$item['branch_id']] = $item['branch_name'];
        }
        return $array;
    }

    //Move image từ folder temp qua folder chính
    private function transferTempfileToAdminfileDrop($path, $imgName)
    {

        $imgName = str_replace("temp_upload/", "", $imgName);
        Storage::disk('public')->makeDirectory(BRANCH_UPLOADS_PATH . date('Ymd'));
        $new_path = BRANCH_UPLOADS_PATH . date('Ymd') . '/' . $imgName;
        Storage::disk('public')->move('temp_upload/' . $path, $new_path);
        return $new_path;
    }

    public function changeStatus($data,$id) {
        return $this->branches->edit($data,$id);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/07/2022
 * Time: 14:01
 */

namespace Modules\Team\Repositories\Company;


use Modules\Team\Models\CompanyTable;

class CompanyRepo implements CompanyRepoInterface
{
    protected $company;

    public function __construct(
        CompanyTable $company
    ) {
        $this->company = $company;
    }

    /**
     * Danh sách nhóm
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->company->getList($filters);

        return [
            'list' => $list
        ];
    }

    /**
     * Thêm công ty
     *
     * @param $input
     * @return array|mixed
     */
    public function store($input)
    {
        try {
            //Thêm công ty
            $idCompany = $this->company->add([
                'company_name' => $input['company_name'],
                'description' => $input['description']
            ]);

            //Cập nhật mã công ty
            $companyCode = 'CPN_' . date('dmY') . sprintf("%02d", $idCompany);
            $this->company->edit([
                'company_code' => $companyCode
            ], $idCompany);

            return ([
                'error' => false,
                'message' => __('Thêm thành công')
            ]);
        } catch (\Exception $e) {
            return ([
                'error' => true,
                'message' => __('Thêm thất bại')
            ]);
        }
    }

    /**
     * Chỉnh sửa công ty
     *
     * @param $id
     * @return array|mixed
     */
    public function getDataEdit($id)
    {
        //Lấy thông tin công ty
        $info = $this->company->getInfo($id);

        return [
            'item' => $info
        ];
    }

    /**
     * Chỉnh sửa công ty
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            //Thêm công ty
            $this->company->edit([
                'company_name' => $input['company_name'],
                'is_actived' => $input['is_actived'],
                'description' => $input['description']
            ], $input['company_id']);

            return ([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            return ([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại')
            ]);
        }
    }

    /**
     * Xoá công ty
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            $this->company->edit([
                'is_deleted' => 1
            ], $input['company_id']);

            return [
                'error' => false,
                'message' => __('Xóa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xóa thất bại')
            ];
        }
    }

    /**
     * Chỉnh sửa trạng thái
     *
     * @param $input
     * @return array|mixed
     */
    public function changeStatus($input)
    {
        try {
            //Update pipeline category
            $this->company->edit([
                'is_actived' => $input['is_actived']
            ], $input['company_id']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ];
        }
    }
}
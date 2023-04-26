<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 18/11/2021
 * Time: 14:07
 */

namespace Modules\Config\Repositories\ConfigCustomerParameter;


use Modules\Config\Models\ConfigCustomerParameterTable;

class ConfigCustomerParameterRepo implements ConfigCustomerParameterRepoInterface
{
    protected $configParameter;

    public function __construct(
        ConfigCustomerParameterTable $configParameter
    )
    {
        $this->configParameter = $configParameter;
    }

    /**
     * Danh sách tham số
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        //Lấy ds tham số
        $list = $this->configParameter->getList($filters);

        return [
            'list' => $list
        ];
    }

    /**
     * Thêm tham số
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        try {
            //Thêm tham số
            $this->configParameter->add([
                'parameter_name' => $input['parameter_name'],
                'content' => $input['content'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            return response()->json([
                'error' => false,
                'message' => __('Thêm mới thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy data view chỉnh sửa
     *
     * @param $customerParameterId
     * @return array|mixed
     */
    public function getDataEdit($customerParameterId)
    {
        $getInfo = $this->configParameter->getInfo($customerParameterId);

        return [
            'item' => $getInfo
        ];
    }

    /**
     * Chỉnh sửa tham số
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        try {
            //Chỉnh sửa tham số
            $this->configParameter->edit([
                'parameter_name' => $input['parameter_name'],
                'content' => $input['content'],
                'updated_by' => Auth()->id()
            ], $input['parameter_id']);

            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xoá tham số
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($input)
    {
        try {
            //Chỉnh sửa tham số
            $this->configParameter->edit([
                'is_deleted' => 1,
            ], $input['parameter_id']);

            return response()->json([
                'error' => false,
                'message' => __('Xoá thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Xoá thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }
}
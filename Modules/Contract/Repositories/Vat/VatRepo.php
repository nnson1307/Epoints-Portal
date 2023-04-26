<?php

namespace Modules\Contract\Repositories\Vat;

use Modules\Contract\Models\VatTable;

class VatRepo implements VatRepoInterface
{
    protected $vat;

    public function __construct(
        VatTable $vat
    ) {
        $this->vat = $vat;
    }

    /**
     * Lấy data danh sách VAT
     *
     * @param array $filter
     * @return array
     */
    public function getList(array $filter = [])
    {
        //Lấy danh sách VAT
        $list = $this->vat->getList();

        return [
            'list' => $list
        ];
    }

    /**
     * Thêm VAT
     *
     * @param $input
     * @return array
     */
    public function store($input)
    {
        try {
            if ($input['vat'] > 100) {
                return [
                    'error' => true,
                    'message' => __('VAT không hợp lệ'),
                ];
            }

            //Thêm VAT
            $idVat = $this->vat->add([
                'vat' => $input['vat'],
                'description' => $input['description'],
                'type' => 'system',
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            return [
                'error' => false,
                'message' => __('Thêm thành công'),
                'vat_id' => $idVat,
                'vat' => floatval($input['vat'])
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm thất bại'),
                '_message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ];
        }
    }

    /**
     * Lấy data view edit
     *
     * @param $input
     * @return array
     */
    public function getDataViewEdit($input)
    {
        //Lấy data VAT
        $getInfo = $this->vat->getInfo($input['vat_id']);

        return [
            'item' => $getInfo
        ];
    }

    /**
     * Chỉnh sửa VAT
     *
     * @param $input
     * @return array
     */
    public function update($input)
    {
        try {
            if ($input['vat'] > 100) {
                return [
                    'error' => true,
                    'message' => __('VAT không hợp lệ'),
                ];
            }

            //Chỉnh sửa VAT
            $this->vat->edit([
                'vat' => $input['vat'],
                'description' => $input['description'],
                'updated_by' => Auth()->id(),
                'is_actived' => $input['is_actived']
            ], $input['vat_id']);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ];
        }
    }

    /**
     * Thay đổi trạng thái VAT
     *
     * @param $input
     * @return array
     */
    public function changeStatus($input)
    {
        try {
            //Chỉnh sửa VAT
            $this->vat->edit([
                'updated_by' => Auth()->id(),
                'is_actived' => $input['is_actived']
            ], $input['vat_id']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại'),
                '_message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ];
        }
    }
}
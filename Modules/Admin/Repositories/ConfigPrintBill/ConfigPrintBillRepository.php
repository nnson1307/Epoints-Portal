<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/1/2019
 * Time: 12:00 PM
 */

namespace Modules\Admin\Repositories\ConfigPrintBill;

use Modules\Admin\Models\ConfigPrintBillTable;
use Modules\Admin\Models\PrintBillDeviceTable;

class ConfigPrintBillRepository implements ConfigPrintBillRepositoryInterface
{
    protected $configPrintBill;
    protected $configPrintBillDevice;

    public function __construct(ConfigPrintBillTable $configPrintBill, PrintBillDeviceTable $configPrintBillDevice)
    {
        $this->configPrintBill = $configPrintBill;
        $this->configPrintBillDevice = $configPrintBillDevice;
    }

    public function getItem($id)
    {
        return $this->configPrintBill->getItem($id);
    }

    public function edit(array $data, $id)
    {
        return $this->configPrintBill->edit($data, $id);
    }

    public function getPrinters($filters)
    {
        return $this->configPrintBillDevice->getPrinters($filters);
    }

    public function storePrinter(array $all)
    {
        try{
            $result = $this->configPrintBillDevice->createPrinter($all);
            if($all['is_default'] == 1){
                $this->configPrintBillDevice->updatePrinterDefault($all['branch_id'], $result['print_bill_device_id'], ['is_default' => 0]);
            }
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }
        return $result;
    }

    public function removePrinter(array $all)
    {
        return $this->configPrintBillDevice->removePrinter($all['print_bill_device_id']);
    }

    public function updatePrinterStatus(array $all)
    {
        return $this->configPrintBillDevice->updatePrinter($all['print_bill_device_id'], ['is_actived' => $all['is_actived']]);
    }

    public function getPrinter($print_bill_device_id)
    {
        return $this->configPrintBillDevice->getPrinter($print_bill_device_id);
    }

    public function updatePrinter(array $all)
    {
        $result = $this->configPrintBillDevice->updatePrinter($all['print_bill_device_id'],
            [
                'branch_id' => $all['branch_id'],
                'printer_name' => $all['printer_name'],
                'printer_ip' => $all['printer_ip'],
                'printer_port' => $all['printer_port'],
                'template' => $all['template'],
                'template_width' => $all['template_width'],
                'is_actived' => $all['is_actived'],
                'is_default' => $all['is_default']
            ]);
        if($all['is_default'] == 1){
            $this->configPrintBillDevice->updatePrinterDefault($all['branch_id'], $all['print_bill_device_id'], ['is_default' => 0]);
        }

        return $result;
    }

    /**
     * Cập nhật printer mặc định
     * @param array $all
     * @return mixed
     */
    public function updatePrinterDefault(array $all)
    {
        $printer = $this->getPrinter($all['print_bill_device_id']);
        if(empty($printer)){
            return response()->json([
                'error' => 1,
                'message' => __("Máy in không tồn tại")
            ]);
        }
        $result = $this->configPrintBillDevice->updatePrinter($all['print_bill_device_id'], ['is_default' => $all['is_default']]);
        if($all['is_default'] == 1){
            $this->configPrintBillDevice->updatePrinterDefault($printer['branch_id'], $all['print_bill_device_id'], ['is_default' => 0]);
        }
        return response()->json([
            'error' => 0,
            'message' => 'Update success'
        ]);
    }
}
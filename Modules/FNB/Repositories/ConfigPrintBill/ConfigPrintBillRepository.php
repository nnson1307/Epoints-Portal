<?php


namespace Modules\FNB\Repositories\ConfigPrintBill;


use Modules\FNB\Models\ConfigPrintBillTable;
use Modules\FNB\Models\PrintBillDeviceTable;

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
}
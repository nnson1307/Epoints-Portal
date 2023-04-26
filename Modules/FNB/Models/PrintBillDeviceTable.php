<?php


namespace Modules\FNB\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PrintBillDeviceTable extends Model
{
    protected $table = 'print_bill_devices';
    protected $primaryKey = 'print_bill_device_id';

    protected $fillable = ["print_bill_device_id", "branch_id", "printer_name", "printer_ip", "printer_port", "template", "template_width", "is_actived", "is_deleted",
        "is_default", "created_by", "updated_by", "created_at", "updated_at"];

    public function getPrinters($filter)
    {
        $page = (int)($filter['page'] ?? 1);

        $select = $this->select(
                        "{$this->table}.branch_id",
                        "b.branch_name",
                        "{$this->table}.print_bill_device_id",
                        "{$this->table}.printer_name",
                        "{$this->table}.printer_ip",
                        "{$this->table}.printer_port",
                        "{$this->table}.template",
                        "{$this->table}.template_width",
                        "{$this->table}.is_actived",
                        "{$this->table}.is_default",
                    )
                    ->leftJoin("branches as b", "b.branch_id", "=",  "{$this->table}.branch_id")
                    ->where("{$this->table}.is_deleted", 0);

        if(isset($filter['search'])){
            $select->where("{$this->table}.printer_name", "LIKE", "%{$filter['search']}%");
        }
        if(isset($filter['is_actived'])){
            $select->where("{$this->table}.is_actived", "=", $filter['is_actived']);
        }

        return $select->paginate(PAGING_ITEM_PER_PAGE, $columns = ['*'], $pageName = 'page', $page);
    }

    public function createPrinter(array $all)
    {
        $data = [
            "branch_id" => $all["branch_id"] ?? null,
            "printer_name" => $all["printer_name"] ?? null,
            "printer_ip" => $all["printer_ip"] ?? null,
            "printer_port" => $all["printer_port"] ?? null,
            "template" => $all["template"] ?? null,
            "template_width" => $all["template_width"] ?? null,
            "is_default" => $all["is_default"] ?? 0,
            "is_actived" => 1,
            "is_deleted" => 0,
            "created_by" => $all['user_id'] ?? 0,
            "updated_by" => $all['user_id'] ?? 0,
            "created_at" => Carbon::now(),
            "updated_at" =>  Carbon::now(),
        ];
        return $this->create($data);
    }

    public function removePrinter($print_bill_device_id)
    {
        return $this->where($this->primaryKey, $print_bill_device_id)->update(['is_deleted'=>1]);
    }

    public function updatePrinter($print_bill_device_id, array $array)
    {
        return $this->where($this->primaryKey, $print_bill_device_id)->update($array);
    }

    public function getPrinter($print_bill_device_id)
    {
        return $this->select(
                "{$this->table}.print_bill_device_id",
                "{$this->table}.branch_id",
                "{$this->table}.printer_name",
                "{$this->table}.printer_ip",
                "{$this->table}.printer_port",
                "{$this->table}.template",
                "{$this->table}.template_width",
                "{$this->table}.is_actived",
                "{$this->table}.is_default",
             )
            ->where("{$this->table}.{$this->primaryKey}", $print_bill_device_id)
            ->where("{$this->table}.is_deleted", 0)
            ->first();
    }

    public function updatePrinterDefault($branch_id, $print_bill_device_id, array $array)
    {
        return $this->where($this->primaryKey, "<>", $print_bill_device_id)
                    ->where("{$this->table}.branch_id", $branch_id)
                    ->update($array);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/20/2018
 * Time: 10:08 AM
 */

namespace Modules\Admin\Repositories\OrderStatus;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Carbon\Carbon;
use Modules\Admin\Models\OrderStatusTable;


class OrderStatusRepository implements OrderStatusRepositoryInterface
{
    protected $orderstatus;
    protected $timestamps=true;

    public function __construct(OrderStatusTable $orderstatus)
    {
        $this->orderstatus=$orderstatus;
    }
    /**
     * Lấy danh sách oder status
     */
    public function list(array $filters=[]){
        return $this->orderstatus->getList($filters);
    }

    /**
     * Add  Oder Status
     */
    public function add(array $data)
    {
        return $this->orderstatus->add($data);
    }
    /**
     * Remove  Oder Status
     */
    public function remove($id)
    {
        $this->orderstatus->remove($id);
    }
    /**
     * Edit  Oder Status
     */
    public function edit(array $data, $id)
    {
        try{
            if($this->orderstatus->edit($data,$id)==false) throw new \Exception();
            return $id;
        }catch (\Exception $e){
            $e->getMessage();
        }
        return false;
    }
    public function getEdit($id){
        return $this->orderstatus->getEdit($id);
    }

    public function exportExcel(array $array,$title)
    {

        $store = $this->orderstatus->exportExecl($array,$title);
        $table_title=$title;
        $oExcel= WriterFactory::create(Type::XLSX);
        $oExcel->openToBrowser("order-status.xlsx");
        $oExcel->addRowWithStyle($table_title,(new StyleBuilder())->setFontBold()->setFontSize(16)->build());
        foreach ($store as $sheet)
        {
            if (!empty($sheet->created_at)) {
                $sheet->created_at = Carbon::parse($sheet->created_at)->format('d-m-Y');
            }
            if (!empty($sheet->updated_at)) {
                $sheet->updated_at = Carbon::parse($sheet->updated_at)->format('d-m-Y');
            }
            if (!empty($sheet->is_active))
            {
                if($sheet->is_active==1)
                {
                    $sheet->is_active="Đang hoạt động";
                }else{
                    $sheet->is_active="Tạm ngưng";
                }
            }
            $oExcel->addRow(get_object_vars($sheet));
        }
        $oExcel->close();
    }

    public function import(array $data)
    {
        return $this->orderstatus->add($data);
    }


}
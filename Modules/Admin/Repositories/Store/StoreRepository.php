<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Admin\Repositories\Store;

use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Admin\Models\StoreTable;


class StoreRepository implements StoreRepositoryInterface
{
    protected $stores;
    protected $excel;
    protected $timestamps=true;

    public function __construct(StoreTable $stores,\Maatwebsite\Excel\Exporter $excel)
    {
        $this->stores=$stores;
        $this->excel=$excel;
    }

    /**
     * Lấy danh sách Store
     */
    public function list(array $filters=[])
    {
        return $this->stores->getList($filters);
    }
    /**
     * Add Store
     */
    public function add(array $data){
        return $this->stores->add($data);
    }
    /**
     * Delete Store
     */
    public function remove($id)
    {
        $this->stores->remove($id);
    }
    /**
     * Edit Store
     */
    public function edit(array $data,$id)
    {

       return $this->stores->edit($data,$id);
    }
    public function getItem($id)
    {
        // TODO: Implement getEdit() method.
        return $this->stores->getItem($id);
    }

    public function getStoreOption()
    {
        // TODO: Implement getStoreOption() method.
        $array = array()  ;
        foreach ($this->stores->getStoreOption() as $item){
            $array[$item['store_id']]  =  $item['store_name'] ;
        }
        return $array;
    }

    public function exportExcel(array $array,$title)
    {

        $store = $this->stores->exportExecl($array);
        $table_title=$title;
        $oExcel= WriterFactory::create(Type::XLSX);
        $oExcel->openToBrowser("store.xlsx");
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

    public function uploadExcel(Request $request)
    {
        $file = $request->file("file");
        if(isset($file))
        {
            $des_file='uploads/admin/store' . basename($file->getClientOriginalName());
            $excelFileType=$file->getClientOriginalExtension();
            $title=["store_id",'store_name','address','created_at'];
            if($excelFileType !="xlsx"){
                return redirect()->back()->with("error","File Excel không đúng");
            }
            else{
                move_uploaded_file($file->getPathname(),$des_file);
                $this->stores->importExcel($des_file,$title);
            }
            return redirect()->back();
        }
    }
}
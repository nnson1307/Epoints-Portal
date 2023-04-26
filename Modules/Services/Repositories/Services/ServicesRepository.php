<?php
/**
 * Services repository
 *
 * @author ledangsinh
 * @since march 28, 2018
 */

namespace Modules\Services\Repositories\Services;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Services\Models\ServicesTable;
use Modules\Services\Models\ServiceTimeTable;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;

class ServicesRepository implements ServicesRepositoryInterface
{

    /**
     * @var ServicesTable
     */
    protected $service;
    protected $serviceTime;
    protected $timestamps = true;

    public function __construct(ServicesTable $service, ServiceTimeTable $serviceTime)
    {
        $this->service = $service;
        $this->serviceTime = $serviceTime;
    }

    /**
     * Lấy danh sách services
     */
    public function list(array $filters = [])
    {
        return $this->service->getList($filters);
    }

    /**
     * Thêm services
     */
    public function add(array $data)
    {
        return $this->service->add($data);
    }

    /**
     * Xóa services
     */
    public function remove($id)
    {
        return $this->service->remove($id);
    }

    /**
     * Sửa services
     */
    public function edit(array $data, $id)
    {
        return $this->service->edit($data, $id);
    }

    /**
     * Get item
     */
    public function getItem($id)
    {
        return $this->service->getItem($id);
    }

    /**
     * Export excel
     */
    public function exportExcel(array $array, $title)
    {
        $services = $this->service->exportExcel($array);

        $oExcel = WriterFactory::create(Type::XLSX);
        $oExcel->openToBrowser("service.xlsx");
        $oExcel->addRowWithStyle($title, (new StyleBuilder())->setFontBold()->setFontSize(15)->build());

        foreach ($services as $sheet) {
            if (!empty($sheet->created_at)) {
                $sheet->created_at = Carbon::parse($sheet->created_at)->format('d-m-Y');
            }
            if (!empty($sheet->updated_at)) {
                $sheet->updated_at = Carbon::parse($sheet->updated_at)->format('d-m-Y');
            }
            if (!empty($sheet->is_active)) {
                if ($sheet->is_active === 1) {
                    $sheet->is_active = "Hoạt động";
                } else {
                    $sheet->is_active = "Tạm ngưng";
                }
            }
            if (!empty($sheet->is_delete)) {
                if ($sheet->is_delete === 1) {
                    $sheet->is_delete = "Đã xóa";
                } else {
                    $sheet->is_delete = "";
                }
            }
            $oExcel->addRow(get_object_vars($sheet));
        }
        $oExcel->close();
    }

    public function importExcelService(Request $request)
    {
        $fileExcel = $request->file("excelFile");
        if (isset($fileExcel)) {
            $desFile = 'uploads/services/services/excel/' . basename($fileExcel->getClientOriginalName());
            $typeFileExcel = $fileExcel->getClientOriginalExtension();
            $title = ["service_name", "service_code", "time", "description", "detail", "is_active"];
            if ($typeFileExcel = "xlsx") {
                move_uploaded_file($fileExcel->getPathname(), $desFile);
                $this->service->importExcel($desFile, $title);
            }
            return redirect()->route('services');
        }
    }
}
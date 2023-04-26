<?php
/**
 * Created by PhpStorm.
 * User: Sinh
 * Date: 3/26/2018
 */

namespace Modules\Admin\Repositories\MemberLevelVerb;

use Box\Spout\Writer\WriterFactory;
use Carbon\Carbon;
use Mockery\Exception;
use Modules\Admin\Models\MemberLevelVerbTable;
use Modules\Admin\Models\MemberLevelTable;
use Box\Spout\Common\Type;

class MemberLevelVerbRepository implements MemberLevelVerbRepositoryInterface
{
    /**
     * @var MemberLevelVerbTable
     */
    protected $memberLevelVerb;
    protected $memberLevel;
    protected $timestamps = true;

    public function __construct(MemberLevelVerbTable $memberLevelVerb, MemberLevelTable $memberLevel)
    {
        $this->memberLevelVerb = $memberLevelVerb;
        $this->memberLevel = $memberLevel;
    }

    /**
     * Get list member level verb
     */
    public function list(array $filters = [])
    {
        return $this->memberLevelVerb->getList($filters);
    }

    /**
     * Get item member level verb
     */
    public function getItem($id)
    {
        return $this->memberLevelVerb->getItem($id);
    }

    /**
     * Add member level verb
     */
    public function add(array $data)
    {
        return $this->memberLevelVerb->add($data);
    }

    /**
     * Remove member level verb
     */
    public function remove($id)
    {
        return $this->memberLevelVerb->remove($id);
    }

    /**
     * Edit member level verb
     */
    public function edit(array $data, $id)
    {
        try {
            if ($this->memberLevelVerb->edit($data, $id) === false) {
                throw new \Exception();
            }
        } catch (\Exception $exception) {
            $exception->getMessage();
        }
        return false;
    }

    /*
     * Export excel member level verb
     */
    public function exportExcel(array $array, $title)
    {
        $memberLevelVerb = $this->memberLevelVerb->exportExcel($array);
        dd($memberLevelVerb);
        $oExcel = WriterFactory::create(Type::XLSX);
        $oExcel->openToBrowser("memberlevelverb.xlsx");
        $oExcel->addRowWithStyle($title, (new StyleBuilder())->setFontBold()->setFontSize(15)->build());

        foreach ($memberLevelVerb as $sheet) {
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

}
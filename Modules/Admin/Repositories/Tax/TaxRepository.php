<?php
namespace Modules\Admin\Repositories\Tax;

use Modules\Admin\Models\TaxTable;


class TaxRepository implements TaxRepositoryInterface
{
    protected $tax;
    protected $excel;
    protected $timestamps=true;

    public function __construct(TaxTable $stores,\Maatwebsite\Excel\Exporter $excel)
    {
        $this->tax=$stores;
        $this->excel=$excel;
    }

    /**
     * Lấy danh sách thuế
     */
    public function list(array $filters=[])
    {
        return $this->tax->getList($filters);
    }
    /**
     * Thêm loại thuế
     */
    public function add(array $data){
        return $this->tax->add($data);
    }
    /**
     * Delete Store
     */
    public function remove($id)
    {
        $this->tax->remove($id);
    }
    /**
     * Sửa loại thuế
     */
    public function edit(array $data,$id)
    {
        try{
            if($this->tax->edit($data,$id)==false) throw new \Exception();
                return $id;
        }catch (\Exception $e)
        {
            $e->getMessage();
        }
        return false;
    }
    public function getItem($id)
    {
        return $this->tax->getItem($id);
    }

    public function export()
    {
        return $this->excel->export(new Export);
    }
}
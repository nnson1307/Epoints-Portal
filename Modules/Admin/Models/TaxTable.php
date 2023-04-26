<?php
namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Illuminate\Support\Facades\DB;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

/**
 * @author thanhlong
 * @since April 5, 2018
 */

class TaxTable extends Model
{
    use ListTableTrait;
    protected $table="tax";
    protected $primaryKey="tax_id";

    //function fillable
    protected $fillable=[
        'tax_id','name','value','type','is_active','descripton','created_at','updated_at','created_by','updated_by'
    ];

    //function get list
    protected function _getList(){
        $oSelect=$this->select('tax_id','name','value','type','is_active','descripton','created_at','updated_at','created_by','updated_by')->where('is_delete',0);
        return $oSelect;
    }

    //function add
    public function add(array $data)
    {
        $oTax=$this->create($data);
        return $oTax->tax_id;
    }

    //function remove
    public function remove($id){
        $this->where($this->primaryKey,$id)->update(['is_delete'=>1,'is_active'=>0]);
    }

    //function edit
    public function edit(array $data,$id)
    {
        return $this->where($this->primaryKey,$id)->update($data);
    }
    //function get item
    public function getItem($id)
    {
        return $this->where($this->primaryKey,$id)->first();
    }

    public function exportExecl(array $select)
    {
        $tax = DB::table("tax")->select($select)->get()->toArray();
        return $tax;
    }

    public function importExcel($file_name)
    {
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open($file_name);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $key => $row) {
                if ($key == 1)
                {}
                elseif ($key != 1 && $row[0] != '')
                {
                    DB::table("tax")
                        ->insert([
                            "tax_id" => $row[0],
                            "name" => $row[1],
                            "value" => $row[2],
                            "type" => $row[3],
                            "created_at" => $row[4],
                        ]);
                }
            }
        }
        $reader->close();
    }
}
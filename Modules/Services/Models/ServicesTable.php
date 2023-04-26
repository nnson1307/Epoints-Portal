<?php
/**
 * ServicesTable.
 * Le Dang Sinh
 * Date: 3/28/2018
 */

namespace Modules\Services\Models;

use Box\Spout\Reader\ReaderFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use Box\Spout\Common\Type;

class ServicesTable extends Model
{
    use ListTableTrait;

    protected $table = 'services';
    protected $primaryKey = 'service_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_id', 'service_name', 'service_code', 'service_time_id', 'description', 'services_image', 'detail',
        'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Build query table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getList()
    {
        $oSelect = $this->from($this->table . ' as sv')
            ->leftJoin('service_time', 'service_time.service_time_id', '=', 'sv.service_time_id')
            ->select('sv.service_id',
                'sv.service_name',
                'sv.service_code',
                'sv.service_time_id',
                'sv.description',
                'sv.services_image',
                'sv.detail',
                'sv.is_active',
                'sv.is_delete',
                'sv.created_at',
                'sv.updated_at',
                'sv.created_by',
                'sv.updated_by',
                'service_time.time as time')
            ->where('sv.is_delete', 0);
        return $oSelect;
    }

    /**
     * Insert services to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oServices = $this->create($data);

        return $oServices->service_id;
    }

    /**
     * Edit product label to database
     *
     * @param array $data , $id
     * @return number
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * Remove product label to database
     *
     * @param number $id
     */
    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_delete' => 1]);
    }

    /**
     * Get item
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /**
     * Function get list services time options.
     * @return array
     */
    public function getListServiceTimeOptions()
    {
        $oSelect = $this->from('service_time')->select('service_id', 'time')->get();
        $listData = array();
        foreach ($oSelect as $key => $value) {
            $listData[$value['service_id']] = $value['time'];
        }
        return $listData;
    }
    /**
     * Export Excel
     */
    public function exportExcel(array $oSelect)
    {
        $oExportExcel = DB::table($this->table . " as sv")
            ->leftJoin('service_time', 'service_time.service_time_id', '=', 'sv.service_time_id')
            ->select($oSelect)->get();
        return $oExportExcel;
    }

    /**
     * Import Excel
     */
    public function importExcel($fileName, $title)
    {
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open($fileName);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $key => $row) {
                if ($key == 1) {

                } elseif ($key != 1 && $row[0] != '') {
                    DB::table($this->table)
                        ->insert([
                            'service_name' => $row[0],
                            'service_code' => $row[1],
                            'service_time_id' => $row[2],
                            'description' => $row[3],
                            'detail' => $row[4],
                            'is_active' => $row[5]
                        ]);
                }
            }
        }
        $reader->close();
    }
}
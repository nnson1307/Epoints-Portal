<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ZNS\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ListParramsTable extends Model
{
    use ListTableTrait;
    protected $table = 'zns_list_params';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'zns_list_params_id',
        'zns_template_id',
        'value',
        'required',
        'type',
        'max_length',
        'min_length',
        'accept_null',
    ];

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function removeByZnsTemplateId($zns_template_id)
    {
        return $this->where("zns_template_id", $zns_template_id)->delete();
    }

    public function edit(array $data, $id)
    {

        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function getItemByTemplateId($zns_template_id)
    {
        return $this->where("{$this->table}.zns_template_id", $zns_template_id)->get();
    }

    public function getItemByZnsTemplateIdArray($zns_template_id)
    {
        return $this->where("{$this->table}.zns_template_id", $zns_template_id)->get()->toArray();
    }

    public function getListParams($zns_template_id)
    {
        $oSelect= self::select("{$this->table}.value")
            ->where("{$this->table}.zns_template_id", $zns_template_id)->get();
        return ($oSelect->pluck("value")->toArray());
    }

    /**
    * Mass (bulk) insert or update on duplicate for Laravel 4/5
    * 
    * insertOrUpdate([
    *   ['id'=>1,'value'=>10],
    *   ['id'=>2,'value'=>60]
    * ]);
    * 
    *
    * @param array $rows
    */
    public function insertOrUpdateRow(array $rows){
        $table = \DB::getTablePrefix().with(new self)->getTable();


        $first = reset($rows);

        $columns = implode( ',',
            array_map( function( $value ) { return "$value"; } , array_keys($first) )
        );

        $values = implode( ',', array_map( function( $row ) {
                return '('.implode( ',',
                    array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $row )
                ).')';
            } , $rows )
        );

        $updates = implode( ',',
            array_map( function( $value ) { return "$value = VALUES($value)"; } , array_keys($first) )
        );
    dd($values,$updates,$columns,$first);
        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

        return \DB::statement( $sql );
    }

}
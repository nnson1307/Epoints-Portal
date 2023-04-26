<?php

namespace Modules\Salary\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 * @package Modules\Salary\Models
 * @author VuND
 * @since 03/12/2021
 */
class BaseModel extends Model
{
    public function returntToArray($oSelect, $obj = false){
        if($oSelect){
            if($obj){
                return $oSelect;
            }
            return $oSelect->toArray();
        }

        return [];
    }

    public function updateItem($id, $data){
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function deleteItem($id){
        return $this->where($this->primaryKey, $id)->detele();
    }
}